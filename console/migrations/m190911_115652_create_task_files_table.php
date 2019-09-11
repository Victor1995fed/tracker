<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_files}}`.
 */
class m190911_115652_create_task_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_files}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'files_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task_files}}');
    }
}
