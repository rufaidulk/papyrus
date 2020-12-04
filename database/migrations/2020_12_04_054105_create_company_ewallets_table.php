<?php

use App\Models\CompanyEwallet;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyEwalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_ewallets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id')->index();
            $table->enum('transaction_type', [CompanyEwallet::TYPE_CREDIT, CompanyEwallet::TYPE_DEBIT]);
            $table->decimal('amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_ewallets');
    }
}
