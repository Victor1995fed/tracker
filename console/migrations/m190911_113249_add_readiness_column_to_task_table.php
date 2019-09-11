<?php

use yii\db\Migration;

/**
 * Handles adding readiness to table `{{%task}}`.
 */
class m190911_113249_add_readiness_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{task}}', 'readiness', $this->integer(3)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{task}}', 'readiness');
    }
}
