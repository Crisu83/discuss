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
                'class' => 'SeoActiveRecordBehavior',
                'route' => 'thread/view',
                'params' => array(
                    'id' => function($data) {
                        return $data->id;
                    },
                    'name' => function($data) {
                        return strtolower($data->subject);
                    },
                    'room' => function($data) {
                        return strtolower(v($data, 'room.title'));
                    },
                ),
            ),
            array(
                'class' => 'app.behaviors.WorkflowBehavior',
                'defaultStatus' => self::STATUS_DEFAULT,
                'statuses' => array(
                    self::STATUS_DEFAULT => array(
                        'label' => t('threadStatus', 'Default'),
                        'transitions' => array(self::STATUS_REPORTED, self::STATUS_DELETED),
                    ),
                    self::STATUS_REPORTED => array(
                        'label' => t('threadStatus', 'Reported'),
                        'transitions' => array(self::STATUS_DEFAULT, self::STATUS_DELETED),
                    ),
                    self::STATUS_DELETED => array(
                        'label' => t('threadStatus', 'Deleted'),
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
			'id' => t('threadLabel', 'Id'),
			'roomId' => t('threadLabel', 'Aihealue'),
			'alias' => t('threadLabel', 'Nimimerkki'),
			'subject' => t('threadLabel', 'Otsikko'),
			'body' => t('threadLabel', 'Viesti'),
			'pinned' => t('threadLabel', 'Kiinnitetty'),
			'locked' => t('threadLabel', 'Lukittu'),
			'lastActivityAt' => t('threadLabel', 'Viimeisin viesti'),
			'status' => t('threadLabel', 'Tila'),
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

    public function loadLastPost()
    {
        return Reply::model()->find($this->createLastPostCriteria());
    }

	/**
	 * Renders the link to this topic.
	 * @return string the generated column.
	 */
	public function subjectColumn()
	{
        $heading = $this->renderIcons() . l($this->subject, $this->getUrl());
		$column = Html::tag('h5', array(), $heading);
        $column .= TbHtml::mutedSpan(t('threadGrid', 'Aloittaja {alias} {dateTime}', array(
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
            $icons[] = l(TbHtml::icon('pushpin'), '#', array('rel' => 'tooltip', 'title' => t('threadTitle', 'Tämä aihe on kiinnitetty')));
        if ($this->locked)
            $icons[] = l(TbHtml::icon('lock'), '#', array('rel' => 'tooltip', 'title' => t('threadTitle', 'Tämä aihe on lukittu')));
        return !empty($icons) ? implode(' ', $icons) . ' ' : '';
	}

    public function buttonToolbar()
    {
        $buttons = array();
        $buttons[] = TbHtml::linkButton(t('threadButton', 'Lainaa'), array(
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'url' => '#',
            'class' => 'quote-button thread-button',
        ));
        if (!user()->isGuest)
        {
            $buttons[] = TbHtml::linkButton(TbHtml::icon('pencil'), array(
                'url' => array('update', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('threadTitle', 'Muokkaa aihetta'),
                'class' => 'thread-button',
            ));
            $buttons[] = TbHtml::linkButton(TbHtml::icon('remove'), array(
                'url' => array('delete', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('threadTitle', 'Poista aihe'),
                'confirm' => t('threadConfirm', 'Oletko varma että haluat poistaa tämän aiheen?'),
                'class' => 'thread-button',
            ));
        }
        return implode(' ', $buttons);
    }

    /**
     * Returns the alias text.
     * @return string the alias.
     */
    public function aliasText()
    {
        return !empty($this->alias)
            ? '<span class="alias">' . $this->alias . '</span>'
            : '<span class="alias muted">' . t('reply', 'Nimetön') . '</span>';
    }

    public function lastPostColumn()
    {
        $reply = $this->loadLastPost();
        $model = $reply !== null ? $reply : $this;
        $column = t('threadGrid', '{alias} {timeAgo}', array(
            '{timeAgo}' => format()->formatTimeAgo($model->createdAt),
            '{alias}' => '<b>' . $model->aliasText() . '</b>',
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
        return $criteria;
    }

    public function createRepliesCriteria()
    {
        $criteria = $this->createReplyCriteria();
        $criteria->order = 'audit_model.created ASC';
        return $criteria;
    }

    public function createLastPostCriteria()
    {
        $criteria = $this->createReplyCriteria();
        $criteria->order = 'audit_model.created DESC';
        $criteria->limit = 1;
        return $criteria;
    }
}