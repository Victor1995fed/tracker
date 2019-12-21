<?php

use yii\db\Migration;

/**
 * Class m191221_054514_add_index_for_tables
 */
class m191221_054514_add_index_for_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex('idx_comment_user_id','comment','user_id');
        $this->createIndex('idx_history_user_id','history','user_id');
        $this->createIndex('idx_note_user_id','note','user_id');
        $this->createIndex('idx_project_user_id','project','user_id');
        $this->createIndex('idx_project_status_id','project','status_id');
        $this->createIndex('idx_tag_user_id','tag','user_id');

        $this->createIndex('idx_task_category_id','task','category_id');
        $this->createIndex('idx_task_priority_id','task','priority_id');
        $this->createIndex('idx_task_status_id','task','status_id');
        $this->createIndex('idx_task_project_id','task','project_id');
        $this->createIndex('idx_task_parent_id','task','parent_id');
        $this->createIndex('idx_task_user_id','task','user_id');

        $this->createIndex('idx_task_comment_task_id','task_comment','task_id');
        $this->createIndex('idx_task_comment_comment_id','task_comment','comment_id');
        $this->createIndex('idx_task_file_file_id','task_file','file_id');
        $this->createIndex('idx_task_file_task_id','task_file','task_id');
        $this->createIndex('idx_task_history_task_id','task_history','task_id');
        $this->createIndex('idx_task_history_history_id','task_history','history_id');

        $this->createIndex('idx_task_tag_task_id','task_tag','task_id');
        $this->createIndex('idx_task_tag_tag_id','task_tag','tag_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_comment_user_id','comment');
        $this->dropIndex('idx_history_user_id','history');
        $this->dropIndex('idx_note_user_id','note');
        $this->dropIndex('idx_project_user_id','project');
        $this->dropIndex('idx_project_status_id','project');
        $this->dropIndex('idx_tag_user_id','tag');

        $this->dropIndex('idx_task_category_id','task');
        $this->dropIndex('idx_task_priority_id','task');
        $this->dropIndex('idx_task_status_id','task');
        $this->dropIndex('idx_task_project_id','task');
        $this->dropIndex('idx_task_parent_id','task');
        $this->dropIndex('idx_task_user_id','task');

        $this->dropIndex('idx_task_comment_task_id','task_comment');
        $this->dropIndex('idx_task_comment_comment_id','task_comment');
        $this->dropIndex('idx_task_file_file_id','task_file');
        $this->dropIndex('idx_task_file_task_id','task_file');
        $this->dropIndex('idx_task_history_task_id','task_history');
        $this->dropIndex('idx_task_history_history_id','task_history');

        $this->dropIndex('idx_task_tag_task_id','task_tag');
        $this->dropIndex('idx_task_tag_tag_id','task_tag');
    }

}
