<?php
/* @var $this ThreadController */
/* @var $model Thread */
?>
<div class="thread-controller update-action">
    <h1>
        <?php echo t('threadHeading','Edit thread'); ?>
        <small><?php echo e($model->subject); ?></small>
    </h1>

    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>