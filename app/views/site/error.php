<?php
/* @var SiteController $this */
/* @var array $error */
/* @var integer $code */
/* @var string $message */

$this->pageTitle=app()->name.' - '.t('breadcrumb','Virhe');
?>
<div class="site-error">
	<h1><?php echo t('error','Virhe {code}',array('{code}'=>$code)); ?></h1>
	<p><?php echo e($message); ?></p>
</div>