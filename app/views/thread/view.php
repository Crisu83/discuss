<?php
/* @var $this ThreadController */
/* @var $model Thread */
/* @var CActiveDataProvider $replies */
/* @var $reply Reply */

$this->breadcrumbs = array(
    $model->room->title=>array('room/view','id'=>$model->roomId),
    $model->subject,
);
?>
<div class="thread-controller view-action">
    <h1 class="hidden"><?php echo e($model->subject); ?></h1>
    <div class="thread">
        <div class="thread-top clearfix">
            <div class="pull-right">
                <?php //echo l('#',$model->getUrl(),array('rel'=>'tooltip','title'=>t('threadTitle','Permalink'),'class'=>'permalink')); ?>
            </div>
        </div>
        <div class="row">
            <div class="span2">
                <div class="thread-left">
                    <p><?php echo TbHtml::b($model->alias); ?></p>
                    <?php echo l(format()->formatTimeAgo($model->createdAt),'#',array(
                        'rel'=>'tooltip',
                        'title'=>dateFormatter()->formatDateTime(strtotime($model->createdAt), 'long', 'short'),
                        'class'=>'time-ago',
                    )); ?>
                </div>
            </div>
            <div class="span10">
                <div class="thread-right">
                    <?php echo TbHtml::lead(e($model->subject)); ?>
                    <?php echo app()->bbcodeParser->parse($model->body); ?>
                </div>
            </div>
        </div>
        <div class="thread-bottom clearfix">
            <div class="pull-right">
                <?php echo $model->buttonToolbar(); ?>
            </div>
        </div>
    </div>

    <hr>

    <div class="replies">
        <?php $this->widget('TbListView',array(
            'dataProvider'=>$replies,
            'itemView'=>'_reply',
            'emptyText'=>false,
            'template'=>"{items}",
            'separator'=>'<hr>',
        )); ?>
    </div>

    <div class="row">
        <div class="span10 offset2">
            <div class="new-reply">
                <h3><?php echo t('threadHeading','Post a new reply'); ?></h3>
                <?php $this->renderPartial('../reply/_form', array('model'=>$reply)); ?>
            </div>
        </div>
    </div>

    <hr>
</div>