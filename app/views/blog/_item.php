<?php
/* @var BlogController $this */
/* @var SortableListView $widget */
/* @var FeaturedBlog $data */
/* @var integer $index */
?>
<div class="span6">
    <div class="blog-item">
        <div class="blog-picture">
            <a href="<?php echo $data->createUrl(); ?>">
                <?php echo $data->renderImagePreset('blogCard'); ?>
            </a>
        </div>
        <div class="blog-content">
            <h3 class="blog-name"><?php echo l(e($data->name),$data->createUrl()); ?></h3>
            <p class="blog-lead"><?php echo e($data->lead); ?></p>
            <?php if ($widget->sortEnabled): ?>
                <div class="draggable-handle">
                    <?php echo TbHtml::icon('move'); ?> <span class="model-id" style="display:none;"><?php echo $data->id; ?></span>
                </div>
            <?php endif; ?>
            <div class="blog-actions">
                <div class="pull-right">
                    <?php echo $data->buttonToolbar(); ?>
                </div>
            </div>
        </div>
    </div>
</div>