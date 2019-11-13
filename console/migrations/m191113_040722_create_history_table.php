<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%history}}`.
 */
class m191113_040722_create_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%history}}', [
            'id' => $this->primaryKey(),
            'comment' => $this->text(),
            'user_id' => $this->integer(),
            'date' => 'DATETIME  DEFAULT NOW()'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%history}}');
    }
}
