<?php
/* @var RoomController $this */
/* @var Room $model */
/* @var ActiveForm $form */
?>

<?php $form=$this->beginWidget('ActiveForm', array(
    'layout'=>TbHtml::FORM_LAYOUT_VERTICAL,
	'id'=>RoomController::FORM_ID,
	'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
)); ?>

    <?php echo $form->textFieldControlGroup($model,'title',array('size'=>TbHtml::INPUT_SIZE_XXLARGE)); ?>
    <?php echo $form->textAreaControlGroup($model,'description',array('rows'=>5,'span'=>8)); ?>

    <?php echo TbHtml::submitButton(t('roomButton','Tallenna'),array(
        'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
        'size'=>TbHtml::BUTTON_SIZE_LARGE,
    )); ?>

    <?php echo TbHtml::linkButton(t('roomButton','Peruuta'),array(
        'color'=>TbHtml::BUTTON_COLOR_LINK,
        'size'=>TbHtml::BUTTON_SIZE_LARGE,
        'url'=>!$model->isNewRecord ? $model->createUrl() : array('list'),
        'confirm'=>t('roomConfirm','Oletko varma että haluat peruuttaa? Kaikki tallentamattomat muutokset menetetään.'),
    )); ?>

<?php $this->endWidget(); ?>