<?php
/* @var $this ThreadController */
/* @var $model Thread */

$this->pageTitle = array(
    t('threadTitle','Uusi aihe'),
);
$this->breadcrumbs=array(
    t('roomBreadcrumb', 'Keskustelu')=>array('room/list'),
    $model->room->title=>array('room/view','id'=>$model->roomId),
    t('threadBreadcrumb','Uusi aihe'),
);
?>
<div class="thread-controller create-action">
    <h1><?php echo t('threadHeading','Uusi aihe'); ?></h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>