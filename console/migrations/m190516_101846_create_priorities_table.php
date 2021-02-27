<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%priorities}}`.
 */
class m190516_101846_create_priorities_table extends Migration
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

        $this->createTable('{{%priorities}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(10),
            'title' => $this->string(20),
        ],$tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%priorities}}');
    }
}
