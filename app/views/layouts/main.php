<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<?php $this->renderPartial('app.views.layouts._head'); ?>
<body class="layout-main">
	<?php $this->widget('bootstrap.widgets.TbNavbar',array(
        'brandLabel'=>TbHtml::icon('comments').' '.app()->name,
		'collapse'=>true,
		'items'=>array(
            array(
                'class'=>'bootstrap.widgets.TbNav',
                'htmlOptions'=>array('pull'=>TbHtml::PULL_RIGHT),
                'items'=>array(
                    array('label'=>'Sign in','url'=>array('/site/login'),'visible'=>user()->isGuest),
                    array('label'=>'Sign out','url'=>array('/site/logout'),'visible'=>!user()->isGuest),
                ),
            ),
		),
	)); ?>

	<div class="container" id="page">

		<?php if(!empty($this->breadcrumbs)):?>
            <?php $this->widget('bootstrap.widgets.TbBreadcrumb', array(
                'links'=>$this->breadcrumbs,
            )); ?>
		<?php endif?>

        <?php $this->widget('TbAlert'); ?>

        <?php echo $content; ?>

	</div>
</body>
</html>
