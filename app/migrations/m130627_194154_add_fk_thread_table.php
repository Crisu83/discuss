<?php

class m130627_194154_add_fk_thread_table extends CDbMigration
{
	public function up()
	{
        $this->addForeignKey('fk_thread_room_roomId', 'thread', 'roomId', 'room', 'id');
	}

	public function down()
	{
		$this->dropForeignKey('fk_thread_room_roomId', 'thread');
	}
}