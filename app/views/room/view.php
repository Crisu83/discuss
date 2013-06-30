<?php
/* @var RoomController $this */
/* @var Room $model */

$this->breadcrumbs=array(
    $model->title,
);
$this->backButton = TbHtml::linkButton(t('threadLink','Return home'),array(
    'url'=>array('room/list'),
    'size'=>TbHtml::BUTTON_SIZE_LARGE,
));
?>
<div class="room-controller view-action">
    <h1>
        <?php echo e($model->title); ?>
        <?php if (!user()->isGuest): ?>
            <small><?php echo l(t('roomButton','edit'),array('update','id'=>$model->id)); ?></small>
        <?php endif; ?>
    </h1>

    <?php echo TbHtml::lead(e($model->description)); ?>

    <hr>

    <?php echo TbHtml::linkButton(t('threadButton','Create thread'),array(
        'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
        'url'=>array('thread/create', 'roomId'=>$model->id),
        'class'=>'create-button',
    )); ?>

    <?php $this->widget('TbGridView',array(
        'type'=>TbHtml::GRID_TYPE_STRIPED,
        'dataProvider'=>$model->createThreadDataProvider(),
        'summaryText'=>t('threadGrid','Displaying {start}-{end} of {count} threads.'),
        'emptyText'=>t('threadGrid','No threads available.'),
        'filter'=>null,
        'columns'=>array(
            array(
                'header'=>t('threadGrid', 'Subject'),
                'value'=>function($data) {
                    /* @var Thread $data */
                    echo $data->subjectColumn();
                },
            ),
            array(
                'header'=>t('roomGrid','Replies'),
                'headerHtmlOptions'=>array('class'=>'number-column'),
                'htmlOptions'=>array('class'=>'number-column'),
                'value'=>function($data) {
                    /* @var Thread $data */
                    echo $data->numReplies;
                }
            ),
            array(
                'header'=>t('roomGrid','Views'),
                'headerHtmlOptions'=>array('class'=>'text-column'),
                'htmlOptions'=>array('class'=>'text-column'),
                'value'=>function($data) {
                    /* @var Thread $data */
                    echo $data->numViews;
                }
            ),
            array(
                'header'=>t('threadGrid','Latest post'),
                'value'=>function($data) {
                    /* @var Thread $data */
                    echo $data->lastPostColumn();
                },
            ),
        ),
    )); ?>
</div>