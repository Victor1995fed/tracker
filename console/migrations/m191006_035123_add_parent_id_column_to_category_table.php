<?php

use yii\db\Migration;

/**
 * Handles adding parent_id to table `{{%category}}`.
 */
class m191006_035123_add_parent_id_column_to_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('category','parent_id',$this->integer()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('category','parent_id');
    }
}
