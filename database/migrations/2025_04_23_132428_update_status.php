<?php

use App\Models\Master\Status;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    static $table;

    function __construct(){
        self::$table =  (new Status())->getTable();
    }


    public function up(): void
    {
        Schema::table(static::$table, function(Blueprint $table){

            if(Schema::hasColumn(table: static::$table, column: 'created_by')){
                $table->dropForeign(index: ['created_by']);
                $table->dropColumn(columns: 'created_by');
            }
            $table->foreignId(column: 'created_by')->nullable()->constrained(table: 'users')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(static::$table, function(Blueprint $table){
            $table->foreignId(column: 'created_by')->constrained(table: 'users')->change();
        });
    }
};
