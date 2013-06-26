<?php

class m130625_180320_create_room_table extends CDbMigration
{
	public function up()
	{
        $this->execute("CREATE TABLE `room` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `title` VARCHAR(255) NOT NULL,
          `description` VARCHAR(255) NOT NULL,
          `status` TINYINT(4) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");
	}

	public function down()
	{
		$this->dropTable('room');
	}
}