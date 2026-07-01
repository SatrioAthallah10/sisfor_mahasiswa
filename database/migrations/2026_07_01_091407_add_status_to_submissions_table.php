<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('notes');
            $table->text('feedback')->nullable()->after('status');
            $table->timestamp('reviewed_at')->nullable()->after('feedback');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn(['status', 'feedback', 'reviewed_at']);
        });
    }
};
