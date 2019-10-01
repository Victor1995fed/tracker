<?php

use yii\db\Migration;

/**
 * Class m191001_105154_remove_table_sub_category
 */
class m191001_105154_remove_table_sub_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('sub_category');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191001_105154_remove_table_sub_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191001_105154_remove_table_sub_category cannot be reverted.\n";

        return false;
    }
    */
}
