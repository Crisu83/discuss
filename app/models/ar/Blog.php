<?php

/**
* This is the model class for table "blog".
*
* The followings are the available columns:
* @property string $id
* @property string $imageId
* @property string $name
* @property string $description
* @property string $url
* @property integer $weight
* @property integer $status
*
* The followings are the available relations:
* @property Image $image
*/
class Blog extends ActiveRecord
{
    /**
    * Returns the static model of the specified AR class.
    * @param string $className active record class name.
    * @return Blog the static model class.
    */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
    * @return string the associated database table name.
    */
    public function tableName()
    {
        return 'blog';
    }

    /**
    * @return array the behavior configurations (name=>config).
    */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), array(
            'weight' => array(
                'class' => 'app.behaviors.WeightBehavior',
            ),
        ));
    }

    /**
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('name, description, url', 'required'),
            array('weight, status', 'numerical', 'integerOnly'=>true),
            array('imageId', 'length', 'max'=>10),
            array('name, url', 'length', 'max'=>255),
            array('url', 'url', 'defaultScheme' => 'http'),
            // The following rule is used by search().
            array('id, imageId, name, description, url, weight, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
    * @return array relational rules.
    */
    public function relations()
    {
        return array_merge(parent::relations(), array(
            'image' => array(self::BELONGS_TO, 'Image', 'imageId'),
        ));
    }

    /**
    * @return array customized attribute labels (name=>label).
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'id' => t('blogLabel', 'ID'),
            'imageId' => t('blogLabel', 'Kuva'),
            'name' => t('blogLabel', 'Nimi'),
            'description' => t('blogLabel', 'Kuvaus'),
            'url' => t('blogLabel', 'URL osoite'),
            'weight' => t('blogLabel', 'Paino'),
            'status' => t('blogLabel', 'Tila'),
        ));
    }

    /**
    * Retrieves a list of models based on the current search/filter conditions.
    * @return CActiveDataProvider the data provider.
    */
    public function search()
    {
        $criteria=new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('imageId', $this->imageId);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('url', $this->url, true);
		$criteria->compare('weight', $this->weight);
		$criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}