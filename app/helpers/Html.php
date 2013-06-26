<?php
/**
 * Html class file.
 * Helper that contains logic for rendering of HTML elements.
 */

class Html extends CHtml
{
    public static function activeBbCodeControlGroup($model, $attribute, $htmlOptions = array())
    {
        $markupSet = array(
            array('name'=>t('markitup','Bold'),'key'=>'B','openWith'=>'[b]','closeWith'=>'[/b]'),
            array('name'=>t('markitup','Italic'),'key'=>'I','openWith'=>'[i]','closeWith'=>'[/i]'),
            array('name'=>t('markitup','Underline'),'key'=>'U','openWith'=>'[u]','closeWith'=>'[/u]'),
            array('separator'=>'---------------'),
            array('name'=>t('markitup','Image'),'replaceWith'=>'[img][!['.t('markitup','Image URL').']!][/img]'),
            array('name'=>t('markitup','Link'),'openWith'=>'[url=[!['.t('markitup','Link URL').']!]]','closeWith'=>'[/url]','placeHolder'=>t('markitup','Link text')),
            array('separator'=>'---------------'),
            array('name'=>t('markitup','Size'),'key'=>'S','openWith'=>'[size=[![Text size]!]','closeWith'=>'[/size]','dropMenu'=>array(
                array('name'=>t('markitup','Big'),'openWith'=>'[size=200]','closeWith'=>'[/size]'),
                array('name'=>t('markitup','Normal'),'openWith'=>'[size=100]','closeWith'=>'[/size]'),
                array('name'=>t('markitup','Small'),'openWith'=>'[size=50]','closeWith'=>'[/size]'),
            )),
            array('separator'=>'---------------'),
            array('name'=>t('markitup','Bulleted list'),'openWith'=>'[list]\n','closeWith'=>'\n[/list]'),
            array('name'=>t('markitup','Numeric list'),'openWith'=>'[list=[![Starting number]!]\n','closeWith'=>'\n[/list]'),
            array('name'=>t('markitup','List item'),'openWith'=>'[*] '),
            array('separator'=>'---------------'),
            array('name'=>t('markitup','Quote'),'openWith'=>'[quote]','closeWith'=>'[/quote]'),
            array('name'=>t('markitup','Code'),'openWith'=>'[code]','closeWith'=>'[/code]'),
            array('separator'=>'---------------'),
            array('name'=>t('markitup','Clean'),'className'=>'clean','replaceWith'=>'function(markitup) { return markitup.selection.replace(/\[(.*?)\]/g, ""); }'),
            array('name'=>t('markitup','Preview'),'className'=>'preview','call'=>'preview'),
        );

        $controller = app()->getController();
        ob_start();
        echo '<div class="markitup-widget well">';
        $controller->widget('ext.markitup.MarkItUp',array(
            'model'=>$model,
            'attribute'=>$attribute,
            'set'=>'bbcode',
            'settings'=>array(
                'nameSpace'=>'comment-body',
                'resizeHandle'=>false,
                'markupSet'=>$markupSet,
            ),
            'htmlOptions'=>array('rows'=>5),
        ));
        $controller->renderPartial('../_emoticons');
        echo '</div>';
        $input = ob_get_clean();
        return TbHtml::customActiveControlGroup($input, $model, $attribute, $htmlOptions);
    }
}