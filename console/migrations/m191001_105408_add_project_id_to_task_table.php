<?php

use yii\db\Migration;

/**
 * Class m191001_105408_add_project_id_to_task_table
 */
class m191001_105408_add_project_id_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task','project_id',$this->integer(10)->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191001_105408_add_project_id_to_task_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191001_105408_add_project_id_to_task_table cannot be reverted.\n";

        return false;
    }
    */
}
