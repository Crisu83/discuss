<?php

/**
 * This is the model class for table "reply".
 *
 * The followings are the available columns in table 'reply':
 * @property integer $id
 * @property integer $threadId
 * @property string $alias
 * @property string $body
 * @property integer $status
 *
 * The followings are the available relations for table 'comment':
 * @property Thread $thread
 *
 * The following methods are available via WorkflowBehavior:
 * @method changeStatus($newStatus)
 * @method getStatusId()
 * @method getStatusName($id = null)
 * @method getStatusConfig($id = null)
 * @method getStatusOptions()
 * @method getAllowedStatusOptions()
 * @method isTransitionAllowed($oldStatus, $newStatus)
 * @method hasStatus($id)
 */
class Reply extends AuditActiveRecord
{
    const STATUS_REPORTED = -2;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className the class name
	 * @return Reply the static model class
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
		return 'reply';
	}

    /**
     * @return array attached behaviors.
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), array(
            array(
                'class' => 'app.behaviors.WorkflowBehavior',
                'defaultStatus' => self::STATUS_DEFAULT,
                'statuses' => array(
                    self::STATUS_DEFAULT => array(
                        'label' => t('replyStatus', 'Oletus'),
                        'transitions' => array(self::STATUS_REPORTED, self::STATUS_DELETED),
                    ),
                    self::STATUS_REPORTED => array(
                        'label' => t('replyStatus', 'Ilmoitettu'),
                        'transitions' => array(self::STATUS_DEFAULT, self::STATUS_DELETED),
                    ),
                    self::STATUS_DELETED => array(
                        'label' => t('replyStatus', 'Poistettu'),
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
			array('threadId, body', 'required'),
			array('threadId, status', 'numerical', 'integerOnly'=>true),
			array('alias, subject', 'length', 'max'=>255),
			array('threadId, status', 'length', 'max'=>10),
            array('id, threadId, alias, subject, body, status', 'safe', 'on'=>'search'),
		));
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array_merge(parent::relations(), array(
            'thread' => array(self::BELONGS_TO, 'Thread', 'threadId'),
		));
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'id' => t('replyLabel', 'Id'),
            'threadId' => t('replyLabel', 'Aihe'),
			'alias' => t('replyLabel', 'Nimimerkki'),
			'subject' => t('replyLabel', 'Otsikko'),
			'body' => t('replyLabel', 'Viesti'),
			'status' => t('replyLabel', 'Tila'),
		));
	}

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider.
     */
	public function search()
	{
		$criteria = new CDbCriteria;

        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.threadId', $this->threadId);
        $criteria->compare('t.alias', $this->alias, true);
        $criteria->compare('t.body', $this->body, true);
        $criteria->compare('t.status', $this->status, true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
    }

    /**
     * Returns the alias text.
     * @return string the alias.
     */
    public function aliasText()
    {
        return !empty($this->alias)
            ? '<strong class="post-author">' . $this->alias . '</strong>'
            : '<strong class="post-author muted">' . t('reply', 'Nimetön') . '</strong>';
    }

	/**
	 * @return string the URL to this comment.
	 */
	public function createUrl()
	{
		return $this->thread->createUrl(array('#' => 'post-'.$this->id));
	}

    public function buttonToolbar()
    {
        $buttons = array();
        if (!$this->thread->isLocked())
        {
            $buttons[] = TbHtml::linkButton(t('replyButton', 'Lainaa'), array(
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'url' => '#',
                'class' => 'quote-button',
            ));
        }
        if (!user()->isGuest)
        {
            $buttons[] = TbHtml::linkButton(TbHtml::icon('pencil'), array(
                'url' => array('reply/update', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('replyTitle', 'Muokkaa viestiä'),
                'class' => 'reply-button',
            ));
            $buttons[] = TbHtml::linkButton(TbHtml::icon('remove'), array(
                'url' => array('reply/delete', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('replyTitle', 'Poista viesti'),
                'confirm' => t('replyConfirm', 'Oletko varma että haluat poistaa tämän viestin?'),
                'class' => 'reply-button',
            ));
        }
        return implode(' ', $buttons);
    }
}