<?php

namespace Tests\Feature;

use App\Entities\TbBase;
use App\Entities\TbCadUser;
use App\Entities\TbCaixa;
use App\Entities\TbClosing;
use App\Entities\TbLaunch;
use App\Entities\TbOperation;
use App\Entities\TbPaymentType;
use App\Entities\TbProfile;
use App\Entities\TbTypeLaunch;
use App\Filament\Resources\TbLaunchResource\Pages\CreateTbLaunch;
use App\Filament\Resources\TbLaunchResource\Pages\EditTbLaunch;
use App\Filament\Support\ResolvesFilialConnection;
use App\Http\Controllers\ConnectDbController;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Testa o TbLaunchResource unificado (Fase E.2): criação/edição/exclusão
 * delegando pra App\Services\TbLaunchService (bloqueio de período fechado
 * incluso), replicação matriz<->filial via id_filial/id_mtz, e a ação de
 * aprovar/reprovar checando a permission launch-approves contra a matriz.
 *
 * Mesma ideia de 3 sqlite físicos (matriz + adb_vla + adb_sed) de
 * tests/Feature/LookupReplicationTest.php pra simular duas conexões de
 * filial distintas, mas com arquivos de filial próprios (ver
 * setUpBeforeClass) já que aqui os ids de tb_caixa/tb_operation/etc são
 * fixos.
 */
class TbLaunchResourceTest extends TestCase
{
    private static string $vlaPath;
    private static string $sedPath;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $base = dirname(__DIR__, 2) . '/database';
        $mtzPath = $base . '/testing.sqlite';
        // Arquivos próprios (não os de LookupReplicationTest) porque este
        // teste semeia tb_caixa/tb_operation/tb_type_launch/tb_payment_type
        // com ids fixos na filial — compartilhar o arquivo com outro teste
        // que cria registros com auto-increment correria risco de colisão
        // de id na mesma suíte.
        self::$vlaPath = $base . '/testing_launch_vla.sqlite';
        self::$sedPath = $base . '/testing_launch_sed.sqlite';

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
        $migrate(self::$vlaPath);
        $migrate(self::$sedPath);
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

