<?php

use yii\db\Migration;

/**
 * Handles adding parent_id to table `{{%task}}`.
 */
class m191005_035829_add_parent_id_column_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task','parent_id',$this->integer()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task','parent_id');
    }
}
