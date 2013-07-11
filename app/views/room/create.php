<?php
/* @var $this RoomController */
/* @var $model Room */

$this->pageTitle = array(
    t('roomTitle', 'Uusi aihealue'),
);
$this->breadcrumbs=array(
    t('roomBreadcrumb', 'Keskustelu')=>array('list'),
    t('roomBreadcrumb', 'Uusi aihealue'),
);
?>
<div class="room-controller create-action">
    <h1><?php echo t('roomHeading','Uusi aihealue'); ?></h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>