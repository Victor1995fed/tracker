<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%category}}`.
 */
class m190407_055620_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->text(),
            'id_project' => $this->text(),
            'date' => $this->date(),
            'image' => $this->string(),
            'sub_category' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }
}
