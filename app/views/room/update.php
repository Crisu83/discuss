<?php
/* @var $this RoomController */
/* @var $model Room */
?>
<div class="room-controller update-action">
    <h1>
        <?php echo t('roomHeading','Edit room'); ?>
        <small><?php echo e($model->title); ?></small>
    </h1>

    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>