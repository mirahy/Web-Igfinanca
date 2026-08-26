<?php

namespace Tests\Feature;

use App\Entities\TbBase;
use App\Entities\TbCadUser;
use App\Entities\TbProfile;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Regressão: app/Http/Kernel.php registrava os aliases 'role'/'permission'/
 * 'role_or_permission' apontando para Spatie\Permission\Middlewares\* (plural),
 * namespace removido no spatie/laravel-permission 6.x (agora é Middleware,
 * singular). Qualquer rota gateada por esses middlewares (ex: /launchs-cl,
 * /roles) retornava 500 (BindingResolutionException) em produção.
 */
class PermissionMiddlewareRegressionTest extends TestCase
{
    private function makeUserWithPermission(string $permission): TbCadUser
    {
        Permission::findOrCreate($permission, 'web');

        $base = TbBase::firstOrCreate(['sigla' => 'MTZ'], ['name' => 'Matriz']);
        $profile = TbProfile::firstOrCreate(['name' => 'Administrador']);

        $user = TbCadUser::firstOrCreate(
            ['email' => 'permtest@example.com'],
            [
                'name' => 'Permission Test',
                'idtb_profile' => $profile->id,
                'idtb_base' => $base->id,
                'password' => bcrypt('secret'),
                'status' => 1,
            ]
        );

        if (! $user->hasPermissionTo($permission)) {
            $user->givePermissionTo($permission);
        }

        return $user;
    }

    public function test_permission_gated_route_does_not_throw_binding_resolution_exception(): void
    {
        $user = $this->makeUserWithPermission('launch-list');

        $this->withoutExceptionHandling();

        $this->actingAs($user)
            ->withSession(['user' => ['id' => $user->id]])
            ->get('/launchs-cl')
            ->assertStatus(200);
    }
}
