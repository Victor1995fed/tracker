<?php

use yii\db\Migration;

/**
 * Class m190516_112052_rename_sub_tasks_table
 */
class m190516_112052_rename_sub_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('sub_tasks','sub_task');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190516_112052_rename_sub_tasks_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190516_112052_rename_sub_tasks_table cannot be reverted.\n";

        return false;
    }
    */
}
