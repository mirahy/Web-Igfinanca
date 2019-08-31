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
use Auth;
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
              'email'      => $request->get('usuario'),
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
                    'messages'    => false,
                    'data'        => $user,
                    'error'        => "Email ou senha inválidos",
                  ];

              if($user->password != $request->get('password'))
                return [
                    'success'     => false,
                    'messages'    => false,
                    'data'        => $user,
                    'error'        => "Email ou senha inválidos",
                  ];

              Auth::login($user, $remember);
              

            }

            

                return [
                  'success'     => true,
                  'messages'    => 'Ok',
                  'data'        => $user,
                  'error'        => null,
                ];

        }
        catch (Exception $e)
        {
          switch (get_class($e)) {
            case QueryException::class      : return['success' => false, 'messages' => 'Preencher campos!', 'error'  => $e->getMessage()];
            case ValidatorException::class  : return['success' => false, 'messages' => $e->getMessageBag(), 'error'  => 'Preencha o captcha!'];
            case Exception::class           : return['success' => false, 'messages' => 'Preencher campos!', 'error'  => $e->getMessage()];
            default                         : return['success' => false, 'messages' => 'Preencher campos!', 'error'  => $e->getMessage()];
          }
        }


  }

}
