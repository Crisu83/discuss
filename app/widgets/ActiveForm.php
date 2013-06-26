<?php

class ActiveForm extends TbActiveForm
{
    public $helpType = TbHtml::HELP_TYPE_BLOCK;

    public function bbCodeControlGroup($model, $attribute, $htmlOptions = array())
    {
        $htmlOptions = $this->processRowOptions($model, $attribute, $htmlOptions);
        return Html::activeBbCodeControlGroup($model, $attribute, $htmlOptions);
    }
}