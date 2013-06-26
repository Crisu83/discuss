<?php
/* @var $this RoomController */
/* @var $model Room */
?>
<div class="room-controller create-action">
    <h1><?php echo t('roomHeading','Create room'); ?></h1>

    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>