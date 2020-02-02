<?php

use yii\db\Migration;

/**
 * Class m200202_092527_alter_table_tag_add_foreign_keys
 */
class m200202_092527_alter_table_tag_add_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-tag-user','tag','user_id','user','id','SET NULL');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-tag-user','tag');
    }

}
