<?php
/* @var BlogController $this */
/* @var FeaturedBlog $model */
/* @var ActiveForm $form */
?>

<?php $form=$this->beginWidget('ActiveForm', array(
    'layout'=>TbHtml::FORM_LAYOUT_VERTICAL,
	'id'=>BlogController::FORM_ID,
	'enableAjaxValidation'=>true,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    'htmlOptions'=>array(
        'enctype'=>'multipart/form-data',
    )
)); ?>

    <?php echo $form->textFieldControlGroup($model,'name',array('size'=>TbHtml::INPUT_SIZE_XXLARGE)); ?>
    <?php echo $form->textAreaControlGroup($model,'description',array('rows'=>5,'span'=>8)); ?>
    <?php echo $form->textFieldControlGroup($model,'url',array('size'=>TbHtml::INPUT_SIZE_XXLARGE)); ?>

    <div class="blog-picture">
        <?php echo $form->fileFieldControlGroup($model,'upload'); ?>
        <?php echo $model->renderImagePreset('blogPreview'); ?>
    </div>

    <?php echo TbHtml::submitButton(t('blogButton','Tallenna'),array(
        'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
        'size'=>TbHtml::BUTTON_SIZE_LARGE,
    )); ?>

    <?php echo TbHtml::linkButton(t('blogButton','Peruuta'),array(
        'color'=>TbHtml::BUTTON_COLOR_LINK,
        'size'=>TbHtml::BUTTON_SIZE_LARGE,
        'url'=>array('list'),
        'confirm'=>t('blogConfirm','Oletko varma että haluat peruuttaa? Kaikki tallentamattomat muutokset menetetään.'),
    )); ?>

<?php $this->endWidget(); ?>