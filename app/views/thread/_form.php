<?php
/* @var ThreadController $this */
/* @var Thread $model */
/* @var ActiveForm $form */

clientScript()->registerScript('emoticons',"
	jQuery('#emoticons a').click(function() {
		var emoticon = $(this).attr('data-emoticon');
		jQuery.markItUp({ replaceWith: emoticon	});
		return false;
	});
");
?>

<?php $form=$this->beginWidget('ActiveForm', array(
    'layout'=>TbHtml::FORM_LAYOUT_VERTICAL,
	'id'=>ThreadController::FORM_ID,
	'enableAjaxValidation'=>true,
)); ?>

    <?php echo $form->textFieldControlGroup($model,'alias',array('size'=>TbHtml::INPUT_SIZE_LARGE)); ?>
    <?php echo $form->textFieldControlGroup($model,'subject',array('size'=>TbHtml::INPUT_SIZE_XXLARGE)); ?>
    <?php echo $form->bbCodeControlGroup($model,'body',array('value'=>app()->bbcodeParser->html2bbcode($model->body))); ?>

    <?php echo TbHtml::submitButton(t('threadButton','Save'),array(
        'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
        'size'=>TbHtml::BUTTON_SIZE_LARGE,
    )); ?>

    <?php echo TbHtml::linkButton(t('threadButton','Cancel'),array(
        'color'=>TbHtml::BUTTON_COLOR_LINK,
        'size'=>TbHtml::BUTTON_SIZE_LARGE,
        'url'=>$model->room->getUrl(),
    )); ?>

<?php $this->endWidget(); ?>