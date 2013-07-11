<?php
/* @var BlogController $this */
/* @var CActiveDataProvider $blogs */

$this->pageTitle=array(
    t('blogTitle','Blogit'),
);
$this->breadcrumbs=array(
    t('blogBreadcrumb','Blogit'),
);
$this->backButton=TbHtml::linkButton(t('threadLink','Palaa etusivulle'),array(
    'url'=>app()->homeUrl,
    'size'=>TbHtml::BUTTON_SIZE_LARGE,
));
?>
<div class="blog-controller list-action">
    <h1><?php echo t('blogHeading','Blogit'); ?></h1>
    <?php echo TbHtml::lead(t('blogLead','Suosittelemme seuraavia blogeja.')); ?>

    <hr>

    <?php if (!user()->isGuest): ?>
        <?php echo TbHtml::linkButton(t('blogButton','Lisää blogi'),array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'url'=>array('blog/create'),
            'class'=>'create-button',
        )); ?>
    <?php endif; ?>

    <div class="blog-list">
        <?php $this->widget('app.widgets.sortable.widgets.SortableListView',array(
            'sortUrl'=>$this->createUrl('ajaxSort'),
            'sortEnabled'=>!user()->isGuest,
            'dataProvider'=>$blogs,
            'emptyText'=>t('blogGrid','Valitettavasti yhtään blogia ei löytynyt.'),
            'template'=>"{items}",
            'itemsCssClass'=>'row',
            'itemView'=>'_item',
        )); ?>
    </div>
</div>