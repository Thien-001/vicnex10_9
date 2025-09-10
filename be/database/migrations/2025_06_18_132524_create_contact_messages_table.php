    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        public function up(): void
        {
            Schema::create('contact_messages', function (Blueprint $table) {
                $table->bigIncrements('Contact_ID');
                $table->string('Name');
                $table->string('Email');
                $table->string('Phone')->nullable();
                $table->string('Subject')->nullable();
                $table->text('Message');
                $table->timestamp('Created_at')->nullable();
                $table->timestamp('Updated_at')->nullable();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('contact_messages');
        }
    };
