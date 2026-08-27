<?php

namespace Tests\Feature;

use App\Entities\TbBase;
use App\Entities\TbCadUser;
use App\Entities\TbProfile;
use App\Filament\Pages\SelectFilial;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SelectFilialTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        $path = dirname(__DIR__, 2) . '/database/testing.sqlite';
        if (! file_exists($path)) {
            touch($path);
            (new \PDO('sqlite:' . $path))->exec('PRAGMA journal_mode=WAL');
        }

        $env = 'DB_CONNECTION_MTZ=sqlite DB_DATABASE=database/testing.sqlite APP_ENV=testing';
        exec("{$env} php artisan migrate --path=database/migrations --force 2>&1");
        exec("{$env} php artisan migrate --path=database/migrations/only_bd_mtz --force 2>&1");
    }

    protected function setUp(): void
    {
        parent::setUp();

        config(['database.connections.adb_mtz' => config('database.connections.sqlite')]);
    }

    private function makeUser(string $email, ?string $role = null): TbCadUser
    {
        $profile = TbProfile::firstOrCreate(['name' => 'Perfil Teste']);
        $homeBase = TbBase::firstOrCreate(['sigla' => 'MTZ'], ['name' => 'Matriz']);

        $user = TbCadUser::firstOrCreate(
            ['email' => $email],
            [
                'name' => $email,
                'idtb_profile' => $profile->id,
                'idtb_base' => $homeBase->id,
                'password' => bcrypt('secret'),
                'status' => 1,
            ]
        );

        if ($role) {
            Role::findOrCreate($role, 'web');
            if (! $user->hasRole($role)) {
                $user->assignRole($role);
            }
        }

        return $user;
    }

    public function test_admin_sees_every_base_as_an_option(): void
    {
        $admin = $this->makeUser('admin-filial@example.com', 'Admin');

        TbBase::firstOrCreate(['sigla' => 'VLA'], ['name' => 'Vila']);
        TbBase::firstOrCreate(['sigla' => 'SED'], ['name' => 'Sede']);

        Livewire::actingAs($admin)
            ->test(SelectFilial::class)
            ->assertFormFieldExists('idtb_base');

        $options = \App\Filament\Support\ResolvesFilialConnection::availableBasesFor($admin);
        $this->assertGreaterThanOrEqual(3, $options->count());
    }

    public function test_non_admin_only_sees_assigned_bases(): void
    {
        $user = $this->makeUser('launch-manager-filial@example.com', null);

        $allowed = TbBase::firstOrCreate(['sigla' => 'VLA'], ['name' => 'Vila']);
        TbBase::firstOrCreate(['sigla' => 'SED'], ['name' => 'Sede']);

        $user->bases()->syncWithoutDetaching([$allowed->id]);

        $options = \App\Filament\Support\ResolvesFilialConnection::availableBasesFor($user->fresh());

        $this->assertCount(1, $options);
        $this->assertSame($allowed->id, $options->first()->id);
    }

    public function test_selecting_an_unauthorized_base_is_rejected(): void
    {
        $user = $this->makeUser('launch-manager-filial2@example.com', null);

        $allowed = TbBase::firstOrCreate(['sigla' => 'VLA2'], ['name' => 'Vila 2']);
        $forbidden = TbBase::firstOrCreate(['sigla' => 'SED2'], ['name' => 'Sede 2']);

        $user->bases()->syncWithoutDetaching([$allowed->id]);

        Livewire::actingAs($user->fresh())
            ->test(SelectFilial::class)
            ->fillForm(['idtb_base' => $forbidden->id])
            ->call('submit');

        $this->assertNull(session('filament.base'));
    }
}
