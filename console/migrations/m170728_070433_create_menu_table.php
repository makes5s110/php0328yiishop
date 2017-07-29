<?php

use yii\db\Migration;

/**
 * Handles the creation of table `menu`.
 */
class m170728_070433_create_menu_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('menu', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('菜单名称'),
            'parent_id'=>$this->integer()->comment('父ID'),
            'sort'=>$this->integer()->comment('排序'),
            'url'=>$this->string(50)->comment('url地址'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('menu');
    }
}
