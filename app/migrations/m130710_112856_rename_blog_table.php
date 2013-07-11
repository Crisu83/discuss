<?php

class m130710_112856_rename_blog_table extends CDbMigration
{
	public function up()
	{
        $this->renameTable('blog', 'featured_blog');
	}

	public function down()
	{
		$this->renameTable('featured_blog', 'blog');
	}
}