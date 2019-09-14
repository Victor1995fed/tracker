<?php

use yii\db\Migration;

/**
 * Handles adding uuid to table `{{%files}}`.
 */
class m190914_100235_add_uuid_column_to_files_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{files}}', 'uuid', $this->string(36));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{files}}', 'uuid');
    }
}
