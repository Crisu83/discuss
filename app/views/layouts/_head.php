<?php /* @var $this Controller */ ?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="language" content="en" />

    <link rel="icon" href="<?php echo baseUrl('/favicon.ico'); ?>" type="image/x-icon"/>

    <title><?php echo e($this->pageTitle); ?></title>

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
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>
</head>