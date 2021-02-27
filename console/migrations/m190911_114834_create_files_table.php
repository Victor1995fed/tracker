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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%files}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
            'url' => $this->string(),
            'description'=> $this->text()
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%files}}');
    }
}
