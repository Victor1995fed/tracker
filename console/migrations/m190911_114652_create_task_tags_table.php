<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%task_tags}}`.
 */
class m190911_114652_create_task_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%task_tags}}', [
            'id' => $this->primaryKey(),
            'task_id' => $this->integer(),
            'tag_id' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%task_tags}}');
    }
}
