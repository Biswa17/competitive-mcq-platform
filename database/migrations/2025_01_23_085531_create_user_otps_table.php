<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserOtpsTable extends Migration
{
    public function up()
    {
        Schema::create('user_otps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->index(); // Foreign key
            $table->string('otp');
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at'); // Expiration time for the OTP
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_otps');
    }
}
