<?php
/* @var BlogController $this */
/* @var FeaturedBlog $model */

$this->pageTitle = array(
    t('blogTitle', 'Lisää blogi'),
);
$this->breadcrumbs=array(
    t('blogBreadcrumb','Blogit')=>array('list'),
    t('blogBreadcrumb', 'Lisää blogi'),
);
?>
<div class="blog-controller create-action">
    <h1><?php echo t('blogHeading','Lisää blogi'); ?></h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>