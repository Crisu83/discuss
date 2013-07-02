<?php
/* @var $this ThreadController */
/* @var $model Thread */
/* @var CActiveDataProvider $replies */
/* @var $reply Reply */

$this->pageTitle = array(
    $model->subject,
    $model->room->title,
);

$this->breadcrumbs = array(
    $model->room->title=>array('room/view','id'=>$model->roomId),
    $model->subject,
);

$this->backButton = TbHtml::linkButton(t('threadLink','Palaa aihealueelle'),array(
    'url'=>$model->room->getUrl(),
    'size'=>TbHtml::BUTTON_SIZE_LARGE,
));

/*
clientScript()->registerScript('PostQuoteButton',"
    $('.quote-button').click(function() {
            window.location.hash = 'add-reply';
            var bbcode = $(this).parents('.post-content').find('.post-bbcode').html();
            $('#Reply_body').text(bbcode);
            return false;
    });
");
*/
?>
<div class="thread-controller view-action">
    <div class="thread post">
        <div class="row">
            <div class="span2">
                <div class="post-author">
                    <?php echo TbHtml::b($model->alias); ?><br>
                    <?php echo l(format()->formatTimeAgo($model->createdAt),'#',array(
                        'rel'=>'tooltip',
                        'title'=>dateFormatter()->formatDateTime(strtotime($model->createdAt), 'long', 'short'),
                        'class'=>'time-ago',
                    )); ?>
                </div>
            </div>
            <div class="span10">
                <div class="post-content">
                    <h1 class="post-subject">
                        <?php echo $model->renderIcons().e($model->subject); ?>
                    </h1>
                    <div class="post-body">
                        <?php echo app()->bbcodeParser->parse($model->body); ?>
                        <div class="post-bbcode hidden"><?php echo $model->body; ?></div>
                    </div>
                    <div class="post-actions">
                        <div class="pull-right">
                            <?php echo $model->buttonToolbar(); ?>
                        </div>
                    </div>
                    <div class="post-permalink">
                        <?php echo l(TbHtml::icon('link'),$model->getUrl(),array(
                            'rel'=>'tooltip',
                            'title'=>t('title','Linkki'),
                        )); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="replies">
        <?php $this->widget('zii.widgets.CListView',array(
            'id'=>'replyList',
            'dataProvider'=>$replies,
            'itemView'=>'/reply/_view',
            'emptyText'=>false,
            'template'=>"{items}",
        )); ?>
    </div>

    <div class="row" id="add-reply">
        <div class="span10 offset2">
            <div class="new-reply">
                <h3><?php echo t('threadHeading','Kirjoita uusi viesti'); ?></h3>
                <?php $this->renderPartial('../reply/_form', array('model'=>$reply)); ?>
            </div>
        </div>
    </div>
</div>