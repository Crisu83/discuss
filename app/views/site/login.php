<?php
/* @var SiteController $this */
/* @var LoginForm $model */
/* @var ActiveForm $form */
?>
<div class="site-controller login-action">

	<h1><?php echo app()->name; ?></h1>

	<div class="login-form">

		<?php $form=$this->beginWidget('ActiveForm',array(
            'id'=>SiteController::FORM_ID_LOGIN,
        )); ?>

			<?php echo $form->textFieldControlGroup($model,'username',array('block'=>true,'label'=>false,'placeholder'=>'Username')); ?>
			<?php echo $form->passwordFieldControlGroup($model,'password',array('block'=>true,'label'=>false,'placeholder'=>'Password')); ?>

            <?php echo TbHtml::submitButton('Login',array(
                'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
                'size'=>TbHtml::BUTTON_SIZE_LARGE,
                'block'=>true,
            )); ?>

		<?php $this->endWidget(); ?>

	</div>
</div>