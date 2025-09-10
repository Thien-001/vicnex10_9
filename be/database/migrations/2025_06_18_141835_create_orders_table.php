    <?php
    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateOrdersTable extends Migration
    {
        public function up(): void
        {
            Schema::create('orders', function (Blueprint $table) {
                $table->id('order_id');

                $table->unsignedBigInteger('user_id');
                $table->string('order_code')->unique();
                $table->string('shipping_address');
                $table->text('note_user')->nullable();
                $table->string('payment_method');
                $table->decimal('shiping_fee', 10, 2)->default(0);
                $table->decimal('total_amount', 10, 2);
                $table->string('status');
                $table->string('status_method')->nullable();

                $table->timestamps(); // create_at + update_at

                $table->unsignedBigInteger('vourchers_id')->nullable();

                $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
                // $table->foreign('vourchers_id')->references('id')->on('vourchers')->nullOnDelete();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('orders');
        }
    }
