<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project}}`.
 */
class m190407_055502_create_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->text(),
            'date' => $this->date(),
            'image' => $this->string(),
            'status' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%project}}');
    }
}
