<?php

use yii\db\Migration;

/**
 * Class m191123_080944_alter_table_file
 */
class m191123_080944_alter_table_file extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('file','file_hash',$this->char(32)->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('file','file_hash');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191123_080944_alter_table_file cannot be reverted.\n";

        return false;
    }
    */
}
