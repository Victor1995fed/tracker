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
        $this->createTable('{{%statuses}}', [
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
        $this->dropTable('{{%statuses}}');
    }
}
