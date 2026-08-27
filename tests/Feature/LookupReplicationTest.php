<?php

namespace Tests\Feature;

use App\Entities\TbBase;
use App\Entities\TbCadUser;
use App\Entities\TbCaixa;
use App\Entities\TbPaymentType;
use App\Entities\TbProfile;
use App\Filament\Resources\TbCaixaResource\Pages\CreateTbCaixa;
use App\Filament\Resources\TbCaixaResource\Pages\EditTbCaixa;
use App\Filament\Resources\TbPaymentTypeResource\Pages\CreateTbPaymentType;
use App\Filament\Resources\TbPaymentTypeResource\Pages\EditTbPaymentType;
use App\Http\Controllers\ConnectDbController;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Testa a replicação matriz->filiais (Fase E.0.5) dos Resources "lookup" do
 * Filament (aqui, TbPaymentType e TbCaixa como representantes do padrão
 * compartilhado por TbTypeLaunch/TbBase/TbOperation), rastreando o registro
 * correspondente em cada filial pela coluna id_mtz.
 *
 * Usa 3 arquivos sqlite distintos (matriz, adb_vla, adb_sed) para simular de
 * fato 3 conexões físicas separadas, já que ConnectDbController::connectBases()
 * troca a conexão padrão do Eloquent pelo nome literal da sigla da base.
 */
class LookupReplicationTest extends TestCase
{
    private static string $vlaPath;
    private static string $sedPath;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $base = dirname(__DIR__, 2) . '/database';
        $mtzPath = $base . '/testing.sqlite';
        self::$vlaPath = $base . '/testing_vla.sqlite';
        self::$sedPath = $base . '/testing_sed.sqlite';

        foreach ([$mtzPath, self::$vlaPath, self::$sedPath] as $path) {
            if (! file_exists($path)) {
                touch($path);
                (new \PDO('sqlite:' . $path))->exec('PRAGMA journal_mode=WAL');
            }
        }

        $migrate = fn (string $dbPath) => exec(
            "DB_CONNECTION_MTZ=sqlite DB_DATABASE={$dbPath} APP_ENV=testing php artisan migrate --path=database/migrations --force 2>&1"
        );

        $migrate('database/testing.sqlite');
        exec('DB_CONNECTION_MTZ=sqlite DB_DATABASE=database/testing.sqlite APP_ENV=testing php artisan migrate --path=database/migrations/only_bd_mtz --force 2>&1');
        $migrate('database/testing_vla.sqlite');
        $migrate('database/testing_sed.sqlite');
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.connections.adb_mtz' => config('database.connections.sqlite')]);
        config(['database.connections.adb_vla' => [
            'driver' => 'sqlite',
            'database' => self::$vlaPath,
            'prefix' => '',
        ]]);
        config(['database.connections.adb_sed' => [
            'driver' => 'sqlite',
            'database' => self::$sedPath,
            'prefix' => '',
        ]]);

