<?php

class m130704_191659_create_blog_table extends CDbMigration
{
    public function up()
    {
        $this->execute("CREATE TABLE `blog` (
          `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
          `imageId` INT UNSIGNED NULL DEFAULT NULL,
          `name` VARCHAR(255) NOT NULL,
          `description` TEXT NOT NULL,
          `url` VARCHAR(255) NOT NULL,
          `weight` INT NOT NULL DEFAULT '0',
          `status` TINYINT(4) NOT NULL DEFAULT '0',
          PRIMARY KEY (`id`)
        ) COLLATE='utf8_general_ci' ENGINE=InnoDB;");
        $this->addForeignKey('fk_blog_image_imageId', 'blog', 'imageId', 'image', 'id');
    }

    public function down()
    {
        $this->dropForeignKey('fk_blog_image_imageId', 'blog');
        $this->dropTable('blog');
    }
}