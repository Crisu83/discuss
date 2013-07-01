<?php

class WeightBehavior extends CActiveRecordBehavior
{
    /**
     * @var string the name of the weight attribute.
     */
    public $weightAttribute = 'weight';

    /**
     * Creates the criteria for ordering by weight.
     * @return CDbCriteria the criteria.
     */
    public function createWeightCriteria()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'weight ASC';
        return $criteria;
    }
}