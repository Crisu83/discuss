<?php
/* @var ThreadController $this */
/* @var Thread $model */
/* @var ActiveForm $form */
?>
<div class="thread-form">
    <?php $form=$this->beginWidget('ActiveForm', array(
        'layout'=>TbHtml::FORM_LAYOUT_VERTICAL,
        'id'=>ThreadController::FORM_ID,
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnChange'=>true,
            'validateOnSubmit'=>true,
        ),
    )); ?>

        <?php echo $form->textFieldControlGroup($model,'alias',array('size'=>TbHtml::INPUT_SIZE_LARGE)); ?>
        <?php echo $form->textFieldControlGroup($model,'subject',array('size'=>TbHtml::INPUT_SIZE_XXLARGE)); ?>
        <?php echo $form->bbCodeControlGroup($model,'body'); ?>

        <?php echo TbHtml::submitButton(t('threadButton','Save'),array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'size'=>TbHtml::BUTTON_SIZE_LARGE,
        )); ?>

        <?php echo TbHtml::linkButton(t('threadButton','Cancel'),array(
            'color'=>TbHtml::BUTTON_COLOR_LINK,
            'size'=>TbHtml::BUTTON_SIZE_LARGE,
            'url'=>$model->getUrl(),
        )); ?>

    <?php $this->endWidget(); ?>
</div>