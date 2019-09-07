<?php

use Illuminate\Database\Seeder;
use App\Entities\TbCadUser;
use App\Entities\TbBase;
use App\Entities\TbProfile;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
        public function run()
        {
          
          // Apaga toda a tabela de usuários
          //DB::table('Tb_Cad_User')->truncate();

          // Cria usuários admins (dados controlados)
          $this->createAdmin();
          // Cria usuários demo (dados faker)
          $this->createUsers();  

        }
  


         private  function createAdmin()
        {
              TbCadUser::create([
                'name'          => 'Mirahy Fonseca',
                'idtb_profile'  => 1,
                'idtb_base'     => 1,
                'birth'         => '1989-12-06',
                'email'         => 'mirahy@admin.com.br',
                'password'      =>  env("PASSWORD_HASH") ? bcrypt('12345') : '12345',
                'status'        => '1',
                'permission'    => '2'
            
              ]);
              $this->command->info('Admin mirahy@admin.com.br user created');
          }

          private function createUsers()
          {
              $max = rand(10, 30);
              for($i=0; $i < $max; $i++):
                  $this->createUser($i+$max);
              endfor;
              $this->command->info($max . ' demo users created');
          }

          private function createUser($index)
          {   
              return TbCadUser::create([
                  'name'          => 'Mirahy Branco Fonseca'. $index,
                  'idtb_profile'  => 1,
                  'idtb_base'     => 1,
                  'birth'         => '1989-12-06',
                  'email'         => 'mirahy@admin'. $index .'.com.br',
                  'password'      =>  env("PASSWORD_HASH") ? bcrypt('12345') : '12345',
                  'status'        => '1',
                  'permission'    => '2'
              ]);
          }
        


      /*TbBase::create([
        'name'       => 'Taruma',
        'descripion' => 'Base taruma'

      ]);

      TbProfile::create([
        'name'        => 'Desenvolvedor',
        'description' => 'Perfil DEV_OP'

      ]);*/
}
    
  

