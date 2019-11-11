<?php

use yii\db\Migration;

/**
 * Class m191111_035535_add_column_user_id
 */
class m191111_035535_add_column_user_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('project','user_id',$this->integer()->defaultValue(null));
        $this->addColumn('task','user_id',$this->integer()->defaultValue(null));
        $this->addColumn('sub_task','user_id',$this->integer()->defaultValue(null));
        $this->addColumn('category','user_id',$this->integer()->defaultValue(null));
        $this->addColumn('note','user_id',$this->integer()->defaultValue(null));
        $this->addColumn('tag','user_id',$this->integer()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('project','user_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191111_035535_add_column_user_id cannot be reverted.\n";

        return false;
    }
    */
}
