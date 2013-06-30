<?php
/* @var RoomController $this */
/* @var CActiveDataProvider $rooms */
?>
<div class="room-controller list-action">
    <?php echo TbHtml::lead(t('roomLead','Hello there! Start by choosing a discussion room.')); ?>

    <hr>

    <?php if (!user()->isGuest): ?>
        <?php echo TbHtml::linkButton(t('roomButton','Create room'),array(
            'color'=>TbHtml::BUTTON_COLOR_PRIMARY,
            'url'=>array('create'),
            'class'=>'create-button',
        )); ?>
    <?php endif; ?>

    <div class="rooms">
        <?php $this->widget('TbGridView',array(
            'type'=>TbHtml::GRID_TYPE_STRIPED,
            'dataProvider'=>$rooms,
            'summaryText'=>t('roomGrid','Displaying {start}-{end} of {count} rooms.'),
            'emptyText'=>t('roomGrid','Sorry, no rooms were found.'),
            'filter'=>null,
            'columns'=>array(
                array(
                    'header'=>t('roomGrid','Topic'),
                    'headerHtmlOptions'=>array('class'=>'text-column'),
                    'htmlOptions'=>array('class'=>'text-column'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->topicColumn();
                    },
                ),
                array(
                    'header'=>t('roomGrid','Threads'),
                    'headerHtmlOptions'=>array('class'=>'number-column'),
                    'htmlOptions'=>array('class'=>'number-column'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->numThreads;
                    }
                ),
                array(
                    'header'=>t('roomGrid','Replies'),
                    'headerHtmlOptions'=>array('class'=>'text-column'),
                    'htmlOptions'=>array('class'=>'text-column'),
                    'value'=>function($data) {
                        /* @var Room $data */
                        echo $data->numReplies;
                    }
                ),
                array(
                    'header'=>t('roomGrid','Last post'),
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