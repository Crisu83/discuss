<?php
/* @var BlogController $this */
/* @var FeaturedBlog $model */

$this->pageTitle=array(
    $model->name,
    t('blogTitle','Blogit'),
);
$this->addMetaProperty('og:title',$model->name);
$this->addMetaProperty('og:url',$model->createAbsoluteUrl());
$this->canonical=$model->createAbsoluteUrl();
$this->breadcrumbs=array(
    t('blogBreadcrumb','Blogit')=>array('list'),
    $model->name,
);
$this->backButton=TbHtml::linkButton(t('threadLink','Palaa blogeihin'),array(
    'url'=>array('list'),
    'size'=>TbHtml::BUTTON_SIZE_LARGE,
));
?>
<div class="blog-controller view-action">
    <div class="row">
        <div class="span4">
            <div class="blog-picture">
                <?php echo $model->renderImagePreset('blogPage'); ?>
            </div>
        </div>
        <div class="span8">
            <h1><?php echo e($model->name); ?></h1>
            <?php echo TbHtml::lead(e($model->lead),array('class'=>'blog-lead')); ?>

            <div class="facebook-like">
                <?php $this->widget('ext.facebook.widgets.FbLike',array(
                    'url'=>$model->createUrl(),
                    'send'=>true,
                )); ?>
            </div>

            <hr>

            <p class="blog-description"><?php echo e($model->description); ?></p>

            <?php echo TbHtml::linkButton(t('blogButton','Siirry blogiin'),array(
                'id'=>'gotoBlogLink',
                'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
                'size'=>TbHtml::BUTTON_SIZE_LARGE,
                'url'=>$model->url,
                'rel'=>'nofollow',
                'target'=>'_blank',
            )); ?>
        </div>
    </div>
</div>