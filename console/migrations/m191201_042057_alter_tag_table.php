<?php

use yii\db\Migration;

/**
 * Class m191201_042057_alter_tag_table
 */
class m191201_042057_alter_tag_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('tag','title',$this->string()->unique());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191201_042057_alter_tag_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191201_042057_alter_tag_table cannot be reverted.\n";

        return false;
    }
    */
}
