<?php

namespace App\Services;

use App\Http\Controllers\ConnectDbController;
use App\Entities\TbBase;


class ReplicaDbService
{
    private $ConnectDbController;
    private $bases;

    public function __construct(ConnectDbController $ConnectDbController)
    {

        $this->ConnectDbController    = $ConnectDbController;
        //recupera o nome das bases
        $this->bases = TbBase::query()->select('sigla')->get();
    }

    public function create($data, $repository)
    {

        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // registra no banco de dados das filiais
                $repository->create($data);
            }
        }
    }

    public function update($data, $id, $repository)
    {
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // atualiza no banco de dados das filiais
                $repository->update($data, $id);
            }
        }
    }

    public function delete($id, $repository)
    {
        foreach ($this->bases as $base) {
            $base = $base['sigla'];
            if ($base != 'adb_mtz') {
                //connecta banco
                $this->ConnectDbController->connectBases($base);
                // deleta no banco de dados das filiais
                $repository->delete($id);
            }
        }
    }
}
