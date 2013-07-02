<?php
/* @var RoomController $this */
/* @var Room $model */

$this->pageTitle = array(
    $model->title,
);

$this->breadcrumbs=array(
    $model->title,
);

$this->backButton = TbHtml::linkButton(t('threadLink','Palaa etusivulle'),array(
    'url'=>array('room/list'),
    'size'=>TbHtml::BUTTON_SIZE_LARGE,
));
?>
<div class="room-controller view-action">
    <h1>
        <?php echo e($model->title); ?>
        <?php if (!user()->isGuest): ?>
            <small><?php echo l(t('roomButton','muokkaa'),array('update','id'=>$model->id)); ?></small>
        <?php endif; ?>
    </h1>

    <?php echo TbHtml::lead(e($model->description)); ?>

    <hr>

    <?php echo TbHtml::linkButton(t('threadButton','Uusi aihe'),array(
        'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
        'url'=>array('thread/create', 'roomId'=>$model->id),
        'class'=>'create-button',
    )); ?>

    <?php $this->widget('TbGridView',array(
        'type'=>TbHtml::GRID_TYPE_STRIPED,
        'dataProvider'=>$model->createThreadDataProvider(),
        'emptyText'=>t('threadGrid','Valitettavasti yhtään aihetta ei löytynyt.'),
        'filter'=>null,
        'template'=>"{items}",
        'columns'=>array(
            array(
                'header'=>t('threadGrid', 'Otsikko'),
                'value'=>function($data) {
                    /* @var Thread $data */
                    echo $data->subjectColumn();
                },
            ),
            array(
                'header'=>t('threadGrid','Vastauksia'),
                'headerHtmlOptions'=>array('class'=>'number-column'),
                'htmlOptions'=>array('class'=>'number-column'),
                'value'=>function($data) {
                    /* @var Thread $data */
                    echo $data->numReplies;
                }
            ),
            array(
                'header'=>t('threadGrid','Katsottu'),
                'headerHtmlOptions'=>array('class'=>'text-column'),
                'htmlOptions'=>array('class'=>'text-column'),
                'value'=>function($data) {
                    /* @var Thread $data */
                    echo $data->numViews;
                }
            ),
            array(
                'header'=>t('threadGrid','Viimeisin viesti'),
                'value'=>function($data) {
                    /* @var Thread $data */
                    echo $data->lastPostColumn();
                },
            ),
        ),
    )); ?>
</div>