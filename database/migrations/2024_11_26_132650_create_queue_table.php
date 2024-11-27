<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id(); // ID único para cada entrada na fila
            $table->string('patient_name'); // Nome do paciente
            $table->integer('priority')->default(50); // Prioridade (ex: baixa, normal, alta, emergência)
            $table->enum('nivel', ['baixo','medio','alto','emergencia'])->default('baixo'); // Status na fila
            $table->enum('status', ['waiting', 'in_progress', 'completed', 'cancelled'])->default('waiting'); // Status na fila
            $table->timestamp('arrival_time')->useCurrent(); // Horário de chegada na fila
            $table->timestamp('start_time')->nullable(); // Horário em que o atendimento começou
            $table->timestamp('end_time')->nullable(); // Horário de finalização do atendimento
            $table->string('notes')->nullable(); // Observações ou informações adicionais
            $table->integer('order')->nullable(); // Observações ou informações adicionais
            $table->timestamps(); // Campos created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
