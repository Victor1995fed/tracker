<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%files}}`.
 */
class m190911_114834_create_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'url' => $this->string(),
            'description'=> $this->text()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}
