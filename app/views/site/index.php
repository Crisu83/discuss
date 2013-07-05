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

    <div class="clearfix">
        <h3 class="pull-left"><?php echo t('siteHeading','Blogit'); ?></h3>
        <?php if (!user()->isGuest): ?>
            <?php echo TbHtml::linkButton(t('siteButton','Hallinnoi blogeja'),array(
                'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
                'url'=>array('blog/admin'),
                'pull'=>TbHtml::PULL_RIGHT,
            )); ?>
        <?php endif; ?>
    </div>

    <div class="blogs">
    <?php $this->widget('bootstrap.widgets.TbListView',array(
        'dataProvider'=>$blogs,
        'template'=>"{items}",
        'itemsCssClass'=>'thumbnails',
        'itemView'=>'app.views.blog._thumbnail',
    )); ?>
    </div>

    <hr>

    <div class="clearfix">
        <h3 class="pull-left"><?php echo t('siteHeading','Keskustelu'); ?></h3>
        <?php echo TbHtml::linkButton(t('siteButton','Siirry keskustelualueelle'),array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'url'=>array('room/index'),
            'pull'=>TbHtml::PULL_RIGHT,
        )); ?>
    </div>

    <div class="rooms">
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
                    'header'=>t('threadGrid','Vastauksia'),
                    'headerHtmlOptions'=>array('class'=>'number-column'),
                    'htmlOptions'=>array('class'=>'number-column'),
                    'value'=>function($data) {
                        /* @var Thread $data */
                        echo $data->numReplies;
                    }
                ),
                array(
                    'header'=>t('threadGrid','Katsottu'),
                    'headerHtmlOptions'=>array('class'=>'text-column'),
                    'htmlOptions'=>array('class'=>'text-column'),
                    'value'=>function($data) {
                        /* @var Thread $data */
                        echo $data->numViews;
                    }
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