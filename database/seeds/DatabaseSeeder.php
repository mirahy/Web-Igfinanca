<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Entities\TbCadUser;
use App\Entities\TbBase;
use App\Entities\TbProfile;
use App\Entities\TbCaixa;
use App\Entities\TbPaymentType;
use App\Entities\TbOperation;
use App\Entities\TbTypeLaunc;
use App\Entities\TbClosing;
use DB;

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
          $this->createInitialData();
          // Cria usuários demo (dados faker)
          //$this->createUsers();  

        }
  


         private  function createInitialData()
        {     
              
              TbBase::create([
                'name'       => 'Vila Alta',
                'sigla'      => 'adb_vla',
                'descripion' => 'Base Vila Alta'
              ]);

              TbBase::create([
                'name'       => 'Matriz',
                'sigla'      => 'adb_mtz',
                'descripion' => 'Base Matriz'
              ]);

              TbCaixa::create(
                ['name'       => 'Dízimo',
                'description' => 'Dízimo'
              ]);
                
               TbCaixa::create(
               ['name'       => 'Oferta',
               'description' => 'Oferta'
              ]);
      
              TbProfile::create([
                'name'        => 'Desenvolvedor',
                'description' => 'Perfil DEV_OP'
              ]);

              TbProfile::create([
                'name'        => 'Básico',
                'description' => 'Usuario acesso basico'     
              ]);

              TbPaymentType::create([
                  'name'        => 'Dinheiro',
                  'descripion' => ''
              ]);

              TbPaymentType::create([
                'name'        => 'Cartão Debito',
                'descripion' => ''
              ]);
              
              TbPaymentType::create([
                'name'        => 'Cheque',
                'descripion' => ''
              ]);
              
              TbPaymentType::create([
                'name'        => 'PIX',
                'descripion' => ''
              ]);

              TbPaymentType::create([
                'name'        => 'DOC',
                'descripion' => ''
              ]);

              TbPaymentType::create([
                'name'        => 'TED',
                'descripion' => ''
              ]);

              TbPaymentType::create([
                'name'        => 'Cartão Crédito',
                'descripion' => ''
              ]);

              TbOperation::create([
                'name'        => 'Entrada',
                'descripion' => ''
              ]);

              TbOperation::create([
                'name'        => 'Saída',
                'descripion' => ''
              ]);

              TbTypeLaunc::create([
                'name'        => 'Dízimo',
                'descripion' => 'Dízimo'
              ]);
              
              TbTypeLaunc::create([
                'name'        => 'Oferta',
                'descripion' => 'Oferta'
              ]);

              TbTypeLaunc::create([
                'name'        => 'Compra',
                'descripion' => 'Compra'
              ]);

              TbTypeLaunc::create([
                'name'        => 'Serviço',
                'descripion' => 'Serviço'
              ]);

              TbTypeLaunc::create([
                'name'        => 'Entrada',
                'descripion' => 'Entradas gerais'
              ]);

              TbClosing::insert([
                'month'  => 'Janeiro',
                'year'   => date('Y'),
                'status' => 1
              ]);

              TbCadUser::create([
                'name'          => 'Admin',
                'idtb_profile'  => 1,
                'idtb_base'     => 1,
                'birth'         => '1900-01-01',
                'email'         => 'admin@vla.com.br',
                'password'      =>  env("PASSWORD_HASH") ? bcrypt('adbvla123') : 'adbvla123',
                'status'        => '1',
                'permission'    => '2'
              ]);

              TbCadUser::create([
                'name'         => 'Oferta Local',
                'idtb_profile'  => 1,
                'idtb_base'     => 1,
                'birth'         => '1900-01-01',
                'email'         => '',
                'password'      =>  '',
                'status'        => '1',
                'permission'    => '2' 
              ]);
              $this->command->info('Admin admin@vla.com.br user created, password adbvla123');

              $path = 'database/seeds/permissoes.sql';
              DB::unprepared(file_get_contents($path));
              $this->command->info('Roles and permissions by user created in Data base!');
        }

          // private function createUsers()
          // {
          //     $max = rand(10, 30);
          //     for($i=0; $i < $max; $i++):
          //         $this->createUser($i+$max);
          //     endfor;
          //     $this->command->info($max . ' demo users created');
          // }

          // private function createUser($index)
          // {   
          //     return TbCadUser::create([
          //         'name'          => 'Mirahy Branco Fonseca'. $index,
          //         'idtb_profile'  => 1,
          //         'idtb_base'     => 1,
          //         'birth'         => '1989-12-06',
          //         'email'         => 'mirahy@admin'. $index .'.com.br',
          //         'password'      =>  env("PASSWORD_HASH") ? bcrypt('12345') : '12345',
          //         'status'        => '1',
          //         'permission'    => '2'
          //     ]);
          // }
    
}   
