<?php

Yii::import('bootstrap.widgets.TbGridView');
Yii::import('vendor.crisu83.yii-extension.behaviors.WidgetBehavior');

class SortableGridView extends TbGridView
{
    /**
     * @var boolean whether sorting is enabled.
     */
    public $sortEnabled = true;
    /**
     * @var string the url to call for saving the new order.
     */
    public $sortUrl;

    /**
     * Initializes the widget.
     */
    public function init()
    {
        $this->attachBehavior('widget', new WidgetBehavior);
        $this->copyId();

        if ($this->sortEnabled)
        {
            $this->columns = array_merge(array(
                array(
                    'type'=>'raw',
                    'value'=>function($data) {
                        echo TbHtml::icon('move').'<span class="model-id" style="display:none;">'.$data->id.'</span>';
                    },
                    'htmlOptions'=>array('class'=>'draggable-column'),
                ),
            ), $this->columns);

            $script = <<<EOD
    var widget = jQuery('#{$this->id}');
    var tableBody = widget.find('tbody').sortable({
        containerSelector: 'tbody',
        itemSelector: 'tr',
        handle: '.draggable-column',
        placeholder: '<tr class=\"placeholder\"/>',
        onDrop: function(item, container, _super) {
            widget.addClass('grid-view-loading');
            var data = tableBody.sortable('serialize').get();
            jQuery.ajax({
                type: 'POST',
                url: '{$this->sortUrl}',
                data: { data: data },
                dataType: 'json',
                complete: function(jqXHR, textStatus) {
                    widget.removeClass('grid-view-loading');
                }
            });
            _super(item, container);
        },
        serialize: function (parent, children, isContainer) {
            return isContainer ? children : parent.find('.model-id').text();
        }
    });
EOD;

            $assetsUrl = $this->publishAssets(__DIR__ . '/../assets');
            /* @var CClientScript $cs */
            $cs = Yii::app()->getClientScript();
            $cs->registerCoreScript('jquery');
            $cs->registerScriptFile($assetsUrl . '/js/jquery-sortable.js', CClientScript::POS_END);
            $cs->registerScript(__CLASS__ . '#' . $this->id . '_sortable', $script);
        }

        parent::init();
    }
}