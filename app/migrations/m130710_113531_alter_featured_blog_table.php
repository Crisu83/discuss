<?php

class m130710_113531_alter_featured_blog_table extends CDbMigration
{
	public function up()
	{
        $this->addColumn('featured_blog', 'lead', 'VARCHAR(255) NULL DEFAULT NULL AFTER name');
	}

	public function down()
	{
		$this->dropColumn('featured_blog', 'lead');
	}
}