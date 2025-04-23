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
             // Check if the column exists
             if (Schema::hasColumn('statuses', 'created_by')) {
                // Drop the foreign key constraint if it exists
                $table->dropForeign(['created_by']);
                // Drop the column
                $table->dropColumn('created_by');
            }

            // Re-add the column as nullable and add the foreign key constraint
            $table->foreignId('created_by')->nullable()->constrained('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statuses', function (Blueprint $table) {
            // Drop the foreign key constraint if it exists
            $table->dropForeign(['created_by']);
            // Drop the column
            $table->dropColumn('created_by');
        });
    }
};
