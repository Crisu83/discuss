<?php
/* @var BlogController $this */
/* @var FeaturedBlog $model */

$this->pageTitle = array(
    t('blogTitle', 'Muokkaa blogia'),
);
$this->breadcrumbs=array(
    t('blogBreadcrumb','Blogit')=>array('list'),
    $model->name=>$model->createUrl(),
    t('blogBreadcrumb', 'Muokkaa'),
);
?>
<div class="blog-controller update-action">
    <h1>
        <?php echo t('blogHeading','Muokkaa blogia'); ?>
        <small><?php echo e($model->name); ?></small>
    </h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>