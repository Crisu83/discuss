<?php
/* @var Blog $data */
/* @var integer $index */
?>
<?php if ($index === 0): ?>
    <div class="span2">
        <div class="thumbnail blog-thumbnail">
            <h5 class="blog-name"><?php echo t('blogHeading','Haluatko blogisi tähän?'); ?></h5>
            <p class="blog-description">&nbsp;</p>
            <?php echo CHtml::mailto(t('blogButton','Ota meihin yhteyttä!'),param('adminEmail'),array(
                'class'=>'btn btn-primary btn-block blog-link',
            )); ?>
        </div>
    </div>
<?php endif; ?>
<div class="span2">
    <div class="thumbnail blog-thumbnail">
        <h5 class="blog-name"><?php echo e($data->name); ?></h5>
        <p class="blog-description"><?php echo e($data->description); ?></p>
        <?php echo TbHtml::linkButton(t('blogButton','Siirry blogiin'),array(
            'url'=>$data->url,
            'class'=>'blog-link',
            'rel'=>'nofollow',
            'block'=>true,
        )); ?>
    </div>
</div>