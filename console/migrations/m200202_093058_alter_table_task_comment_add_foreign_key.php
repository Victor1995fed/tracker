<?php

use yii\db\Migration;

/**
 * Class m200202_093058_alter_table_task_comment_add_foreign_key
 */
class m200202_093058_alter_table_task_comment_add_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-task-comment-task','task_comment','task_id','task','id','SET NULL');
        $this->addForeignKey('fk-task-comment-comment','task_comment','comment_id','comment','id','SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task-comment-task','task_comment');
        $this->dropForeignKey('fk-task-comment-comment','task_comment');
    }
}
