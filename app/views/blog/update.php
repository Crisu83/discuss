<?php
/* @var BlogController $this */
/* @var Blog $model */

$this->pageTitle = array(
    t('blogTitle', 'Muokkaa blogia'),
);
$this->breadcrumbs=array(
    t('blogBreadcrumb','Blogit')=>array('admin'),
    $model->name=>array('#'),
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