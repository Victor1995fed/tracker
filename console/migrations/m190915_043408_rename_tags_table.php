<?php

use yii\db\Migration;

/**
 * Class m190915_043408_rename_tags_table
 */
class m190915_043408_rename_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('tags','tag');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190915_043408_rename_tags_table cannot be reverted.\n";

        return false;
    }
}
