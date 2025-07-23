<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mouse_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(table: 'users')->onDelete('cascade');
            $table->string('mouse_speed');
            $table->string('max_speed');
            $table->string('max_positive_acc');
            $table->string('max_negative_acc');
            $table->string('total_x_distance');
            $table->string('total_y_distance');
            $table->string('total_distance');
            $table->string('left_click_count');
            $table->string('right_click_count');
            $table->timestamps();
        });

        Schema::create('keyboard_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(table: 'users')->onDelete('cascade');
            $table->string('email_typing_time');
            $table->string('password_typing_time');
            $table->string('shift_count');
            $table->string('caps_lock_count');
            $table->string('average_dwell_time');
            $table->string('average_flight_duration');
            $table->string('average_up_down_time');
            $table->timestamps();
        });
        
        Schema::create('context_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(table: 'users')->onDelete('cascade');
            $table->string('browser_name');
            $table->string('browser_version');
            $table->string('user_agent');
            $table->string('color_depth');
            $table->string('canvas_fingerprint');
            $table->string('os');
            $table->string('cpu_class');
            $table->string('resolution');
            $table->timestamps();
        });
        
        Schema::create('location_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(table: 'users')->onDelete('cascade');
            $table->string('ip');
            $table->string('country_name');
            $table->string('country_code');
            $table->string('region');
            $table->string('city');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_related');
    }
};
