<?php
/* @var FeaturedBlog $data */
/* @var integer $index */
?>
<div class="span6">
    <a class="blog-thumbnail" href="<?php echo $data->createUrl(); ?>">
        <div class="blog-picture">
            <?php echo $data->renderImagePreset('blogThumb'); ?>
        </div>
        <h5 class="blog-name"><?php echo e($data->name); ?></h5>
        <div class="blog-backdrop"></div>
    </a>
</div>