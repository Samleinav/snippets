<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('snippets')) {
            Schema::create('snippets', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255);
                $table->string('description', 400)->nullable();
                $table->longText('code')->nullable();
                $table->string('target', 60)->default('global'); // global, admin, frontend, api
                $table->string('route_rules', 400)->nullable();
                $table->string('status', 60)->default('published');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('snippets');
    }
};
