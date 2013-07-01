<?php
/* @var $this ThreadController */
/* @var $model Thread */
?>
<div class="thread-controller create-action">
    <h1><?php echo t('threadHeading','Uusi aihe'); ?></h1>
    <?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
</div>