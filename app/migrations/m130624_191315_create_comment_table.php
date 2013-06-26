<?php

class m130624_191315_create_comment_table extends CDbMigration
{
	public function up()
	{
        $this->execute("CREATE TABLE `comment` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `threadId` INT UNSIGNED NOT NULL,
          `alias` VARCHAR(255) NOT NULL,
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