<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id('id_paciente');
            $table->string('codigo', 50)->unique();
            $table->string('nombre', 100);
            $table->string('apellidos', 100);
            $table->string('cedula', 50);
            $table->date('fecha_nacimiento')->nullable();
            $table->string('eps', 100)->nullable();
            $table->enum('sexo', ['m', 'f']);
            $table->timestamp('fecha_creacion')->useCurrent();
        });
    }
    public function down() { Schema::dropIfExists('pacientes'); }
};