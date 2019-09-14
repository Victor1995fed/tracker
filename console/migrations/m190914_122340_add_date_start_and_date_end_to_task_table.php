<?php

use yii\db\Migration;

/**
 * Class m190914_122340_add_date_start_and_date_end_to_task_table
 */
class m190914_122340_add_date_start_and_date_end_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{task}}', 'date_start', $this->date());
        $this->addColumn('{{task}}', 'date_end', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{task}}', 'date_start');
        $this->dropColumn('{{task}}', 'date_end');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190914_122340_add_date_start_and_date_end_to_task_table cannot be reverted.\n";

        return false;
    }
    */
}
