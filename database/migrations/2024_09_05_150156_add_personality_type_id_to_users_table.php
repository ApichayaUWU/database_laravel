<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add foreign key to the personality_types table
            $table->unsignedBigInteger('personality_type_id')->nullable();
            $table->foreign('personality_type_id')
                  ->references('id')
                  ->on('personality_types')
                  ->onDelete('set null'); // Use 'set null' to allow users to have no personality type if it's deleted
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['personality_type_id']);
            $table->dropColumn('personality_type_id');
        });
    }
};
