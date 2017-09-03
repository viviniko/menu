<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * @var string
     */
    protected $menusTable;

    /**
     * @var string
     */
    protected $menuItemsTable;

    /**
     * CreateMenuTable constructor.
     */
    public function __construct()
    {
        $this->menusTable = Config::get('menu.menus_table');
        $this->menuItemsTable = Config::get('menu.menu_items_table');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->menusTable, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('display_name')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create($this->menuItemsTable, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('menu_id');
            $table->unsignedInteger('permission_id')->nullable();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('url')->nullable();
            $table->string('target')->nullable();
            $table->string('icon_class')->nullable();
            $table->string('color')->nullable();
            $table->integer('sort');
            $table->timestamps();

            // $table->foreign('menu_id')->references('id')->on($this->menusTable)
            //     ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->menuItemsTable);
        Schema::dropIfExists($this->menusTable);
    }
}
