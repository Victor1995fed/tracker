<?php

use yii\db\Migration;

/**
 * Class m190601_084657_rename_category_column_and_priority_task_table
 */
class m190601_084657_rename_category_column_and_priority_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('task','category','category_id');
        $this->renameColumn('task','priority','priority_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('task','category_id','category');
        $this->renameColumn('task','priority_id','priority');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190601_084657_rename_category_column_and_priority_task_table cannot be reverted.\n";

        return false;
    }
    */
}
