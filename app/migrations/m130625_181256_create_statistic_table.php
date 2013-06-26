<?php

class m130625_181256_create_statistic_table extends CDbMigration
{
	public function up()
	{
        $this->execute("CREATE TABLE IF NOT EXISTS `statistic` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `action` VARCHAR(255) NOT NULL,
          `model` VARCHAR(255) NOT NULL,
          `modelId` INT UNSIGNED NOT NULL,
          `creatorId` INT UNSIGNED NOT NULL,
          `createdAt` DATETIME NOT NULL,
          PRIMARY KEY (`id`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");
	}

	public function down()
	{
		$this->dropTable('statistic');
	}
}