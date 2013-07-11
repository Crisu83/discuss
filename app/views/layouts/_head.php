<?php /* @var $this Controller */ ?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <?php /*<link rel="icon" href="<?php echo baseUrl('/favicon.ico'); ?>" type="image/x-icon"/>*/ ?>

    <title><?php echo e($this->pageTitle); ?></title>
    <?php $this->widget('app.widgets.SeoHead',array(
        'defaultProperties'=>array(
            'og:site_name'=>app()->facebook->siteName,
            'og:locale'=>app()->facebook->locale,
            'fb:app_id'=>app()->facebook->appId,
        ),
    )); ?>

    <?php css('css/main.css'); ?>
    <?php css('css/responsive.css'); ?>

    <?php app()->bootstrap->registerAllScripts(); ?>
    <?php app()->bootstrap->registerYiistrapCss(); ?>

    <?php if (YII_DEBUG): ?>
        <?php js('js/main.js'); ?>
    <?php else: ?>
        <?php js('js/main.min.js'); ?>
    <?php endif; ?>

    <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">

    <?php app()->registerGoogleAnalytics(); ?>
</head>