<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRkaklTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rkakl', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('tahun_anggaran', 15);
            $table->string('aktivasi', 2)->nullable();
            $table->string('status_revisi', 2)->nullable()->comment('1 = baru, 2 = revisi');
            $table->text('keterangan')->nullable();
            // $table->string('unit_kerja', 50);
            $table->date('tgl');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rkakl');
    }
}
