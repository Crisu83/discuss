<?php
/* @var BlogController $this */
/* @var CActiveDataProvider $blogs */

$this->pageTitle=app()->name;
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

    <hr>

    <?php if (!user()->isGuest): ?>
        <?php echo TbHtml::linkButton(t('blogButton','Uusi blogi'),array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'url'=>array('blog/create'),
            'class'=>'create-button',
        )); ?>
    <?php endif; ?>

    <div class="blogs">
        <?php $this->widget('app.widgets.sortablegridview.widgets.SortableGridView',array(
            'sortUrl'=>$this->createUrl('ajaxSort'),
            'sortEnabled'=>!user()->isGuest,
            'type'=>TbHtml::GRID_TYPE_STRIPED,
            'dataProvider'=>$blogs,
            'emptyText'=>t('blogGrid','Valitettavasti yhtään blogia ei löytynyt.'),
            'filter'=>null,
            'template'=>"{items}",
            'columns'=>array(
                array(
                    'header'=>t('blogGrid','Nimi'),
                    'htmlOptions'=>array('class'=>'text-column'),
                    'value'=>function($data) {
                        /* @var Blog $data */
                        echo $data->name;
                    },
                ),
                array(
                    'header'=>t('blogGrid','URL osoite'),
                    'htmlOptions'=>array('class'=>'text-column'),
                    'value'=>function($data) {
                        /* @var Blog $data */
                        echo l($data->url,$data->url,array('rel'=>'nofollow'));
                    }
                ),
                array(
                    'class'=>'bootstrap.widgets.TbButtonColumn',
                    'template'=>'{update} {delete}',
                ),
            ),
        )); ?>
    </div>
</div>