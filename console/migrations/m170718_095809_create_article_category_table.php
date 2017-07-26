<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article_category`.
 */
class m170718_095809_create_article_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article_category', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(50),
            'intro'=>$this->text(),
            'sort'=>$this->integer(11),
            'status'=>$this->integer(2)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article_category');
    }
}
