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


class LoginService
{

  Private $repository;
  Private $validator;
  Private $reCaptcha;

  public function __construct(ReCaptcha $reCaptcha, LoginValidator $validator, TbCadUserRepository $repository)
  {
      $this->reCaptcha   = $reCaptcha->verify("KEY_SECRET_RECAPTCHA");
      $this->validator   = $validator;
      $this->repository  = $repository;

  }




  public function auth(Request $request)
  {
      $data = $request->all();

      
      //recuperando sigla, name e id selecionada no login
      $base    = TbBase::Where('id', $data['base'])->get('sigla')->ToArray();
      $db      =   strtoupper(substr($base[0]['sigla'],-3));
      $id_base = $data['base'];

      //dd($db);

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

              if(!$user)
              return [
                'success'     => false,
                'messages'    => ["Email e/ou senha inválidos"],
                'data'        => $user,
                'type'        => ["email","password"],
                'base'        => $base, 
                'db'          => $db,
                'id_base'     => $id_base,       
              ];

              if (!Auth::attempt($data, $remember))
              return [
                'success'     => false,
                'messages'    => ["Email e/ou senha inválidos"],
                'data'        => $user,
                'type'        => ["email","password"],
                'base'        => $base, 
                'db'          => $db,
                'id_base'     => $id_base,                 
              ];

              Auth::attempt($data, $remember);

            }else{
              $user = $this->repository->FindWhere(['email' => $request->get('email')])->first();

              if(!$user)
              return [
                    'success'     => false,
                    'messages'    => ["Email e/ou senha inválidos"],
                    'data'        => $user,
                    'type'        => ["email","password"],
                    'base'        => $base, 
                    'db'          => $db,
                    'id_base'     => $id_base, 
                                     
                  ];

              if($user->password != $request->get('password'))
                return [
                    'success'     => false,
                    'messages'    => ["Email e/ou senha inválidos"],
                    'data'        => $user,
                    'type'        => ["email","password"],
                    'base'        => $base, 
                    'db'          => $db,
                    'id_base'     => $id_base,  
                  ];
                  
                  
                  Auth::login($user);

            }


                return [
                  'success'     => true,
                  'messages'    => ['Ok'],
                  'data'        => $user,
                  'type'        => [null],
                  'base'        => $base, 
                  'db'          => $db,
                  'id_base'     => $id_base, 
                ];

        }
        catch (Exception $e)
        {
          switch (get_class($e)) {
            case QueryException::class      : return['success' => false, 'messages' => 'Preencher campos!', 'data'   => '', 'type' => '', 'base' => '', 'db' => '', 'id_base' => ''];
            case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys(), 'data'   => '', 'base' => '', 'db' => '', 'id_base' => ''];
            case Exception::class           : return['success' => false, 'messages' => 'Preencher campos!', 'data'   => '', 'type' => '', 'base' => '', 'db' => '', 'id_base' => ''];
            default                         : return['success' => false, 'messages' => 'Preencher campos!', 'data'   => '', 'type' => '', 'base' => '', 'db' => '', 'id_base' => ''];
          }
        }


  }

}
