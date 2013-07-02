<?php
/* @var ThreadController $this */
/* @var Thread $model */

$this->pageTitle = array(
    t('threadTitle','Muokkaa aihetta'),
);
?>
<div class="thread-controller update-action">
    <h1>
        <?php echo t('threadHeading','Muokkaa aihetta'); ?>
        <small><?php echo e($model->subject); ?></small>
    </h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>