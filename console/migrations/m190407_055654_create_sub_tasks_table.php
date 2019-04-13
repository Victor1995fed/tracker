<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sub_tasks}}`.
 */
class m190407_055654_create_sub_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sub_tasks}}', [
            'id' => $this->primaryKey(),
            'description' => $this->text(),
            'content' => $this->text(),
            'date' => $this->date(),
            'image' => $this->string(),
            'status' => $this->integer(),
            'category' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sub_tasks}}');
    }
}
