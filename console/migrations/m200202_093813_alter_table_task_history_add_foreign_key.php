<?php

use yii\db\Migration;

/**
 * Class m200202_093813_alter_table_task_history_add_foreign_key
 */
class m200202_093813_alter_table_task_history_add_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-task-history-task','task_history','task_id','task','id','SET NULL');
        $this->addForeignKey('fk-task-history-history','task_history','history_id','history','id','SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task-history-task','task_history');
        $this->dropForeignKey('fk-task-history-history','task_history');
    }

}
