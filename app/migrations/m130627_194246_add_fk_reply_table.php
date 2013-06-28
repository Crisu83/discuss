<?php

class m130627_194246_add_fk_reply_table extends CDbMigration
{
    public function up()
    {
        $this->addForeignKey('fk_reply_thread_threadId', 'reply', 'threadId', 'thread', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_reply_thread_threadId', 'reply');
    }
}