<?php

use yii\db\Migration;

/**
 * Class m190512_050300_alter_table_tasks_column_priority
 */
class m190512_050300_alter_table_tasks_column_priority extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('tasks', 'priority', $this->integer(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('tasks', 'priority');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190512_050300_alter_table_tasks_column_priority cannot be reverted.\n";

        return false;
    }
    */
}
