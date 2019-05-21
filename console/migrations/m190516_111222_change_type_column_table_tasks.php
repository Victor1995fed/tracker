<?php

use yii\db\Migration;

/**
 * Class m190516_111222_change_type_column_table_tasks
 */
class m190516_111222_change_type_column_table_tasks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('tasks', 'status');
        $this->addColumn('tasks', 'status',$this->string(15));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190516_111222_change_type_column_table_tasks cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190516_111222_change_type_column_table_tasks cannot be reverted.\n";

        return false;
    }
    */
}
