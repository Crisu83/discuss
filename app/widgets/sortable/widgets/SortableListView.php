<?php

Yii::import('bootstrap.widgets.TbListView');
Yii::import('vendor.crisu83.yii-extension.behaviors.WidgetBehavior');

class SortableListView extends TbListView
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
            $script = <<<EOD
    var widget = jQuery('#{$this->id}');
    var row = widget.find('> .row').sortable({
        containerSelector: '.row',
        itemSelector: '.span6',
        handle: '.draggable-handle',
        placeholder: '<div class="placeholder"/>',
        onDrop: function(item, container, _super) {
            widget.addClass('list-view-loading');
            var data = row.sortable('serialize').get();
            jQuery.ajax({
                type: 'POST',
                url: '{$this->sortUrl}',
                data: { data: data },
                dataType: 'json',
                complete: function(jqXHR, textStatus) {
                    widget.removeClass('list-view-loading');
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