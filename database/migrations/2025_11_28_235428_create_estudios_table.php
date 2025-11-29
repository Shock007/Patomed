<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('estudios', function (Blueprint $table) {
            $table->id('id_estudio');
            $table->foreignId('id_paciente')->constrained('pacientes', 'id_paciente')->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->string('codigo_estudio', 50)->unique();
            $table->timestamp('fecha')->useCurrent();
            $table->text('descripcion_macro')->nullable();
            $table->text('descripcion_micro')->nullable();
            $table->text('diagnostico')->nullable();
            $table->boolean('resultado');
            $table->tinyInteger('estado')->default(0);
            $table->timestamp('fecha_registro')->useCurrent();
        });
    }
    public function down() { Schema::dropIfExists('estudios'); }
};