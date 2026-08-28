<?php

namespace Tests\Feature;

use App\Entities\TbBase;
use App\Entities\TbCadUser;
use App\Entities\TbProfile;
use App\Livewire\FilialBadge;
use App\Livewire\FilialSwitcher;
use App\Filament\Support\ResolvesFilialConnection;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Badge (topbar, antes do menu do usuário) + seletor rápido (dentro do
 * menu do usuário) de troca de filial no painel.
 */
class FilialSwitcherTest extends TestCase
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

    private function makeAdmin(string $email): TbCadUser
    {
        Role::findOrCreate('Admin', 'web');
        $profile = TbProfile::firstOrCreate(['name' => 'Perfil Teste']);
        $homeBase = TbBase::firstOrCreate(['sigla' => 'SWMTZ'], ['name' => 'Matriz']);

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

        if (! $user->hasRole('Admin')) {
            $user->assignRole('Admin');
        }

        return $user;
    }

    public function test_badge_shows_nothing_when_no_filial_is_selected(): void
    {
        $admin = $this->makeAdmin('badge-none@example.com');
        ResolvesFilialConnection::clearCurrentBase();

        Livewire::actingAs($admin)
            ->test(FilialBadge::class)
            ->assertDontSeeHtml('fi-badge');
    }

    public function test_badge_shows_the_current_filial_name(): void
    {
        $admin = $this->makeAdmin('badge-shows@example.com');
        $base = TbBase::firstOrCreate(['sigla' => 'SWBADGE'], ['name' => 'Filial do Badge']);
        ResolvesFilialConnection::setCurrentBase($base);

        Livewire::actingAs($admin)
            ->test(FilialBadge::class)
            ->assertSee('Filial do Badge');
    }

    public function test_switcher_lists_bases_available_to_the_user_with_the_current_one_selected(): void
    {
        $admin = $this->makeAdmin('switcher-list@example.com');
        $base = TbBase::firstOrCreate(['sigla' => 'SWLIST'], ['name' => 'Filial da Lista']);
        ResolvesFilialConnection::setCurrentBase($base);

        Livewire::actingAs($admin)
            ->test(FilialSwitcher::class)
            ->assertSet('idtbBase', $base->id)
            ->assertSeeHtml('Filial da Lista');
    }

    public function test_switcher_changes_the_active_filial_and_redirects(): void
    {
        $admin = $this->makeAdmin('switcher-change@example.com');
        $current = TbBase::firstOrCreate(['sigla' => 'SWFROM'], ['name' => 'De Onde']);
        $target = TbBase::firstOrCreate(['sigla' => 'SWTO'], ['name' => 'Para Onde']);
        ResolvesFilialConnection::setCurrentBase($current);

        Livewire::actingAs($admin)
            ->test(FilialSwitcher::class)
            ->set('idtbBase', $target->id)
            ->assertRedirect();

        $this->assertSame($target->id, ResolvesFilialConnection::currentBase()['id'] ?? null);
    }
}
