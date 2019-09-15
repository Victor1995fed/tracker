<?php

use yii\db\Migration;

/**
 * Class m190915_041425_add_spending_to_task_table
 */
class m190915_041425_add_spending_to_task_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{task}}', 'spending', $this->decimal(5,1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{task}}', 'spending');
    }
}
