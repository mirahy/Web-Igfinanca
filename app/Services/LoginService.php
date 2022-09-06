<?php

namespace App\Services;

use Prettus\Validator\Contracts\ValidatorInterface;
use App\Validators\LoginValidator;
use App\Validators\ReCaptcha;
use Illuminate\Http\Request;
use App\Repositories\TbCadUserRepository;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Database\QueryException;
use Exception;
use Illuminate\Support\Facades\Auth;
use Hash;
use App\Entities\TbBase;
use DB;
use Schema;
use App\Http\Controllers\ConnectDbController;


class LoginService
{

  Private $repository;
  Private $validator;
  Private $reCaptcha;
  private $ConnectDbController;

  public function __construct(ReCaptcha $reCaptcha,
                              LoginValidator $validator,
                              TbCadUserRepository $repository,
                              ConnectDbController $ConnectDbController)
  {
      $this->reCaptcha   = $reCaptcha->verify("KEY_SECRET_RECAPTCHA");
      $this->validator   = $validator;
      $this->repository  = $repository;
      $this->ConnectDbController  = $ConnectDbController;

  }




  public function auth(Request $request)
  {

    
      $data = $request->all();

      
      //recuperando sigla, name e id selecionada no login
      $base    = TbBase::Where('id', $data['base'])->get()->ToArray();
      $db      =   strtoupper(substr($base[0]['sigla'],-3));
      $id_base = $data['base'];
      $name_base = $base[0]['name'];
      $sigla = $base[0]['sigla'];


      try
        {

            //validando campos
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
            //setando email e password em suas variaveis
            
            $data=[
              'email'      => $request->get('email'),
              'password'   => $request->get('password')
            ];
            
            
            // setando o valor de do chekc lembre-me
            if($request->get('customCheck')){
              $remember     = 'true';

            }else{
              $remember     = 'false';
            }


            
            //estrutura do login
            if( env("PASSWORD_HASH"))
            {
              $user = $this->repository->FindWhere(['email' => $request->get('email')])->first();
              
              if(!$user || !Hash::check($request->get('password'), $user->password))
              return [
                'success'     => false,
                'messages'    => ["Email e/ou senha inválidos"],
                'data'        => $user,
                'type'        => ["email","password"],
                'base'        => $base, 
                'db'          => $db,
                'id_base'     => $id_base,
                'name_base'   => $name_base,     
              ];

              Auth::attempt($data, $remember);

            }else{
              $user = $this->repository->FindWhere(['email' => $request->get('email')])->first();
              
              if(!$user || $user->password != $request->get('password'))
              return [
                    'success'     => false,
                    'messages'    => ["Email e/ou senha inválidos"],
                    'data'        => $user,
                    'type'        => ["email","password"],
                    'base'        => $base, 
                    'db'          => $db,
                    'id_base'     => $id_base,
                    'name_base'   => $name_base, 
                                     
                  ];
                  
                  Auth::login($user);

            }

            $user = $this->repository->FindWhere(['email' => $request->get('email')])->first();
            

                return [
                  'success'     => true,
                  'messages'    => ['Ok'],
                  'data'        => $user,
                  'type'        => [null],
                  'base'        => $base, 
                  'db'          => $db,
                  'id_base'     => $id_base,
                  'name_base'   => $name_base,
                ];

        }
        catch (Exception $e)
        {
          switch (get_class($e)) {
            case QueryException::class      : return['success' => false, 'messages' => 'Preencher campos!', 'data'   => '', 'type' => '', 'base' => '', 'db' => '', 'id_base' => '', 'name_base'   => ''];
            case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys(), 'data'   => '', 'base' => '', 'db' => '', 'id_base' => '', 'name_base'   => ''];
            case Exception::class           : return['success' => false, 'messages' => 'Preencher campos!', 'data'   => '', 'type' => '', 'base' => '', 'db' => '', 'id_base' => '', 'name_base'   => ''];
            default                         : return['success' => false, 'messages' => 'Preencher campos!', 'data'   => '', 'type' => '', 'base' => '', 'db' => '', 'id_base' => '', 'name_base'   => ''];
          }
        }


  }

}
