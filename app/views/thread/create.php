<?php
/* @var $this ThreadController */
/* @var $model Thread */

$this->pageTitle = array(
    t('threadTitle','Uusi aihe'),
);
?>
<div class="thread-controller create-action">
    <h1><?php echo t('threadHeading','Uusi aihe'); ?></h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>