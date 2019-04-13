<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m190407_055727_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'description' => $this->text(),
            'content' => $this->text(),
            'date' => $this->date(),
            'image' => $this->string(),
            'status' => $this->integer(),
            'category' => $this->integer(),
            'sub_tasks' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tasks}}');
    }
}
