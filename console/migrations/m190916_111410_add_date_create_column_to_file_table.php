<?php

use yii\db\Migration;

/**
 * Handles adding date_create to table `{{%file}}`.
 */
class m190916_111410_add_date_create_column_to_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{file}}', 'date_create', $this->date()->defaultValue(null));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{file}}', 'date_create');
    }
}
