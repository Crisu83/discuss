<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<?php $this->renderPartial('app.views.layouts._head'); ?>
<body class="layout-main">
	<?php $this->widget('bootstrap.widgets.TbNavbar',array(
        'color'=>TbHtml::NAVBAR_COLOR_INVERSE,
        'brandLabel'=>TbHtml::icon('home').' '.app()->name,
		'collapse'=>true,
		'items'=>array(
            array(
                'class'=>'bootstrap.widgets.TbNav',
                'htmlOptions'=>array('pull'=>TbHtml::PULL_RIGHT),
                'items'=>array(
                    array('label'=>t('link','Sign in'),'url'=>array('/site/login'),'visible'=>user()->isGuest),
                    array('label'=>t('link','Sign out'),'url'=>array('/site/logout'),'visible'=>!user()->isGuest),
                ),
            ),
		),
	)); ?>

	<div class="container" id="page">

        <?php if (!empty($this->breadcrumbs) && !empty($this->backButton)): ?>
            <div class="page-top">
                <div class="row">
                    <div class="span8">
                        <div class="page-breadcrumb">
                            <?php if(!empty($this->breadcrumbs)):?>
                                <?php $this->widget('bootstrap.widgets.TbBreadcrumb', array(
                                    'homeLabel'=>t('breadcrumb','Home'),
                                    'links'=>$this->breadcrumbs,
                                )); ?>
                            <?php endif?>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="page-back">
                            <?php if (!empty($this->backButton)): ?>
                                <?php echo $this->backButton; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php $this->widget('TbAlert',array(
            'closeText'=>TbHtml::icon('remove'),
        )); ?>

        <?php echo $content; ?>

	</div>
</body>
</html>
