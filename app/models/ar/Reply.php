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
			'id' => t('label', 'Id'),
            'threadId' => t('label', 'Thread'),
			'alias' => t('label', 'Alias'),
			'body' => t('label', 'Body'),
			'status' => t('label', 'Status'),
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
            ? '<span class="alias">' . $this->alias . '</span>'
            : '<span class="alias muted">' . t('reply', 'Anonymous') . '</span>';
    }

	/**
	 * @return string the URL to this comment.
	 */
	public function getUrl()
	{
		return $this->thread->getUrl(array('#' => 'post-'.$this->id));
	}

    public function buttonToolbar()
    {
        $buttons = array();
        $buttons[] = TbHtml::linkButton(t('replyButton', 'Quote'), array(
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'url' => '#',
            'class' => 'reply-button',
        ));
        if (!user()->isGuest)
        {
            $buttons[] = TbHtml::linkButton(TbHtml::icon('pencil'), array(
                'url' => array('reply/update', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('replyTitle', 'Edit post'),
                'class' => 'reply-button',
            ));
            $buttons[] = TbHtml::linkButton(TbHtml::icon('remove'), array(
                'url' => array('reply/delete', 'id' => $this->id),
                'rel' => 'tooltip',
                'title' => t('replyTitle', 'Delete post'),
                'class' => 'reply-button',
            ));
        }
        return implode(' ', $buttons);
    }
}