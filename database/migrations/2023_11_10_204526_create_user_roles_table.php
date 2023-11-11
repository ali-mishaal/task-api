<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->foreignUuid('user_id')->nullable()->constrained();
            $table->foreignUuid('role_id')->nullable()->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_roles');
    }
};
