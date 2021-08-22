<?php

namespace App\Services;

use Exception;
use App\Entities\TbCadUser;
use App\Validators\TbCadUserValidator;
use App\Repositories\TbCadUserRepository;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\QueryException;
use Yajra\Datatables\Datatables;
use DB;
class TbCadUserService
{

      private $repository;
      private $validator;

      public function __construct(TbCadUserRepository $repository, TbCadUserValidator $validator)
      {
          $this->repository   = $repository;
          $this->validator    = $validator;

      }

      //função cadastar usuário
      public function store($data)
      {
        try {

              if($data['password'] == null && $data['password_confirmation']  == null ){

                $data['password'] = env('PATTERN_PASS');
                $data['password_confirmation'] =env('PATTERN_PASS');
              }

              
               // validando campos
              $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
              
              // validando campos senhas
              $v = $this->validator->validaInputsPass($data);
              if(!$v['success']){
                return $v;
              }
              //hash password?
              $data['password'] = env("PASSWORD_HASH") ? bcrypt($data['password']) : $data['password'];

              $user = $this->repository->create($data);
      
              $name = explode(" ",$user['name']);

              return [
                'success'     => true,
                'messages'    => [$name[0]],
                'data'        => $user,
                'type'        => ["id"],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => $e->getMessage(), 'type'  => ["id"]];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => $e->getMessage()->all(), 'type'  => ["id"]];
                default                         : return['success' => false, 'messages' => $e->getMessage()->all(), 'type'  => ["id"]];
              }

        }

      }

      //função atualizar usuário
      public function update($data)
      {
        try {

              $id = $data['id'];

              $this->validator->with($data)->setId($id)->passesOrFail(ValidatorInterface::RULE_UPDATE);
              $user = $this->repository->update($data, $id);

              $name = explode(" ",$user['name']);

              return [
                'success'     => true,
                'messages'    => [$name[0]],
                'data'        => $user,
                'type'        => [""],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
                default                         : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
              }

        }
      }

      //função deletar usuário
      public function delete($id)
      {

        try {

              $user = TbCadUserService::find_Id($id);
            
              $name = explode(" ",$user['data']['name']);
              
              $this->repository->delete($id);
 
              return [
                'success'     => true,
                'messages'    => [$name[0]],
                'data'        => $user,
                'type'        => [""],
              ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys()];
                case Exception::class           : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
                default                         : return['success' => false, 'messages' => 'Não foi possivel cadastar o usuário!', 'type'  => $e->getMessage()];
              }

        }
            
      }

      //retorna usuario pelo id
      public function find_Id($id)
      {

        try {

         $user = $this->repository->find($id)->toArray();

          return [
            'success'     => true,
            'messages'    => null,
            'data'        => $user,
            'type'        => null,
          ];


        } catch (Exception $e) {

              switch (get_class($e)) {               
                case QueryException::class      : return['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
                case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => null];
                case Exception::class           : return['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
                default                         : return['success' => false, 'messages' => $e->getMessage(), 'type'  => null];
              }

        }

      }

      //retorna nomes dos usuários apartir do textos digitados nos formulário
      public function find_Autocomplete($term)
      {

        $data =  TbCadUser::where([['name', 'LIKE', '%' . $term. '%'],['status','=','1'],['deleted_at','=', null]])->get();
        
        return  $data;
      }
      //retorna usuários para uso na tabelas do framework datatables
      public function find_DataTables($request)
      {

          $def = '%';

          return Datatables::of(TbCadUser::query()
          ->with('base')
          ->with('Profile')
          ->where([['status', 'LIKE', $request->query('status', $def)]]))
          ->blacklist(['action'])
          ->make(true);
      }

       //retorna usuário pelo nome
       public function find_User_name($name)
       {
         
         $data =  TbCadUser::where('name', 'LIKE',  $name )->get();
         
         return  $data;
       }

}
