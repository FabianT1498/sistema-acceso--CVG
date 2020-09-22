<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id');
	       $table->string('dni')->nullable();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('condition', 1)->default('C')->comment('Este campo puede tener dos valores C=Cliente o P=Proveedor');
            $table->softDeletes(); //Nueva línea, para el borrado lógico
            $table->timestamps();
            $table->unique(array('dni', 'condition'));
            $table->unique(array('name', 'condition'));
        });
        // Add the constraint
        //DB::statement('ALTER TABLE companies ADD CONSTRAINT chk_condicion CHECK (condition::TEXT=\'C\'::TEXT OR condition::TEXT=\'P\'::TEXT);');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
