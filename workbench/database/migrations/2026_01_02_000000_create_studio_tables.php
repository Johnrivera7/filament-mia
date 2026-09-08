<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Two tables, enough to photograph the theme.
 *
 * The preview panel exists to show what the stylesheet does to a populated
 * panel: a table with badges, meters and a summary row, a form with sections,
 * and widgets with charts. That needs records with shape — statuses, dates,
 * money, relationships — rather than many tables.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('industry');
            $table->string('city');
            $table->string('tier')->default('project');
            $table->timestamps();
        });

        Schema::create('projects', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('status')->default('discovery');
            $table->string('health')->default('on_track');
            $table->string('priority')->default('normal');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->unsignedInteger('budget')->default(0);
            $table->unsignedInteger('spent')->default(0);
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->date('delivered_at')->nullable();
            $table->boolean('is_starred')->default(false);
            $table->text('brief')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
        Schema::dropIfExists('clients');
    }
};
