<?php

class m130624_190032_create_thread_table extends CDbMigration
{
	public function up()
	{
        $this->execute("CREATE TABLE `thread` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `roomId` INT UNSIGNED NOT NULL,
          `alias` VARCHAR(255) NOT NULL,
          `subject` VARCHAR(255) NOT NULL,
          `body` TEXT NOT NULL,
          `pinned` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
          `locked` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
          `lastActivityAt` DATETIME NOT NULL,
          `status` TINYINT(4) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");
	}

	public function down()
	{
		$this->dropTable('topic');
	}
}