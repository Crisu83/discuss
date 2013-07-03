<?php
/* @var RoomController $this */
/* @var CActiveDataProvider $rooms */

$this->pageTitle=app()->name;
$this->canonical=$this->createUrl('list');
?>
<div class="room-controller list-action">
    <?php echo TbHtml::lead(t('roomLead','Kotipolku.com on keskustelupalsta ja suunnattu asunnon omistajille sekä omistajuutta suunnitteleville henkilöille. Jaetaan kysymykset remonteista, sisustuksesta, lattiamateriaaleista, pihaistutuksista, homeongelmista tai vaikka laina-asioista.')); ?>

    <hr>

    <?php if (!user()->isGuest): ?>
        <?php echo TbHtml::linkButton(t('roomButton','Uusi aihealue'),array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'url'=>array('create'),
            'class'=>'create-button',
        )); ?>
    <?php endif; ?>

    <div class="rooms">
        <?php $this->widget('TbGridView',array(
            'type'=>TbHtml::GRID_TYPE_STRIPED,
            'dataProvider'=>$rooms,
            'emptyText'=>t('roomGrid','Valitettavasti yhtään aihealuetta ei löytynyt.'),
            'filter'=>null,
            'template'=>"{items}",
            'columns'=>array(
                array(
                    'header'=>t('roomGrid','Aihepiiri'),
                    'headerHtmlOptions'=>array('class'=>'text-column'),
                    'htmlOptions'=>array('class'=>'text-column'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->topicColumn();
                    },
                ),
                array(
                    'header'=>t('roomGrid','Aiheita'),
                    'headerHtmlOptions'=>array('class'=>'number-column'),
                    'htmlOptions'=>array('class'=>'number-column'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->numThreads;
                    }
                ),
                array(
                    'header'=>t('roomGrid','Vastauksia'),
                    'headerHtmlOptions'=>array('class'=>'text-column'),
                    'htmlOptions'=>array('class'=>'text-column'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->numReplies;
                    }
                ),
                array(
                    'header'=>t('roomGrid','Viimeisin viesti'),
                    'headerHtmlOptions'=>array('class'=>'last-post'),
                    'htmlOptions'=>array('class'=>'last-post'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->latestPostColumn();
                    }
                ),
            ),
        )); ?>
    </div>
</div>