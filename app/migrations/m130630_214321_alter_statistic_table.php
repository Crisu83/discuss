<?php

class m130630_214321_alter_statistic_table extends CDbMigration
{
	public function up()
	{
        $this->renameColumn('statistic', 'creatorId', 'userId');
        $this->addColumn('statistic', 'ipAddress', 'VARCHAR(255) NOT NULL AFTER `userId`');
	}

	public function down()
	{
        $this->renameColumn('statistic', 'userId', 'creatorId');
        $this->dropColumn('statistic', 'ipAddress');
	}
}