<?php
/* @var Reply $data */
?>
<div class="reply-item-view">
    <h2 class="hidden"><?php echo e($data->subject); ?></h2>
    <div class="reply post">
        <div class="row">
            <div class="span2">
                <div class="post-author">
                    <?php echo TbHtml::b($data->aliasText()); ?><br>
                    <?php echo l(format()->formatTimeAgo($data->createdAt),'#',array(
                        'rel'=>'tooltip',
                        'title'=>dateFormatter()->formatDateTime(strtotime($data->createdAt), 'long', 'short'),
                        'class'=>'time-ago',
                    )); ?>
                </div>
            </div>
            <div class="span10">
                <div class="post-content">
                    <?php if (!empty($data->subject)): ?>
                        <h3 class="post-subject"><?php echo e($data->subject); ?></h3>
                    <?php endif; ?>
                    <div class="post-body">
                        <?php echo app()->bbcodeParser->parse($data->body); ?>
                    </div>
                    <div class="post-actions">
                        <div class="pull-right">
                            <?php echo $data->buttonToolbar(); ?>
                        </div>
                    </div>
                    <div class="post-permalink">
                        <?php echo l(TbHtml::icon('link'),$data->getUrl(),array(
                            'rel'=>'tooltip',
                            'title'=>t('title','Linkki'),
                        )); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>