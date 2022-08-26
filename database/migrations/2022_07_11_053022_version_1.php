<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Version1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_data', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('name');
            $table->string('nip');
            $table->string('grade');
            $table->string('pos');
        });
        Schema::create('location', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('name');
        });
        Schema::create('room', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('name');
        });
        Schema::create('infra_type', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('name');
        });
        Schema::create('supervisor', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('name');
            $table->string('nip');
            $table->string('pos');
            $table->boolean('is_preference');
        });
        Schema::create('unsur', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('code');
            $table->string('name');
        });
        Schema::create('subunsur', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('code');
            $table->string('name');
            $table->foreignId('unsur_id')->constrained('unsur');
        });
        Schema::create('butir_kegiatan', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->string('code');
            $table->string('name');
            $table->decimal('credit', 8, 4);
            $table->foreignId('subunsur_id')->constrained('subunsur');
        });

        Schema::create('IIB12', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('user_data_id')->constrained('user_data');
            $table->foreignId('location_id')->constrained('location');
            $table->foreignId('butir_kegiatan_id')->constrained('butir_kegiatan');
            $table->foreignId('supervisor_id')->constrained('supervisor');
            //specific attribute
            $table->text('title');
            $table->date('time');
            $table->foreignId('room_id')->constrained('room');
            $table->string('infra_name');
            $table->foreignId('infra_type_id')->constrained('infra_type');
            $table->string('infra_func');
            $table->enum('type', ['detect', 'fix']);
            $table->text('background')->nullable();
            $table->text('problem_ident')->nullable();
            $table->text('problem_analysis')->nullable();
            $table->text('result_ident')->nullable();
            $table->text('solution')->nullable();
            $table->text('action')->nullable();
            $table->text('documentation')->nullable();
            $table->text('approval_letter')->nullable();
            $table->string('requester');
            $table->text('problem_summary');
            $table->timestamps();
        });

        Schema::create('IIB9', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('user_data_id')->constrained('user_data');
            $table->foreignId('location_id')->constrained('location');
            $table->foreignId('butir_kegiatan_id')->constrained('butir_kegiatan');
            $table->foreignId('supervisor_id')->constrained('supervisor');
            //specific attribute
            $table->text('title');
            $table->date('time');
            $table->foreignId('room_id')->constrained('room');
            $table->string('infra_name');
            $table->foreignId('infra_type_id')->constrained('infra_type');
            $table->string('infra_func');
            $table->text('background');
            $table->text('step');
            $table->text('summary');
            $table->text('documentation')->nullable();
            $table->text('approval_letter')->nullable();
            $table->string('requester');
            $table->timestamps();
        });

        Schema::create('IIB8', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('user_data_id')->constrained('user_data');
            $table->foreignId('location_id')->constrained('location');
            $table->foreignId('butir_kegiatan_id')->constrained('butir_kegiatan');
            $table->foreignId('supervisor_id')->constrained('supervisor');
            //specific attribute
            $table->text('title');
            $table->date('time');
            $table->foreignId('room_id')->constrained('room');
            $table->text('step');
            $table->text('result');
            $table->text('summary');
            $table->text('documentation')->nullable();
            $table->text('approval_letter')->nullable();
            $table->text('maintenance_summary');
            $table->string('requester');
            $table->timestamps();
        });

        Schema::create('IIB8_infra', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('IIB8_id')->constrained('IIB8')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('infra_type_id')->constrained('infra_type');
            $table->string('infra_name');
            $table->timestamps();
        });

        Schema::create('IC39', function (Blueprint $table) {
            $table->id()->autoincrement();
            $table->foreignId('user_data_id')->constrained('user_data');
            $table->foreignId('location_id')->constrained('location');
            $table->foreignId('butir_kegiatan_id')->constrained('butir_kegiatan');
            $table->foreignId('supervisor_id')->constrained('supervisor');
            //specific attribute
            $table->text('title');
            $table->date('time');
            $table->string('dataset');
            $table->text('storage');
            $table->text('filename');
            $table->text('documentation')->nullable();
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
        Schema::dropIfExists('user_data');
        Schema::dropIfExists('location');
    }
}
