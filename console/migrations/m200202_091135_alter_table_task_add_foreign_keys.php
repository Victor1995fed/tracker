<?php

use yii\db\Migration;

/**
 * Class m200202_091135_alter_table_task_add_foreign_keys
 */
class m200202_091135_alter_table_task_add_foreign_keys extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-task-project','task','project_id','project','id','SET NULL');
        $this->addForeignKey('fk-task-priority','task','priority_id','priority','id','SET NULL');
        $this->addForeignKey('fk-task-status','task','status_id','status','id','SET NULL');
        $this->addForeignKey('fk-task-user','task','user_id','user','id','SET NULL');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task-project','task');
        $this->dropForeignKey('fk-task-priority','task');
        $this->dropForeignKey('fk-task-status','task');
        $this->dropForeignKey('fk-task-user','task');
    }
}
