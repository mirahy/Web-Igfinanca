<?php

namespace Tests\Feature;

use App\Entities\TbBase;
use App\Entities\TbCadUser;
use App\Entities\TbProfile;
use App\Filament\Resources\RolesResource\RelationManagers\PermissionsRelationManager;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RolesPermissionsRelationManagerTest extends TestCase
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

    private function makeAdmin(): TbCadUser
    {
        Role::findOrCreate('Admin', 'web');

        $base = TbBase::firstOrCreate(['sigla' => 'MTZ'], ['name' => 'Matriz']);
        $profile = TbProfile::firstOrCreate(['name' => 'Administrador']);

        $user = TbCadUser::firstOrCreate(
            ['email' => 'reltest@example.com'],
            [
                'name' => 'Relation Test',
                'idtb_profile' => $profile->id,
                'idtb_base' => $base->id,
                'password' => bcrypt('secret'),
                'status' => 1,
            ]
        );

        if (! $user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }

        return $user;
    }

    public function test_role_edit_page_loads_with_permissions_relation_manager(): void
    {
        $admin = $this->makeAdmin();
        $role = Role::findOrCreate('Financeiro', 'web');

        $this->actingAs($admin)
            ->get("/admin/roles/{$role->id}/edit")
            ->assertStatus(200)
            ->assertSeeLivewire(PermissionsRelationManager::class);
    }

    public function test_can_attach_and_detach_permission_via_relation_manager(): void
    {
        $admin = $this->makeAdmin();
        $role = Role::findOrCreate('Tesoureiro', 'web');
        $permission = Permission::findOrCreate('launch-list', 'web');

        $this->actingAs($admin);

        Livewire::test(PermissionsRelationManager::class, [
            'ownerRecord' => $role,
            'pageClass' => \App\Filament\Resources\RolesResource\Pages\EditRoles::class,
        ])
            ->callTableAction('attach', null, data: ['recordId' => $permission->id])
            ->assertHasNoTableActionErrors();

        $this->assertTrue($role->fresh()->hasPermissionTo('launch-list'));

        Livewire::test(PermissionsRelationManager::class, [
            'ownerRecord' => $role,
            'pageClass' => \App\Filament\Resources\RolesResource\Pages\EditRoles::class,
        ])
            ->callTableAction('detach', $permission)
            ->assertHasNoTableActionErrors();

        $this->assertFalse($role->fresh()->hasPermissionTo('launch-list'));
    }
}
