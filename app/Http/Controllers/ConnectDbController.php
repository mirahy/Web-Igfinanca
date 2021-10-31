<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;


class ConnectDbController extends Controller
{


    //conecta a base matriz
    public function connectMatriz()
    {
        Config::set('database.default', 'adb_mtz'); //atribuir a conexão padrão

        // Conecta no banco
        DB::reconnect('adb_mtz');
    }

    //conecta a base especificada na session


    public function connectBase()
    {

        if (session()->has('base')) //veirifica se o item base existe e não é null
        {
            $base = session()->get('base');
            $base = $base[0]['sigla'];

            if (DB::connection()->getDatabaseName() != $base) //verifica se a conexão ja existe
            {

                Config::set('database.default', $base); //atribuir a conexão padrão  

                // Conecta no banco
                DB::reconnect($base);
            }
        }
    }

    //recebe a uma base como parametro para conexão
    public function connectBases($base)
    {

        if ($base) //veirifica se o item não é null
        {

            if (DB::connection()->getDatabaseName() != $base) //verifica se a conexão ja existe
            {

                Config::set('database.default', $base); //atribuir a conexão padrão  

                // Conecta no banco
                DB::reconnect($base);
            }
        }
    }

}
