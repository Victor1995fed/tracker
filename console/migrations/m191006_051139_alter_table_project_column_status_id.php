<?php

use yii\db\Migration;

/**
 * Class m191006_051139_alter_table_project_column_status_id
 */
class m191006_051139_alter_table_project_column_status_id extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('project','status_id','integer');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191006_051139_alter_table_project_column_status_id cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191006_051139_alter_table_project_column_status_id cannot be reverted.\n";

        return false;
    }
    */
}
