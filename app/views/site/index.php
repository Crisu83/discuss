<?php
/* @var SiteController $this */
/* @var CActiveDataProvider $blogs */
/* @var CActiveDataProvider $threads */

$this->pageTitle=app()->name;
$this->canonical=$this->createUrl('index');
?>
<div class="site-controller index-action">
    <?php echo TbHtml::lead(t('siteLead','Kotipolku.com on sivusto joka on suunnattu asunnon omistajille ja omistajuutta suunnitteleville henkilöille.')); ?>

    <div class="site-share">
        <?php $this->widget('ext.facebook.widgets.FbLike',array(
            'url'=>request()->getBaseUrl(true),
            'send'=>true,
        )); ?>
    </div>

    <hr>

    <div class="row">
        <div class="span9">
            <div class="discussion">
                <h3>
                    <?php echo l(t('siteHeading','Keskustelu'),array('room/list')); ?>
                    <small><?php echo t('blogHeading','Uusimmat viestit'); ?></small>
                </h3>

                <div class="threads">
                    <?php $this->widget('bootstrap.widgets.TbGridView',array(
                        'type'=>TbHtml::GRID_TYPE_STRIPED,
                        'dataProvider'=>$threads,
                        'emptyText'=>t('threadGrid','Valitettavasti yhtään aihetta ei löytynyt.'),
                        'filter'=>null,
                        'template'=>"{items}",
                        'columns'=>array(
                            array(
                                'header'=>t('threadGrid', 'Otsikko'),
                                'value'=>function($data) {
                                    /* @var Thread $data */
                                    echo $data->subjectColumn();
                                },
                            ),
                            array(
                                'header'=>t('threadGrid','Viimeisin viesti'),
                                'value'=>function($data) {
                                    /* @var Thread $data */
                                    echo $data->lastPostColumn();
                                },
                            ),
                        ),
                    )); ?>
                </div>
            </div>
        </div>
        <div class="span3">
            <div class="blogs">
                <h3>
                    <?php echo l(t('siteHeading','Blogit'),array('blog/list')); ?>
                    <small><?php echo t('blogHeading','Suosittelemme'); ?></small>
                </h3>

                <?php /*if (!user()->isGuest): ?>
                    <?php echo TbHtml::linkButton(t('siteButton','Hallinnoi blogeja'),array(
                        'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
                        'url'=>array('blog/admin'),
                        'class'=>'create-button',
                    )); ?>
                <?php endif;*/ ?>

                <div class="blog-list">
                    <?php $this->widget('bootstrap.widgets.TbListView',array(
                        'dataProvider'=>$blogs,
                        'template'=>"{items}",
                        'itemsCssClass'=>'blog-thumbnails row-fluid',
                        'itemView'=>'app.views.blog._thumbnail',
                    )); ?>
                </div>

                    <?php echo TbHtml::linkButton(t('blogLink','Kerro meille blogistasi'),array(
                        'color'=>TbHtml::BUTTON_COLOR_LINK,
                        'size'=>TbHtml::BUTTON_SIZE_LARGE,
                        'url'=>'mailto:kotipolku@outlook.com',
                        'block' => true,
                    )); ?>

            </div>
        </div>
    </div>

</div>