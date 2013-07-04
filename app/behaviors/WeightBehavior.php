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

    /**
     * Updates the weights.
     * @param array $data a list of id in the new order.
     * @param CActiveRecord[] $models the models to update weights for.
     */
    public function updateWeights($data, $models)
    {
        $success = true;
        $weight = 0;
        foreach ($data as $id)
        {
            foreach ($models as $model)
            {
                if ($model->hasAttribute($this->weightAttribute) && (int)$model->id === (int)$id)
                {
                    $model->{$this->weightAttribute} = $weight++;
                    $success = $success && $model->save(true, array($this->weightAttribute));
                }
            }
        }
        return $success;
    }
}