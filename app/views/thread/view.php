<?php
/* @var $this ThreadController */
/* @var $model Thread */

$this->breadcrumbs = array(
    $model->room->title=>array('room/view','id'=>$model->roomId),
    $model->subject,
);
?>
<div class="thread-controller view-action">
    <div class="thread">
        <div class="thread-top clearfix">
            <div class="pull-left muted">
                <?php echo TbHtml::icon('calendar'); ?>
                <?php echo dateFormatter()->formatDateTime(strtotime($model->createdAt), 'long', 'short'); ?>
            </div>
            <div class="pull-right">
                <?php echo l('#',$model->getUrl(),array('rel'=>'tooltip','title'=>t('threadTitle','Permalink'),'class'=>'permalink')); ?>
            </div>
        </div>
        <div class="row">
            <div class="span2">
                <div class="thread-left">
                    <b><?php echo $model->alias; ?></b>
                </div>
            </div>
            <div class="span10">
                <div class="thread-right">
                    <?php echo app()->bbcodeParser->parse($model->body); ?>
                </div>
            </div>
        </div>
        <div class="thread-bottom clearfix">
            <div class="pull-left">
                <?php //echo l(TbHtml::icon('warning-sign'),array('view'),array('rel'=>'tooltip','title'=>t('threadTitle','Report post'))); ?>
            </div>
            <div class="pull-right">
                <?php echo $model->buttonToolbar(); ?>
            </div>
        </div>
    </div>

    <hr>

    <div class="comments">

    </div>
</div>