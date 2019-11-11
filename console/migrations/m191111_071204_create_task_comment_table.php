<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_comment}}`.
 */
class m191111_071204_create_task_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_comment}}', [
            'id' => $this->primaryKey(),
            'task_id'=>$this->integer(),
            'comment_id'=>$this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task_comment}}');
    }
}
