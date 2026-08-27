<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * A migration original (2021_03_29_000005_create_tb_caixa_table) já cria
 * `deleted_at` via softDeletes(), mas as bases das filiais (adb_vla/adb_sed)
 * em produção ficaram sem essa coluna — provavelmente nunca rodaram essa
 * migration de fato, mesmo com o migrations/migration marcado como já
 * executado ali. Isso ficou invisível até `TbCaixa` ganhar `use SoftDeletes;`
 * (Fase E.0.5), que passou a filtrar por `deleted_at` em toda consulta.
 *
 * Corrige de forma defensiva, sem depender de refazer o histórico de
 * migrations em cada conexão: adiciona a coluna só onde estiver faltando.
 */
class AddDeletedAtToTbCaixaTableIfMissing extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('tb_caixa', 'deleted_at')) {
            Schema::table('tb_caixa', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        // Não reverte — a coluna já é esperada pela migration original
        // (2021_03_29_000005_create_tb_caixa_table); isso só corrige bases
        // onde ela nunca chegou a existir.
    }
}
