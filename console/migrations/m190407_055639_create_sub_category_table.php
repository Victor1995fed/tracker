<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sub_category}}`.
 */
class m190407_055639_create_sub_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sub_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'description' => $this->text(),
            'id_project' => $this->text(),
            'date' => $this->date(),
            'image' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%sub_category}}');
    }
}
