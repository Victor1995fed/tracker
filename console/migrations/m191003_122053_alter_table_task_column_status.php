<?php

use yii\db\Migration;

/**
 * Class m191003_122053_alter_table_task_column_status
 */
class m191003_122053_alter_table_task_column_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('task','status','status_id');
        $this->alterColumn('task','status_id','integer');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191003_122053_alter_table_task_column_status cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191003_122053_alter_table_task_column_status cannot be reverted.\n";

        return false;
    }
    */
}
