<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMainTable extends Migration
{
    #配送方式
    private $DELIVER_KIND = ['self', 'me', 'third'];
    #支付方式
    private $PAY_KIND = ['wx', 'ali', 'bd', 'upay', 'cash'];
    #订单状态
    private $ORDER_STATUS = ['create', 'paying', 'receive', 'delivery', 'complete', 'cancel'];
    #交易类型
    private $DEAL_KIND = ['recharge', 'consume', 'third_pay', 'pay_fail', 'deposit'];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //红包
        Schema::create('coupons', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->string('name');
            $table->string('desc')->nullable();
            $table->dateTime('start_time');     //有效期:开始时间
            $table->dateTime('end_time');       //有效期:结束时间
            $table->integer('mny');
            $table->tinyInteger('type');
            $table->boolean('used');     //是否已经使用

            //索引
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        //地址信息
        Schema::create('addresses', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->string('name');       //姓名
            $table->tinyInteger('sex');
            $table->string('location');   //位置
            $table->string('address');    //详细地址
            $table->string('mobile');     //手机号

            //索引
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        //店铺分类信息
        Schema::create('shop_categories', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();  //父分类
            $table->string('name');
            $table->timestamps();

            //索引
            $table->foreign('parent_id')
                ->references('id')
                ->on('shop_categories')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        //店铺信息
        Schema::create('shops', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->string('name');
            $table->string('desc', 1000)->nullable();

            $table->double('location_lat')->nullable();
            $table->double('location_lng')->nullable();
            $table->string('location_address', 1000)->nullable();

            $table->string('work_time')->nullable();    //营业时间
            $table->integer('min_price');               //起送价
            $table->integer('charge');                  //配送费
            $table->string('notice', 1000)->nullable(); //商家公告
            $table->string('phone');                    //联系电话
            $table->enum('delivery_kind', $this->DELIVER_KIND);  //配送方式
            $table->boolean('support_cod');             //是否支持货到付款

            //索引
            $table->index('name');
        });

        //店铺图片
        Schema::create('shop_images', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('shop_id');
            $table->string('desc')->nullable();
            $table->string('url');

            //索引
            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        //店铺-分类交叉表
        Schema::create('shop_category_crosses', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('shop_id');
            $table->unsignedInteger('shop_category_id');

            //索引
            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('shop_category_id')
                ->references('id')
                ->on('shop_categories')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        //商品分类信息
        Schema::create('product_categories', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('shop_id');
            $table->string('name');

            //索引
            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        //商品信息
        Schema::create('products', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('shop_id');
            $table->unsignedInteger('product_category_id');
            $table->string('name');
            $table->string('desc')->nullable();
            $table->integer('price');
            $table->string('image')->nullable();

            //索引
            $table->index('name');
            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('product_category_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        //订单头信息
        Schema::create('orders', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('shop_id');
            $table->unsignedInteger('coupon_id')->nullable();
            $table->string('order_no')->unique();
            $table->dateTime('order_time');
            $table->enum('delivery_kind', $this->DELIVER_KIND);
            $table->enum('status', $this->ORDER_STATUS);  //订单状态
            $table->string('cancel_reason')->nullable(); //订单取消原因
            $table->string('delivery_time');   //送出时间
            $table->enum('pay_kind', $this->PAY_KIND);   //支付方式
            $table->string('memo')->nullable();         //订单备注
            $table->string('invoice')->nullable();      //发票信息

            $table->string('link_man');     //联系人
            $table->string('link_mobile');  //联系电话
            $table->string('link_address'); //收货地址

            $table->integer('total_mny');
            $table->integer('pay_mny');
            $table->integer('delivery_charge');  //配送费
            $table->integer('coupon_mny');       //优惠金额

            //索引
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('coupon_id')
                ->references('id')
                ->on('coupons')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        //订单行信息
        Schema::create('order_lines', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('product_id');

            $table->unsignedInteger('number');
            $table->integer('price');
            $table->integer('mny');

            //索引
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('no action')
                ->onUpdate('no action');
        });

        //订单执行流程
        Schema::create('order_procedure', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('order_id');
            $table->enum('status', $this->ORDER_STATUS);
            $table->dateTime('time');
            $table->string('desc')->nullable();

            //索引
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        //评论信息
        Schema::create('comments', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->datetime('time');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('shop_id');
            $table->unsignedInteger('user_id');

            $table->unsignedTinyInteger('score');
            $table->text('comment')->nullable();

            //索引
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('shop_id')
                ->references('id')
                ->on('shops')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        //评论-商品
        Schema::create('comment_products', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('comment_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('user_id');

            $table->unsignedTinyInteger('score');
            $table->text('comment')->nullable();

            //索引
            $table->foreign('comment_id')
                ->references('id')
                ->on('comments')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('no action')
                ->onUpdate('no action');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

        //账户明细信息
        Schema::create('accounts', function(Blueprint $table){
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('user_id');
            $table->datetime('time');
            $table->enum('deal_kind', $this->DEAL_KIND);  //交易类型
            $table->integer('mny');
            $table->integer('balance');

            //索引
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('coupons');
        Schema::drop('addresses');
        Schema::drop('shop_categories');
        Schema::drop('shops');
        Schema::drop('shop_category_crosses');
        Schema::drop('product_categories');
        Schema::drop('products');
        Schema::drop('orders');
        Schema::drop('order_lines');
        Schema::drop('order_procedure');
        Schema::drop('comments');
        Schema::drop('comment_products');
        Schema::drop('accounts');
    }
}
