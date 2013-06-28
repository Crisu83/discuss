<?php

/**
 * This is the model class for table "Statistic".
 *
 * The followings are the available columns in table 'Statistic':
 * @property integer $id
 * @property string $action
 * @property string $model
 * @property integer $modelId
 * @property integer $creatorId
 * @property integer $createdAt
 */
class Statistic extends ActiveRecord
{
    // Statistics actions.
    const ACTION_VIEW = 'view';

    /**
     * Returns the static model of the specified AR class.
     * @return Statistic the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'statistic';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('action, model, modelId, creatorId', 'required'),
            array('action, model', 'length', 'max'=>255),
            array('modelId, creatorId', 'length', 'max'=>10),
        ));
    }

    /**
     * Creates a statistics record.
     * @param array $attributes the attributes.
     * @param bool $allowMultiple whether multiple occurrences are allowed.
     * @return boolean whether the record was saved.
     */
    public static function create($attributes, $allowMultiple = false)
    {
        $model = Statistic::model()->findByAttributes($attributes);
        if ($model === null || $allowMultiple)
        {
            $attributes['createdAt'] = sqlDateTime();
            $model = new Statistic();
            foreach ($attributes as $name => $value)
                $model->$name = $value;
            return $model->save(true, array_keys($attributes));
        }
        return false;
    }
}