<?php
/* @var RoomController $this */
/* @var CActiveDataProvider $rooms */

$this->pageTitle=array(
    t('roomTitle','Keskustelu'),
);
$this->breadcrumbs=array(
    t('roomBreadcrumb', 'Keskustelu'),
);
$this->canonical=$this->createUrl('index');
?>
<div class="site-controller index-action">
    <h1><?php echo t('siteHeading','Keskustelu'); ?></h1>
    <?php echo TbHtml::lead(t('roomLead','Keskustele remonteista, sisustuksesta, lattiamateriaaleista, pihaistutuksista, homeongelmista tai vaikka laina-asioista.')); ?>

    <hr>

    <?php if (!user()->isGuest): ?>
        <?php echo TbHtml::linkButton(t('siteButton','Lisää aihealue'),array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'url'=>array('create'),
            'class'=>'create-button',
        )); ?>
    <?php endif; ?>

    <div class="rooms">
        <?php $this->widget('app.widgets.sortable.widgets.SortableGridView',array(
            'sortUrl'=>app()->createUrl('room/ajaxSort'),
            'sortEnabled'=>!user()->isGuest,
            'type'=>TbHtml::GRID_TYPE_STRIPED,
            'dataProvider'=>$rooms,
            'emptyText'=>t('roomGrid','Valitettavasti yhtään aihealuetta ei löytynyt.'),
            'filter'=>null,
            'template'=>"{items}",
            'columns'=>array(
                array(
                    'header'=>t('roomGrid','Aihealue'),
                    'headerHtmlOptions'=>array('class'=>'text-column'),
                    'htmlOptions'=>array('class'=>'text-column'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->topicColumn();
                    },
                ),
                array(
                    'header'=>t('roomGrid','Aiheita'),
                    'headerHtmlOptions'=>array('class'=>'number-column'),
                    'htmlOptions'=>array('class'=>'number-column'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->numThreads;
                    }
                ),
                array(
                    'header'=>t('roomGrid','Vastauksia'),
                    'headerHtmlOptions'=>array('class'=>'text-column'),
                    'htmlOptions'=>array('class'=>'text-column'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->numReplies;
                    }
                ),
                array(
                    'header'=>t('roomGrid','Viimeisin viesti'),
                    'headerHtmlOptions'=>array('class'=>'last-post'),
                    'htmlOptions'=>array('class'=>'last-post'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->latestPostColumn();
                    }
                ),
            ),
        )); ?>
    </div>
</div>