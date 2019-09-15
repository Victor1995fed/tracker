<?php

use yii\db\Migration;

/**
 * Class m190915_044105_rename_priorities_task_files_task_tags_table
 */
class m190915_044105_rename_priorities_task_files_task_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('priorities','priority');
        $this->renameTable('task_files','task_file');
        $this->renameTable('task_tags','task_tag');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190915_044105_rename_priorities_task_files_task_tags_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190915_044105_rename_priorities_task_files_task_tags_table cannot be reverted.\n";

        return false;
    }
    */
}
