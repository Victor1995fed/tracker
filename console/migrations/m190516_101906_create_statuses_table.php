<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%statuses}}`.
 */
class m190516_101906_create_statuses_table extends Migration
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

        $this->createTable('{{%statuses}}', [
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
        $this->dropTable('{{%statuses}}');
    }
}
