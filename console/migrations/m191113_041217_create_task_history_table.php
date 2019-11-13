<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_history}}`.
 */
class m191113_041217_create_task_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_history}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'history_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task_history}}');
    }
}
