<?php
/* @var RoomController $this */
/* @var CActiveDataProvider $rooms */

$this->pageTitle=app()->name;
$this->canonical=$this->createUrl('list');
js('js/lib/jquery-sortable.js');
clientScript()->registerScript('RoomSortable',"
    var grid = jQuery('#roomGrid');
    var tableBody = grid.find('tbody').sortable({
        containerSelector: 'tbody',
        itemSelector: 'tr',
        handle: '.draggable-column',
        placeholder: '<tr class=\"placeholder\"/>',
        onDrop: function(item, container, _super) {
            grid.addClass('grid-view-loading');
            var data = tableBody.sortable('serialize').get();
            jQuery.ajax({
                type: 'POST',
                url: '".$this->createUrl('ajaxSortable')."',
                data: { data: data },
                dataType: 'json',
                complete: function(jqXHR, textStatus) {
                    grid.removeClass('grid-view-loading');
                }
            });
            _super(item, container);
        },
        serialize: function (parent, children, isContainer) {
            return isContainer ? children : parent.find('.model-id').text();
        }
    });
");
?>
<div class="room-controller list-action">
    <?php echo TbHtml::lead(t('roomLead','Kotipolku.com on keskustelupalsta ja suunnattu asunnon omistajille sekä omistajuutta suunnitteleville henkilöille. Jaetaan kysymykset remonteista, sisustuksesta, lattiamateriaaleista, pihaistutuksista, homeongelmista tai vaikka laina-asioista.')); ?>

    <div class="site-share">
        <?php $this->widget('ext.facebook.widgets.FbLike',array(
            'url'=>request()->getBaseUrl(true),
            'send'=>true,
        )); ?>
    </div>

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
            'id'=>'roomGrid',
            'type'=>TbHtml::GRID_TYPE_STRIPED,
            'dataProvider'=>$rooms,
            'emptyText'=>t('roomGrid','Valitettavasti yhtään aihealuetta ei löytynyt.'),
            'filter'=>null,
            'template'=>"{items}",
            'columns'=>array(
                array(
                    'type'=>'raw',
                    'value'=>function($data) {
                        echo TbHtml::icon('move').'<span class="model-id" style="display:none;">'.$data->id.'</span>';
                    },
                    'htmlOptions'=>array('class'=>'draggable-column'),
                    'visible'=>!user()->isGuest,
                ),
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