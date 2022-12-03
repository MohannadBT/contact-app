<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {

            /* Old way */
            // $table->unsignedBigInteger('user_id')->after('id')->nullable();
            // $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade'); // u can put ('set null')
            
            /* New way */
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnUpdate(); 
            // constrained('taple_name'), u can use nullable or after methods but u need to put them before the constrained 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tasks', function (Blueprint $table) {
            // $table->dropForeign('tasks_user_id_foreign');
            $table->dropForeign(['user_id']); // same as above but this one we specify the column name
            $table->dropColumn('user_id');
        });
    }
};
