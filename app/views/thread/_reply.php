<?php
/* @var Reply $data */
?>
<div class="reply-item-view">
    <h2 class="hidden"><?php echo e($data->subject); ?></h2>
    <div class="reply">
        <div class="row">
            <div class="span2">
                <div class="reply-left">
                    <p><?php echo TbHtml::b($data->alias); ?></p>
                    <?php echo l(format()->formatTimeAgo($data->createdAt),'#',array(
                        'rel'=>'tooltip',
                        'title'=>dateFormatter()->formatDateTime(strtotime($data->createdAt), 'long', 'short'),
                        'class'=>'time-ago',
                    )); ?>
                </div>
            </div>
            <div class="span10">
                <div class="reply-right">
                    <?php if (!empty($data->subject)): ?>
                        <?php echo TbHtml::lead(e($data->subject)); ?>
                    <?php endif; ?>
                    <?php echo app()->bbcodeParser->parse($data->body); ?>
                </div>
            </div>
        </div>
        <div class="reply-bottom clearfix">
            <div class="pull-right">
                <?php echo $data->buttonToolbar(); ?>
            </div>
        </div>
    </div>
</div>