<?php

use yii\db\Migration;

/**
 * Class m191006_050941_rename_status_column_to_project_table
 */
class m191006_050941_rename_status_column_to_project_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('project','status','status_id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('project','status_id','status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191006_050941_rename_status_column_to_project_table cannot be reverted.\n";

        return false;
    }
    */
}