        // Garante que cada teste começa e termina na conexão da matriz —
        // o loop de replicação deixa a conexão padrão apontando pra última
        // filial visitada.
        app(ConnectDbController::class)->connectMatriz();
        $this->beforeApplicationDestroyed(fn () => app(ConnectDbController::class)->connectMatriz());
    }

    private function makeAdmin(): TbCadUser
    {
        Role::findOrCreate('Admin', 'web');

        TbBase::query()->delete();
        TbBase::create(['name' => 'Matriz', 'sigla' => 'adb_mtz']);
        TbBase::create(['name' => 'Filial Vila', 'sigla' => 'adb_vla']);
        TbBase::create(['name' => 'Filial Sede', 'sigla' => 'adb_sed']);
        $matriz = TbBase::where('sigla', 'adb_mtz')->first();

        $profile = TbProfile::firstOrCreate(['name' => 'Administrador']);

        $user = TbCadUser::firstOrCreate(
            ['email' => 'lookupreplication@example.com'],
            [
                'name' => 'Lookup Replication Test',
                'idtb_profile' => $profile->id,
                'idtb_base' => $matriz->id,
                'password' => bcrypt('secret'),
                'status' => 1,
            ]
        );

        if (! $user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }

        return $user;
    }

    public function test_creating_a_payment_type_replicates_to_every_filial_with_id_mtz(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        Livewire::test(CreateTbPaymentType::class)
            ->fillForm(['name' => 'Pix', 'descripion' => 'Pagamento instantâneo'])
            ->call('create')
            ->assertHasNoFormErrors();

        app(ConnectDbController::class)->connectMatriz();
        $matrizRecord = TbPaymentType::where('name', 'Pix')->firstOrFail();

        foreach (['adb_vla', 'adb_sed'] as $sigla) {
            app(ConnectDbController::class)->connectBases($sigla);
            $filialRecord = TbPaymentType::where('id_mtz', $matrizRecord->id)->first();

            $this->assertNotNull($filialRecord, "Registro não replicado para {$sigla}");
            $this->assertSame('Pix', $filialRecord->name);
        }
    }

    public function test_updating_a_payment_type_updates_every_filial_copy(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        Livewire::test(CreateTbPaymentType::class)
            ->fillForm(['name' => 'Boleto', 'descripion' => 'original'])
            ->call('create')
            ->assertHasNoFormErrors();

        app(ConnectDbController::class)->connectMatriz();
        $matrizRecord = TbPaymentType::where('name', 'Boleto')->firstOrFail();

        Livewire::test(EditTbPaymentType::class, ['record' => $matrizRecord->getKey()])
            ->fillForm(['name' => 'Boleto', 'descripion' => 'atualizado'])
            ->call('save')
            ->assertHasNoFormErrors();

        foreach (['adb_vla', 'adb_sed'] as $sigla) {
            app(ConnectDbController::class)->connectBases($sigla);
            $filialRecord = TbPaymentType::where('id_mtz', $matrizRecord->id)->firstOrFail();

            $this->assertSame('atualizado', $filialRecord->descripion);
        }
    }

    public function test_deleting_a_payment_type_deletes_every_filial_copy(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        Livewire::test(CreateTbPaymentType::class)
            ->fillForm(['name' => 'Cartão', 'descripion' => 'original'])
            ->call('create')
            ->assertHasNoFormErrors();

        app(ConnectDbController::class)->connectMatriz();
        $matrizRecord = TbPaymentType::where('name', 'Cartão')->firstOrFail();
        $mtzId = $matrizRecord->id;

        Livewire::test(EditTbPaymentType::class, ['record' => $mtzId])
            ->callAction('delete');

        foreach (['adb_vla', 'adb_sed'] as $sigla) {
            app(ConnectDbController::class)->connectBases($sigla);
            $this->assertNull(TbPaymentType::where('id_mtz', $mtzId)->first(), "Registro não removido em {$sigla}");
        }
    }

    public function test_creating_a_caixa_replicates_with_soft_delete_support(): void
    {
        $admin = $this->makeAdmin();
        $this->actingAs($admin);

        Livewire::test(CreateTbCaixa::class)
            ->fillForm(['name' => 'Caixa Principal', 'description' => 'teste'])
            ->call('create')
            ->assertHasNoFormErrors();

        app(ConnectDbController::class)->connectMatriz();
        $matrizRecord = TbCaixa::where('name', 'Caixa Principal')->firstOrFail();
        $mtzId = $matrizRecord->id;

        Livewire::test(EditTbCaixa::class, ['record' => $mtzId])
            ->callAction('delete');

        $this->assertNotNull(TbCaixa::withTrashed()->find($mtzId)->deleted_at, 'Exclusão na matriz deveria ser soft-delete');

        foreach (['adb_vla', 'adb_sed'] as $sigla) {
            app(ConnectDbController::class)->connectBases($sigla);
            $filialRecord = TbCaixa::withTrashed()->where('id_mtz', $mtzId)->first();

            $this->assertNotNull($filialRecord, "Registro não replicado para {$sigla}");
            $this->assertNotNull($filialRecord->deleted_at, "Exclusão não replicada (soft-delete) para {$sigla}");
        }
    }
}
