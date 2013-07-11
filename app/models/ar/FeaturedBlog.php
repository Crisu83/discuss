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
class FeaturedBlog extends ActiveRecord
{
    public $upload;

    /**
    * Returns the static model of the specified AR class.
    * @param string $className active record class name.
    * @return FeaturedBlog the static model class.
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
        return 'featured_blog';
    }

    /**
    * @return array the behavior configurations (name=>config).
    */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), array(
            'seo' => array(
                'class' => 'vendor.crisu83.yii-seo.behaviors.SeoActiveRecordBehavior',
                'route' => 'blog/view',
                'params' => array(
                    'id' => function($data) {
                        return $data->id;
                    },
                    'name' => function($data) {
                        return strtolower($data->name);
                    },
                ),
            ),
            'image' => array(
                'class' => 'vendor.crisu83.yii-imagemanager.behaviors.ImageBehavior',
            ),
            'weight' => array(
                'class' => 'app.behaviors.WeightBehavior',
            ),
            'workflow' => array(
                'class' => 'app.behaviors.WorkflowBehavior',
                'defaultStatus' => self::STATUS_DEFAULT,
                'statuses' => array(
                    self::STATUS_DEFAULT => array(
                        'label' => t('threadStatus', 'Oletus'),
                        'transitions' => array(self::STATUS_DELETED),
                    ),
                    self::STATUS_DELETED => array(
                        'label' => t('threadStatus', 'Poistettu'),
                    ),
                ),
            ),
        ));
    }

    /**
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return array_merge(parent::rules(), array(
            array('name, lead, url', 'required'),
            array('weight, status', 'numerical', 'integerOnly'=>true),
            array('imageId', 'length', 'max'=>10),
            array('name, lead, url', 'length', 'max'=>255),
            array('url', 'url', 'defaultScheme' => 'http'),
            array('description, upload', 'safe'),
            // The following rule is used by search().
            array('id, imageId, name, lead, description, url, weight, status', 'safe', 'on' => 'search'),
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
            'lead' => t('blogLabel', 'Johdanto'),
            'description' => t('blogLabel', 'Kuvaus'),
            'url' => t('blogLabel', 'URL osoite'),
            'weight' => t('blogLabel', 'Paino'),
            'status' => t('blogLabel', 'Tila'),
            'upload' => t('blogLabel', 'Kuva'),
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
		$criteria->compare('lead', $this->lead, true);
		$criteria->compare('description', $this->description, true);
		$criteria->compare('url', $this->url, true);
		$criteria->compare('weight', $this->weight);
		$criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function buttonToolbar()
    {
        $buttons = array();
        $buttons[] = TbHtml::linkButton(t('blogButton','Tutustu'), array(
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'url' => $this->createUrl(),
            'class' => 'blog-button',
        ));
        if (!user()->isGuest)
        {
            $buttons[] = TbHtml::linkButton(TbHtml::icon('pencil'), array(
                'url' => array('update', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('blogTitle', 'Muokkaa blogia'),
                'class' => 'blog-button',
            ));
            $buttons[] = TbHtml::linkButton(TbHtml::icon('remove'), array(
                'url' => array('delete', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('blogTitle', 'Poista blogi'),
                'confirm' => t('blogConfirm', 'Oletko varma että haluat poistaa tämän blogin?'),
                'class' => 'blog-button',
            ));
        }
        return implode(' ', $buttons);
    }
}