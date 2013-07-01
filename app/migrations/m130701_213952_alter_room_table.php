<?php

class m130701_213952_alter_room_table extends CDbMigration
{
	public function up()
	{
        $this->addColumn('room', 'weight', 'INT NOT NULL DEFAULT 0 AFTER description');
	}

	public function down()
	{
		$this->dropColumn('room', 'weight');
	}
}