<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html lang="en">
<?php $this->renderPartial('app.views.layouts._head'); ?>
<body class="layout-main">
    <?php app()->facebook->register(); ?>

	<?php $this->widget('bootstrap.widgets.TbNavbar',array(
        'color'=>TbHtml::NAVBAR_COLOR_INVERSE,
        'brandLabel'=>TbHtml::icon('home').' Kotipolku',
		'collapse'=>true,
		'items'=>array(
            array(
                'class'=>'bootstrap.widgets.TbNav',
                'items'=>array(
                    array('label'=>t('link','Etusivu'),'url'=>array('/site/index')),
                    array('label'=>t('link','Keskustelu'),'url'=>array('/room/list')),
                    array('label'=>t('link','Blogit'),'url'=>array('/blog/list')),
                ),
            ),
            array(
                'class'=>'bootstrap.widgets.TbNav',
                'htmlOptions'=>array('pull'=>TbHtml::PULL_RIGHT),
                'items'=>array(
                    array('label'=>t('link','Kirjaudu ylläpitäjänä'),'url'=>array('/site/login'),'visible'=>user()->isGuest),
                    array('label'=>t('link','Kirjaudu ulos'),'url'=>array('/site/logout'),'visible'=>!user()->isGuest),
                ),
            ),
		),
	)); ?>

	<div class="container" id="page">

        <?php if (!empty($this->breadcrumbs) || !empty($this->backButton)): ?>
            <div class="page-top">
                <div class="row">
                    <div class="span8">
                        <div class="page-breadcrumb">
                            <?php if(!empty($this->breadcrumbs)):?>
                                <?php $this->widget('bootstrap.widgets.TbBreadcrumb', array(
                                    'homeLabel'=>t('breadcrumb','Etusivu'),
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

        <hr>

        <div id="footer">
            <div class="copyright">
                <?php echo t('app','&copy; Kotipolku {year}. Kaikki oikeudet pidätetään.',array('{year}'=>date('Y'))); ?>
            </div>
        </div>

	</div>
</body>
</html>
