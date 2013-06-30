<?php

/**
 * This is the model class for table "thread".
 *
 * The followings are the available columns in table 'thread':
 * @property integer $id
 * @property integer $roomId
 * @property string $alias
 * @property string $subject
 * @property string $body
 * @property integer $pinned
 * @property integer $locked
 * @property string $lastActivityAt
 * @property integer $status
 *
 * The followings are the available relations for table 'thread':
 * @property Room $room
 * @property Reply[] $replies
 * @property integer $numReplies
 * @property integer $numViews
 */
class Thread extends AuditActiveRecord
{
    const STATUS_REPORTED = -2;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className the class name
	 * @return Thread the static model class
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
		return 'thread';
	}

    /**
     * @return array attached behaviors.
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), array(
            array(
                'class'=>'vendor.crisu83.yii-seo.behaviors.SeoActiveRecordBehavior',
                'route'=>'thread/view',
                'params'=>array('id'=>$this->id, 'name'=>$this->subject),
            ),
            array(
                'class'=>'app.behaviors.WorkflowBehavior',
                'defaultStatus'=>self::STATUS_DEFAULT,
                'statuses'=>array(
                    self::STATUS_DEFAULT => array(
                        'label'=>t('topic','Default'),
                        'transitions'=>array(self::STATUS_REPORTED, self::STATUS_DELETED),
                    ),
                    self::STATUS_REPORTED => array(
                        'label'=>t('topic','Reported'),
                        'transitions'=>array(self::STATUS_DEFAULT, self::STATUS_DELETED),
                    ),
                    self::STATUS_DELETED => array(
                        'label'=>t('topic','Deleted'),
                    ),
                ),
            )
        ));
    }

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('roomId, alias, subject, body, status', 'required'),
			array('roomId, pinned, locked, status', 'numerical', 'integerOnly'=>true),
			array('alias, subject', 'length', 'max'=>255),
			array('roomId, status', 'length', 'max'=>10),
            array('lastActivityAt', 'safe'),
            array('id, roomId, alias, subject, body, pinned, locked, status', 'safe', 'on'=>'search'),
		));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array_merge(parent::relations(), array(
            'room' => array(self::BELONGS_TO, 'Room', 'roomId'),
            'replies' => array(self::HAS_MANY, 'Reply', 'threadId'),
            'numReplies' => array(self::STAT, 'Reply', 'threadId'),
            'numViews' => array(self::STAT, 'Statistic', 'modelId',
                'condition' => 'model=:model AND action=:action',
                'params' => array(
                    ':model' => __CLASS__,
                    ':action' => Statistic::ACTION_VIEW,
                )
            ),
		));
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'id' => t('label', 'Id'),
			'roomId' => t('label', 'Room'),
			'alias' => t('label', 'Alias'),
			'subject' => t('label', 'Subject'),
			'body' => t('label', 'Body'),
			'pinned' => t('label', 'Pinned'),
			'locked' => t('label', 'Locked'),
			'lastActivityAt' => t('label', 'Last activity at'),
			'status' => t('label', 'Status'),
		));
	}

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider.
     */
	public function search()
	{
		$criteria=new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.roomId', $this->roomId);
        $criteria->compare('t.alias', $this->alias, true);
        $criteria->compare('t.subject', $this->subject, true);
        $criteria->compare('t.pinned', $this->pinned);
        $criteria->compare('t.locked', $this->locked);
        $criteria->compare('t.lastActivityAt', $this->lastActivityAt, true);
        $criteria->compare('t.status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
		));
	}

    public function loadLatestPost()
    {
        return Reply::model()->find($this->createReplyCriteria());
    }

	/**
	 * Renders the link to this topic.
	 * @return string the generated column.
	 */
	public function subjectColumn()
	{
        $heading = $this->renderIcons() . l($this->subject, $this->getUrl());
		$column = Html::tag('h5', array(), $heading);
        $column .= TbHtml::mutedSpan(t('threadGrid', 'Started by {alias} – {dateTime}', array(
            '{alias}' => '<b>' . $this->alias . '</b>',
            '{dateTime}' => dateFormatter()->formatDateTime(strtotime($this->createdAt), 'long', 'short'),
        )));
        return $column;
	}

	/**
	 * Renders the icons for this thread.
	 * @return string the icons.
	 */
	public function renderIcons()
	{
		$icons = array();
        if ($this->pinned)
            $icons[] = l(TbHtml::icon('pushpin'), '#', array('rel' => 'tooltip', 'title' => t('iconTitle', 'This thread is pinned')));
        if ($this->locked)
            $icons[] = l(TbHtml::icon('lock'), '#', array('rel' => 'tooltip', 'title' => t('iconTitle', 'This thread is locked')));
        return implode(' ', $icons) . ' ';
	}

	/**
	 * Renders the stats column.
	 * @return string the generated column.
	 */
	public function statsColumn()
	{
        $rows = array();
		$rows[] = t('topic', '{n} Reply|{n} Replies', array($this->numReplies, '{n}' => '<b>' . $this->numReplies . '</b>'));
		$rows[] = t('topic', '{n} View|{n} Views', array($this->numViews, '{n}' => '<b>' . $this->numViews . '</b>'));
		return implode('<br>', $rows);
	}

    public function buttonToolbar()
    {
        $buttons = array();
        $buttons[] = TbHtml::linkButton(t('threadButton', 'Quote'), array(
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'url' => '#',
            'class' => 'thread-button',
        ));
        if (!user()->isGuest)
        {
            $buttons[] = TbHtml::linkButton(TbHtml::icon('pencil'), array(
                'url' => array('update', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('threadTitle', 'Edit thread'),
                'class' => 'thread-button',
            ));
            $buttons[] = TbHtml::linkButton(TbHtml::icon('remove'), array(
                'url' => array('delete', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('threadTitle', 'Delete thread'),
                'class' => 'thread-button',
            ));
        }
        return implode(' ', $buttons);
    }

    public function latestPostColumn()
    {
        $reply = $this->loadLatestPost();
        $model = $reply !== null ? $reply : $this;
        $column = t('threadGrid', '{timeAgo} by {alias}', array(
            '{timeAgo}' => format()->formatTimeAgo($model->createdAt),
            '{alias}' => '<b>' . $model->alias . '</b>',
        ));
        $column .= ' ' . l(TbHtml::icon(TbHtml::ICON_FORWARD), $model->getUrl());
        return $column;
    }

    public function createReplyCriteria()
    {
        $criteria = new CDbCriteria();
        $criteria->join = 'INNER JOIN audit_model ON (t.id=modelId AND action=:action AND model=:model)';
        $criteria->addCondition('t.threadId=:threadId');
        $criteria->params = array(
            ':action' => AuditModel::ACTION_CREATE,
            ':model' => 'Reply',
            ':threadId' => $this->id,
        );
        $criteria->order = 'audit_model.created ASC';
        return $criteria;
    }
}