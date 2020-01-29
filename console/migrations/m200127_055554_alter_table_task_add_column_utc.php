<?php

use yii\db\Migration;

/**
 * Class m200127_055554_alter_table_task_add_column_utc
 */
class m200127_055554_alter_table_task_add_column_utc extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('task', 'utc', $this->integer()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('task','utc');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200127_055554_alter_table_task_add_column_utc cannot be reverted.\n";

        return false;
    }
    */
}
