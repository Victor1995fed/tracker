<?php

use yii\db\Migration;

/**
 * Class m200202_093337_alter_table_task_file_add_foreign_key
 */
class m200202_093337_alter_table_task_file_add_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-task-file-task','task_file','task_id','task','id','SET NULL');
        $this->addForeignKey('fk-task-file-file','task_file','file_id','file','id','SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task-file-task','task_file');
        $this->dropForeignKey('fk-task-file-file','task_file');
    }

}
