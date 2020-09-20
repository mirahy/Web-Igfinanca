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
                throw new Exception("Email inválido");

              if ($user && Auth::attempt($data, $remember))
                throw new Exception("Senha inválida");

              Auth::attempt($data, $remember);
        

            }
            else
            {
              $user = $this->repository->FindWhere(['email' => $request->get('email')])->first();

              if(!$user)
              return [
                    'success'     => false,
                    'messages'    => ["Email e/ou senha inválidos"],
                    'data'        => $user,
                    'type'        => ["email","password"],                 
                  ];

              if($user->password != $request->get('password'))
                return [
                    'success'     => false,
                    'messages'    => ["Email e/ou senha inválidos"],
                    'data'        => $user,
                    'type'        => ["email","password"],  
                  ];
                  
                  $user = Auth::login($user);
                  

            }

                return [
                  'success'     => true,
                  'messages'    => ['Ok'],
                  'data'        => $user,
                  'type'        => [null],  
                ];

        }
        catch (Exception $e)
        {
          switch (get_class($e)) {
            case QueryException::class      : return['success' => false, 'messages' => 'Preencher campos!', 'data'   => '', 'type' => ''];
            case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag()->all(), 'type'  => $e->getMessageBag()->keys(), 'data'   => ''];
            case Exception::class           : return['success' => false, 'messages' => 'Preencher campos!', 'data'   => '', 'type' => ''];
            default                         : return['success' => false, 'messages' => 'Preencher campos!'];
          }
        }


  }

}
