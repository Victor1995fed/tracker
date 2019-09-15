<?php

use yii\db\Migration;

/**
 * Class m190915_043717_rename_statuses_table
 */
class m190915_043717_rename_statuses_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('statuses','status');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190915_043717_rename_statuses_table cannot be reverted.\n";

        return false;
    }

}
