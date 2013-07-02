<?php
/* @var $this RoomController */
/* @var $model Room */

$this->pageTitle = array(
    t('roomTitle', 'Muokkaa aihealuetta'),
);
?>
<div class="room-controller update-action">
    <h1>
        <?php echo t('roomHeading','Muokkaa aihealuetta'); ?>
        <small><?php echo e($model->title); ?></small>
    </h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>