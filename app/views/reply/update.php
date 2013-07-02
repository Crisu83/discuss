<?php
/* @var ThreadController $this */
/* @var Reply $model */

$this->pageTitle = array(
    t('threadTitle','Muokkaa viestiä'),
);
?>
<div class="reply-controller update-action">
    <h1>
        <?php echo t('replyHeading','Muokkaa viestiä'); ?>
        <?php if (!empty($model->subject)): ?>
            <small><?php echo e($model->subject); ?></small>
        <?php endif; ?>
    </h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>