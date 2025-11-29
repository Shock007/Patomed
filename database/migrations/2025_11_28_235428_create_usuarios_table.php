<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('usuario', 100)->unique();
            $table->string('password_hash', 255);
            $table->timestamp('creado_en')->useCurrent();
        });
    }
    public function down() { Schema::dropIfExists('usuarios'); }
};