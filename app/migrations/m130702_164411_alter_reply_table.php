<?php

class m130702_164411_alter_reply_table extends CDbMigration
{
	public function up()
	{
        $this->alterColumn('reply', 'status', 'TINYINT(4) NOT NULL DEFAULT 0');
	}

	public function down()
	{
        $this->alterColumn('reply', 'status', 'TINYINT(4) UNSIGNED NOT NULL DEFAULT 0');
	}
}