<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $newOption = 'Cancel';
            // Use the DB::statement method to modify the ENUM type.
            DB::statement("ALTER TABLE attendances MODIFY COLUMN status ENUM('Pending','Completed', 'No_Vakta_Added', '$newOption')");
            $table->string('reason', 120);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_status', function (Blueprint $table) {
            //
        });
    }
};
