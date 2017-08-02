<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170731_020518_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50)->comment('收货人'),
            'user_id'=>$this->integer()->comment('用户ID'),
            'province'=>$this->string(30)->comment('省'),
            'city'=>$this->string(30)->comment('城市'),
            'area'=>$this->string(30)->comment('区/县'),
            'address'=>$this->string(255)->comment('详细地址'),
            'status'=>$this->string(20)->comment('状态(1为默认地址,0为新增地址)'),
            'tel'=>$this->string(11)->comment('手机号码'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
