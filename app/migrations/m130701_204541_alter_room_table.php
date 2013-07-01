<?php

class m130701_204541_alter_room_table extends CDbMigration
{
	public function up()
	{
        $this->alterColumn('room', 'description', 'VARCHAR(255) NULL DEFAULT NULL');
	}

	public function down()
	{
        $this->alterColumn('room', 'description', 'VARCHAR(255) NOT NULL');
	}
}