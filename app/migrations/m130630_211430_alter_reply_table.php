<?php

class m130630_211430_alter_reply_table extends CDbMigration
{
	public function up()
	{
        $this->alterColumn('reply', 'alias', 'VARCHAR(255) NULL DEFAULT NULL');
	}

	public function down()
	{
        $this->alterColumn('reply', 'alias', 'VARCHAR(255) NOT NULL');
	}
}