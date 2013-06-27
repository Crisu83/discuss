<?php
/**
 * Html class file.
 * Helper that contains logic for rendering of HTML elements.
 */

class Html extends CHtml
{
    public static function activeBbCodeControlGroup($model, $attribute, $htmlOptions = array())
    {
        $controller = app()->getController();
        ob_start();
        $controller->widget('ext.bbcode.widgets.SCEditor', array(
            'model' => $model,
            'attribute' => $attribute,
            'language' => 'fi',
            'cssFile'=>baseUrl('css/main.css'),
            'options' => array(
                'toolbar' => 'bold,italic,underline|link|quote|emoticon|source',
                'resizeEnabled' => false,
                'enablePasteFiltering' => true,
                'autoExpand' => true,
                'autoUpdate' => true,
            ),
        ));
        $input = ob_get_clean();
        return TbHtml::customActiveControlGroup($input, $model, $attribute, $htmlOptions);
    }
}