<?php

namespace Tests\Feature;

use App\Entities\TbBase;
use App\Entities\TbCadUser;
use App\Entities\TbProfile;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Smoke test do painel Filament (/admin) e dos 7 Resources gerados.
 *
 * Não usa RefreshDatabase: o banco sqlite de teste (database/testing.sqlite)
 * já vem migrado (incluindo database/migrations/only_bd_mtz, de onde vem
 * activity_log) e é reaproveitado entre execuções, em modo autocommit —
 * assim as duas conexões PDO envolvidas (a 'sqlite' padrão e a 'adb_mtz'
 * forçada pelo middleware ReconnectDbDefault do painel) não competem por
 * uma transação aberta, o que causava "database is locked" no sqlite.
 */
class FilamentSmokeTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Migra o sqlite de teste (idempotente) num processo à parte, antes
        // do Laravel/PHPUnit abrirem qualquer transação de teste — rodar
        // isso dentro do ciclo normal do RefreshDatabase entra em conflito
        // com a segunda conexão PDO que o painel abre para 'adb_mtz'.
        $path = dirname(__DIR__, 2) . '/database/testing.sqlite';
        if (! file_exists($path)) {
            touch($path);
            // WAL evita "database is locked" entre a conexão 'sqlite' e a
            // 'adb_mtz' (forçada pelo ReconnectDbDefault do painel) quando
            // as duas apontam pro mesmo arquivo ao mesmo tempo.
            (new \PDO('sqlite:' . $path))->exec('PRAGMA journal_mode=WAL');
        }

        $env = 'DB_CONNECTION_MTZ=sqlite DB_DATABASE=database/testing.sqlite APP_ENV=testing';
        exec("{$env} php artisan migrate --path=database/migrations --force 2>&1");
        exec("{$env} php artisan migrate --path=database/migrations/only_bd_mtz --force 2>&1");
    }

    protected function setUp(): void
    {
        parent::setUp();

        // ReconnectDbDefault força a conexão 'adb_mtz', que em produção é
        // MySQL. Em teste redirecionamos para o mesmo arquivo sqlite.
        config(['database.connections.adb_mtz' => config('database.connections.sqlite')]);
    }

    private function makeAdmin(): TbCadUser
    {
        Role::findOrCreate('Admin', 'web');

        $base = TbBase::firstOrCreate(['sigla' => 'MTZ'], ['name' => 'Matriz']);
        $profile = TbProfile::firstOrCreate(['name' => 'Administrador']);

        $user = TbCadUser::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin Teste',
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

    public function test_admin_login_page_loads(): void
    {
        $this->get('/admin/login')->assertStatus(200);
    }

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_view_every_resource_index(): void
    {
        $admin = $this->makeAdmin();
        $this->withoutExceptionHandling();
        $this->actingAs($admin);

        foreach ($this->resourceIndexRoutes() as $route) {
            $this->get($route)->assertStatus(200);
        }
    }

    private function resourceIndexRoutes(): array
    {
        return [
            '/admin/tb-payment-types',
            '/admin/permissions',
            '/admin/roles',
            '/admin/tb-bases',
            '/admin/tb-caixas',
            '/admin/tb-operations',
            '/admin/tb-type-launches',
        ];
    }
}
