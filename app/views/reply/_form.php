<?php
/* @var ThreadController $this */
/* @var Reply $model */
/* @var ActiveForm $form */
?>
<div class="thread-form">
    <?php $form=$this->beginWidget('ActiveForm', array(
        'layout'=>TbHtml::FORM_LAYOUT_VERTICAL,
        'id'=>ReplyController::FORM_ID,
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>

        <?php echo $form->textFieldControlGroup($model,'alias',array('size'=>TbHtml::INPUT_SIZE_LARGE,'value'=>($user = user()->loadModel()) !== null ? $user->name : '')); ?>
        <?php echo $form->textFieldControlGroup($model,'subject',array('size'=>TbHtml::INPUT_SIZE_XXLARGE)); ?>
        <?php echo $form->bbCodeControlGroup($model,'body'); ?>

        <?php echo TbHtml::submitButton(t('replyButton','Tallenna'),array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'size'=>TbHtml::BUTTON_SIZE_LARGE,
        )); ?>

        <?php echo TbHtml::linkButton(t('replyButton','Peruuta'),array(
            'color'=>TbHtml::BUTTON_COLOR_LINK,
            'size'=>TbHtml::BUTTON_SIZE_LARGE,
            'url'=>$model->thread->createUrl(),
        )); ?>

    <?php $this->endWidget(); ?>
</div>