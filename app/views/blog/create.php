<?php
/* @var BlogController $this */
/* @var FeaturedBlog $model */

$this->pageTitle = array(
    t('blogTitle', 'Uusi blogi'),
);
$this->breadcrumbs=array(
    t('blogBreadcrumb','Blogit')=>array('list'),
    t('blogBreadcrumb', 'Uusi blogi'),
);
?>
<div class="blog-controller create-action">
    <h1><?php echo t('blogHeading','Uusi blogi'); ?></h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>