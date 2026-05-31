
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('montant_total', 10, 2)->default(0);
            $table->enum('statut', ['en_attente', 'confirmee', 'expediee', 'livree'])->default('en_attente');
            $table->string('nom_client');
            $table->string('telephone');
            $table->string('email');
            $table->string('region');
            $table->text('adresse');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
};
