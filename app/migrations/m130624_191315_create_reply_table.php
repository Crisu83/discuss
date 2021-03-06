<?php

class m130624_191315_create_reply_table extends CDbMigration
{
	public function up()
	{
        $this->execute("CREATE TABLE `reply` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `threadId` INT UNSIGNED NOT NULL,
          `alias` VARCHAR(255) NOT NULL,
          `subject` VARCHAR(255) NULL DEFAULT NULL,
          `body` TEXT NOT NULL,
          `status` TINYINT(4) UNSIGNED NOT NULL,
          PRIMARY KEY (`id`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");
	}

	public function down()
	{
		$this->dropTable('comment');
	}
}