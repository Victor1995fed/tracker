<?php

use yii\db\Migration;

/**
 * Class m200202_094039_alter_table_task_tag_add_foreign_key
 */
class m200202_094039_alter_table_task_tag_add_foreign_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey('fk-task-tag-task','task_tag','task_id','task','id','SET NULL');
        $this->addForeignKey('fk-task-tag-tag','task_tag','tag_id','tag','id','SET NULL');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-task-tag-task','task_tag');
        $this->dropForeignKey('fk-task-tag-tag','task_tag');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200202_094039_alter_table_task_tag_add_foreign_key cannot be reverted.\n";

        return false;
    }
    */
}
