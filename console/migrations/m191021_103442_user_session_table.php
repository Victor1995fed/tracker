<?php

use yii\db\Migration;

/**
 * Class m191021_103442_user_session_table
 */
class m191021_103442_user_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%session}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'token' => $this->text(),
            'date_create'=>$this->timestamp()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_session}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191021_103442_user_session_table cannot be reverted.\n";

        return false;
    }
    */
}
