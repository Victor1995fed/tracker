<?php

use yii\db\Migration;

/**
 * Class m190915_042832_rename_files_table
 */
class m190915_042832_rename_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('files','file');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190915_042832_rename_files_table cannot be reverted.\n";

        return false;
    }

}
