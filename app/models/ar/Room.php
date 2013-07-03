<?php

/**
* This is the model class for table "room".
*
* The followings are the available columns in table 'room':
* @property string $id
* @property string $title
* @property string $description
* @property integer $weight
* @property integer $status
 *
 * The followings are the available relations for table 'thread':
 * @property integer $numThreads
 * @property integer $numReplies
*/
class Room extends AuditActiveRecord
{
    /**
    * Returns the static model of the specified AR class.
    * @param string $className active record class name.
    * @return Room the static model class.
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
        return 'room';
    }

    /**
    * @return array the behavior configurations (name=>config).
    */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), array(
            array(
                'class' => 'vendor.crisu83.yii-seo.behaviors.SeoActiveRecordBehavior',
                'route' => 'room/view',
                'params' => array(
                    'id' => function($data) {
                        return $data->id;
                    },
                    'name' => function($data) {
                        return strtolower($data->title);
                    },
                ),
            ),
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
            array('title', 'required'),
            array('weight, status', 'numerical', 'integerOnly'=>true),
            array('title, description', 'length', 'max'=>255),
            // The following rule is used by search().
            array('id, title, description, weight, status', 'safe', 'on' => 'search'),
        ));
    }

    /**
    * @return array relational rules.
    */
    public function relations()
    {
        return array_merge(parent::relations(), array(
            'lastActiveThread' => array(self::HAS_ONE, 'Thread', 'roomId', 'order' => 'lastActivityAt DESC'),
            'numThreads' => array(self::STAT, 'Thread', 'roomId'),
            'numReplies' => array(self::STAT, 'Thread', 'roomId', 'join' => 'INNER JOIN reply ON(t.id = reply.threadId)'),
        ));
    }

    /**
    * @return array customized attribute labels (name=>label).
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
            'id' => t('roomLabel', 'Id'),
            'title' => t('roomLabel', 'Aihepiiri'),
            'description' => t('roomLabel', 'Kuvaus'),
            'weight' => t('roomLabel', 'Paino'),
            'status' => t('roomLabel', 'Tila'),
        ));
    }

    /**
    * Retrieves a list of models based on the current search/filter conditions.
    * @return CActiveDataProvider the data provider.
    */
    public function search()
    {
        $criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('weight',$this->weight);
		$criteria->compare('status',$this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    public function createThreadDataProvider()
    {
        $criteria = new CDbCriteria();
        $criteria->distinct = true;
        $criteria->addCondition('roomId=:roomId');
        $criteria->params[':roomId'] = $this->id;
        $criteria->order = 'pinned DESC, lastActivityAt DESC';
        return new CActiveDataProvider('Thread', array(
            'criteria' => $criteria,
        ));
    }

    public function topicColumn()
    {
        $column = Html::tag('h5', array(), l(e($this->title), $this->createUrl()));
        $column .= e($this->description);
        return $column;
    }

    public function latestPostColumn()
    {
        $rows = array();
        $thread = $this->loadLatestPost();
        if ($thread !== null)
        {
            $model = !empty($thread->replies) ? $thread->loadLastPost() : $thread;
            if ($model !== null)
            {
                $rows[] = l(e($thread->subject), $model->createUrl());
                $rows[] = t('roomGrid', '{alias} {timeAgo}', array(
                    '{timeAgo}' => format()->formatTimeAgo($model->createdAt),
                    '{alias}' => '<b>' . e($thread->alias) . '</b>'
                )) . ' ' . l(TbHtml::icon(TbHtml::ICON_FORWARD), $thread->createUrl(array('#' => 'latest-post')));
                return implode('<br>', $rows);
            }
        }
        return '';
    }

    /**
     * @return Thread the model.
     */
    public function loadLatestPost()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition('roomId=:roomId');
        $criteria->params = array(':roomId' => $this->id);
        $criteria->order = 'lastActivityAt DESC';
        return Thread::model()->find($criteria);
    }
}