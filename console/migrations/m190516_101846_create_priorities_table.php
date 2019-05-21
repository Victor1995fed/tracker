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
        $this->createTable('{{%priorities}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(10),
            'title' => $this->string(20),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%priorities}}');
    }
}