        app(ConnectDbController::class)->connectMatriz();
        $this->beforeApplicationDestroyed(fn () => app(ConnectDbController::class)->connectMatriz());
    }

    /**
     * Semeia matriz + filial 'adb_vla' com os cadastros necessários pro
     * lançamento (caixa, operação, tipo de lançamento, tipo de pagamento e
     * fechamento) diretamente em cada conexão — sem passar pela replicação
     * da Fase E.0.5, que já tem cobertura própria em LookupReplicationTest.
     */
    private function seedFilial(): TbBase
    {
        Role::findOrCreate('Admin', 'web');

        // firstOrCreate (não delete+recreate) porque tests/Feature/LookupReplicationTest.php
        // compartilha a mesma matriz sqlite e as mesmas siglas adb_mtz/adb_vla/adb_sed.
        TbBase::firstOrCreate(['sigla' => 'adb_mtz'], ['name' => 'Matriz']);
        $vla = TbBase::firstOrCreate(['sigla' => 'adb_vla'], ['name' => 'Filial Vila']);
        TbBase::firstOrCreate(['sigla' => 'adb_sed'], ['name' => 'Filial Sede']);

        // Ver comentário equivalente em LookupReplicationTest::makeAdmin().
        TbBase::whereNotIn('sigla', ['adb_mtz', 'adb_vla', 'adb_sed'])->delete();

        TbLaunch::withTrashed()->forceDelete();

        $profile = TbProfile::firstOrCreate(['name' => 'Administrador']);
        $matriz = TbBase::where('sigla', 'adb_mtz')->first();

        $admin = TbCadUser::firstOrCreate(
            ['email' => 'launch-admin@example.com'],
            [
                'name' => 'Launch Admin',
                'idtb_profile' => $profile->id,
                'idtb_base' => $matriz->id,
                'password' => bcrypt('secret'),
                'status' => 1,
            ]
        );
        if (! $admin->hasRole('Admin')) {
            $admin->assignRole('Admin');
        }

        app(ConnectDbController::class)->connectBases('adb_vla');

        TbCadUser::firstOrCreate(
            ['email' => 'launch-admin@example.com'],
            [
                'name' => 'Launch Admin',
                'idtb_profile' => $profile->id,
                'idtb_base' => $matriz->id,
                'password' => bcrypt('secret'),
                'status' => 1,
                'id' => $admin->id,
            ]
        );

        TbLaunch::withTrashed()->forceDelete();
        TbClosing::withTrashed()->forceDelete();
        TbCaixa::withTrashed()->forceDelete();
        TbOperation::query()->delete();
        TbTypeLaunch::query()->delete();
        TbPaymentType::query()->delete();

        TbCaixa::create(['id' => 1, 'name' => 'Caixa Principal']);
        TbOperation::create(['id' => 1, 'name' => 'Entrada']);
        TbOperation::create(['id' => 2, 'name' => 'Saída']);
        TbTypeLaunch::create(['id' => 1, 'name' => 'Dízimo']);
        TbPaymentType::create(['id' => 1, 'name' => 'Dinheiro']);
        TbClosing::create(['id' => 1, 'month' => 'Agosto', 'year' => 2026, 'status' => 1, 'period_valid' => 0]);

        app(ConnectDbController::class)->connectMatriz();

        ResolvesFilialConnection::setCurrentBase($vla);

        return $vla;
    }

    private function actingAsAdmin(): TbCadUser
    {
        return TbCadUser::where('email', 'launch-admin@example.com')->firstOrFail();
    }

    public function test_creating_a_launch_replicates_a_mirror_to_the_matriz(): void
    {
        $this->seedFilial();
        $admin = $this->actingAsAdmin();
        $this->actingAs($admin);

        Livewire::test(CreateTbLaunch::class)
            ->fillForm([
                'id_user' => $admin->id,
                'idtb_operation' => 1,
                'idtb_type_launch' => 1,
                'idtb_payment_type' => 1,
                'idtb_caixa' => 1,
                'idtb_closing' => 1,
                'operation_date' => '2026-08-15',
                'value' => 150.5,
                'description' => 'Oferta especial',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        app(ConnectDbController::class)->connectBases('adb_vla');
        $filialLaunch = TbLaunch::where('description', 'Oferta especial')->firstOrFail();
        $this->assertSame(0, (int) $filialLaunch->status);
        $this->assertNotNull($filialLaunch->id_mtz);

        app(ConnectDbController::class)->connectMatriz();
        $matrizLaunch = TbLaunch::where('id', $filialLaunch->id_mtz)->first();
        $this->assertNotNull($matrizLaunch, 'Lançamento não espelhado na matriz');
        $this->assertEquals($filialLaunch->id, $matrizLaunch->id_filial);
        $this->assertSame('Oferta especial', $matrizLaunch->description);
    }

    public function test_creating_a_launch_in_a_closed_period_is_blocked(): void
    {
        $this->seedFilial();
        $admin = $this->actingAsAdmin();
        $this->actingAs($admin);

        app(ConnectDbController::class)->connectBases('adb_vla');
        TbClosing::where('id', 1)->update(['status' => 0]);
        app(ConnectDbController::class)->connectMatriz();

        Livewire::test(CreateTbLaunch::class)
            ->fillForm([
                'id_user' => $admin->id,
                'idtb_operation' => 1,
                'idtb_type_launch' => 1,
                'idtb_payment_type' => 1,
                'idtb_caixa' => 1,
                'idtb_closing' => 1,
                'operation_date' => '2026-08-15',
                'value' => 10,
            ])
            ->call('create');

        app(ConnectDbController::class)->connectBases('adb_vla');
        $this->assertSame(0, TbLaunch::count());
    }

    public function test_updating_a_launch_updates_the_matriz_mirror(): void
    {
        $this->seedFilial();
        $admin = $this->actingAsAdmin();
        $this->actingAs($admin);

        Livewire::test(CreateTbLaunch::class)
            ->fillForm([
                'id_user' => $admin->id,
                'idtb_operation' => 1,
                'idtb_type_launch' => 1,
                'idtb_payment_type' => 1,
                'idtb_caixa' => 1,
                'idtb_closing' => 1,
                'operation_date' => '2026-08-15',
                'value' => 100,
                'description' => 'original',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        app(ConnectDbController::class)->connectBases('adb_vla');
        $filialLaunch = TbLaunch::where('description', 'original')->firstOrFail();
        $idMtz = $filialLaunch->id_mtz;
        app(ConnectDbController::class)->connectMatriz();

        Livewire::test(EditTbLaunch::class, ['record' => $filialLaunch->getKey()])
            ->fillForm([
                'id_user' => $admin->id,
                'idtb_operation' => 1,
                'idtb_type_launch' => 1,
                'idtb_payment_type' => 1,
                'idtb_caixa' => 1,
                'idtb_closing' => 1,
                'operation_date' => '2026-08-15',
                'value' => 200,
                'description' => 'atualizado',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        app(ConnectDbController::class)->connectBases('adb_vla');
        $this->assertSame('atualizado', TbLaunch::find($filialLaunch->id)->description);

        app(ConnectDbController::class)->connectMatriz();
        $this->assertSame('atualizado', TbLaunch::find($idMtz)->description);
    }

    /**
     * Regressão de produção: editar e salvar um Lançamento dava 404 no
     * POST /livewire/update. Causa raiz: o synthesizer nativo do Livewire
     * reidrata a propriedade $record (buscando o Model no banco) ANTES de
     * qualquer código nosso rodar — e como cada requisição HTTP passa de
     * novo pelo middleware ReconnectDbDefault (que sempre força a conexão
     * pra matriz), essa busca falha pra um id que só existe na filial,
     * virando 404 (Laravel converte ModelNotFoundException não capturada
     * em NotFoundHttpException).
     *
     * Livewire::test() não reproduz isso — ele monta o componente
     * diretamente, sem passar pelo pipeline de middleware HTTP do painel.
     * Por isso este teste faz uma requisição HTTP de verdade: GET na
     * página de edição (extraindo o wire:snapshot do HTML, exatamente
     * como o navegador faz) seguido de um POST real em /livewire/update
     * simulando o clique em "Salvar alterações", com a conexão
     * deliberadamente resetada pra matriz entre as duas chamadas — a
     * mesma condição de cada requisição real em produção.
     */
    public function test_editing_a_launch_via_a_real_http_request_does_not_404(): void
    {
        $this->seedFilial();
        $admin = $this->actingAsAdmin();
        $this->actingAs($admin);

        // O bug só se manifesta quando o id local da filial não existe *de
        // jeito nenhum* na matriz (aí sim vira ModelNotFoundException/404).
        // Numa base de teste zerada, criar 1 lançamento faz o id da filial
        // (autoincrement raso) coincidir com QUALQUER registro de id baixo
        // que porventura exista na matriz — inclusive um espelho de outro
        // lançamento — mascarando o bug (a hidratação "erra a conexão" mas
        // ainda assim acha alguma linha, sem lançar exceção). Inflar o
        // autoincrement da FILIAL (não da matriz) garante um id local alto
        // o bastante pra não existir na matriz, reproduzindo de fato a
        // situação de produção (onde a matriz acumula histórico de todas
        // as filiais e tem autoincrement muito mais avançado que qualquer
        // uma isolada — o oposto do que uma base de teste zerada produz).
        app(ConnectDbController::class)->connectBases('adb_vla');
        DB::connection('adb_vla')->table('tb_launch')->insert(array_fill(0, 50, [
            'id_user' => $admin->id, 'description' => 'preenchimento', 'operation_date' => '2026-01-01',
            'value' => 1, 'idtb_operation' => 1, 'idtb_type_launch' => 1, 'idtb_payment_type' => 1,
            'idtb_caixa' => 1, 'idtb_base' => 1, 'status' => 0, 'idtb_closing' => 1,
        ]));
        app(ConnectDbController::class)->connectMatriz();

        Livewire::test(CreateTbLaunch::class)
            ->fillForm([
                'id_user' => $admin->id,
                'idtb_operation' => 1,
                'idtb_type_launch' => 1,
                'idtb_payment_type' => 1,
                'idtb_caixa' => 1,
                'idtb_closing' => 1,
                'operation_date' => '2026-08-15',
                'value' => 100,
                'description' => 'http-original',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        app(ConnectDbController::class)->connectBases('adb_vla');
        $filialLaunch = TbLaunch::where('description', 'http-original')->firstOrFail();
        $idMtz = $filialLaunch->id_mtz;
        app(ConnectDbController::class)->connectMatriz();

        $html = $this->get("/admin/tb-launches/{$filialLaunch->id}/edit")->assertOk()->getContent();

        preg_match_all('/wire:snapshot="([^"]+)"/', $html, $allMatches);
        $this->assertNotEmpty($allMatches[1] ?? [], 'wire:snapshot não encontrado no HTML da página de edição');

        $matches = null;
        foreach ($allMatches[1] as $candidate) {
            if (str_contains($candidate, 'edit-tb-launch')) {
                $matches = [null, $candidate];
                break;
            }
        }
        $this->assertNotNull($matches, 'snapshot do componente EditTbLaunch não encontrado (achou ' . count($allMatches[1]) . ' snapshots no total)');

        // O snapshot embutido no HTML fica intacto (igual ao navegador faz)
        // — o Livewire valida sua integridade via checksum, então mudar de
        // valor exigiria recalcular esse checksum. Salvar sem alterar nada
        // já é suficiente pra reproduzir o bug: ele acontece na hidratação
        // de $record, antes de qualquer campo ser processado.
        $snapshot = htmlspecialchars_decode($matches[1]);

        // Nova requisição PHP "de verdade": ReconnectDbDefault força a
        // conexão de volta pra matriz, exatamente como aconteceria numa
        // requisição HTTP real subsequente. DB::purge() descarta qualquer
        // PDO já aberto pra filial, pra não mascarar o bug com uma conexão
        // "presa" que só existe porque tudo roda no mesmo processo PHP do
        // PHPUnit (em produção cada requisição é um processo à parte, sem
        // nada em comum entre o GET e o POST além da sessão/banco).
        DB::purge('adb_vla');
        DB::purge('adb_mtz');
        app(ConnectDbController::class)->connectMatriz();

        $response = $this->postJson('/livewire/update', [
            'components' => [[
                'snapshot' => $snapshot,
                'updates' => [],
                'calls' => [['path' => '', 'method' => 'save', 'params' => []]],
            ]],
        ]);

        $response->assertOk();
        $response->assertJsonMissing(['status' => 404]);

        app(ConnectDbController::class)->connectBases('adb_vla');
        $this->assertNotNull(TbLaunch::find($filialLaunch->id), 'Lançamento não encontrado na filial após salvar');

        app(ConnectDbController::class)->connectMatriz();
        $this->assertNotNull(TbLaunch::find($idMtz), 'Espelho na matriz não encontrado após salvar');
    }

    public function test_approving_a_launch_via_the_service_mirrors_the_status_to_the_matriz(): void
    {
        $this->seedFilial();
        $admin = $this->actingAsAdmin();
        $this->actingAs($admin);

        Livewire::test(CreateTbLaunch::class)
            ->fillForm([
                'id_user' => $admin->id,
                'idtb_operation' => 1,
                'idtb_type_launch' => 1,
                'idtb_payment_type' => 1,
                'idtb_caixa' => 1,
                'idtb_closing' => 1,
                'operation_date' => '2026-08-15',
                'value' => 50,
                'description' => 'a aprovar',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        app(ConnectDbController::class)->connectBases('adb_vla');
        $filialLaunch = TbLaunch::where('description', 'a aprovar')->firstOrFail();
        $idMtz = $filialLaunch->id_mtz;

        // aprov_id(), assim como store()/update(), espera ser chamado com a
        // conexão já na filial — quem reverte pra matriz é o próprio método.
        $result = app(\App\Services\TbLaunchService::class)->aprov_id([
            'id' => $filialLaunch->id,
            'status' => 1,
        ]);

        $this->assertTrue($result['success']);

        app(ConnectDbController::class)->connectBases('adb_vla');
        $this->assertSame(1, (int) TbLaunch::find($filialLaunch->id)->status);

        app(ConnectDbController::class)->connectMatriz();
        $this->assertSame(1, (int) TbLaunch::find($idMtz)->status);
    }

    public function test_approve_action_is_gated_by_launch_approves_permission_checked_on_matriz(): void
    {
        $this->seedFilial();
        $admin = $this->actingAsAdmin();
        $this->actingAs($admin);

        // Sqlite de teste acumula entre execuções — garante que o admin
        // começa sem a permission antes de checar o caso "sem permissão".
        Permission::findOrCreate('launch-approves', 'web');
        if ($admin->hasPermissionTo('launch-approves')) {
            $admin->revokePermissionTo('launch-approves');
        }

        $userCanApprove = new \ReflectionMethod(\App\Filament\Resources\TbLaunchResource::class, 'userCanApprove');
        $userCanApprove->setAccessible(true);
        $cache = new \ReflectionProperty(\App\Filament\Resources\TbLaunchResource::class, 'canApproveCache');
        $cache->setAccessible(true);

        $cache->setValue(null, null);
        $this->assertFalse($userCanApprove->invoke(null));

        // userCanApprove() deixa a conexão de volta na filial ao terminar —
        // a permission precisa ser criada/concedida na matriz, então força
        // a conexão antes de mexer em Role/Permission.
        app(ConnectDbController::class)->connectMatriz();
        Permission::findOrCreate('launch-approves', 'web');
        $admin->givePermissionTo('launch-approves');

        $cache->setValue(null, null);
        $this->assertTrue($userCanApprove->invoke(null));

        // A checagem roda contra a matriz por dentro, mas precisa devolver
        // a conexão pra filial ativa da sessão ao terminar.
        $this->assertSame(self::$vlaPath, DB::connection()->getConfig('database'));
    }
}
