<?php

use yii\db\Migration;

/**
 * Class m190915_044706_rename_column_files_id_to_task_file_table
 */
class m190915_044706_rename_column_files_id_to_task_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
$this->renameColumn('task_file','files_id','file_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190915_044706_rename_column_files_id_to_task_file_table cannot be reverted.\n";

        return false;
    }


}
