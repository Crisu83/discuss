<?php
/**
 * File class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package crisu83.yii-filemanager.models
 */

/**
 * This is the model class for table "file".
 *
 * The followings are the available columns in table 'file':
 * @property string $id
 * @property string $name
 * @property string $extension
 * @property string $path
 * @property string $filename
 * @property string $mimeType
 * @property string $byteSize
 * @property string $createdAt
 */
class File extends ActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return File the static model class
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
		return 'file';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, extension, filename, mimeType, byteSize, createdAt', 'required'),
			array('name, path, extension, filename, mimeType', 'length', 'max' => 255),
			array('byteSize', 'length', 'max' => 10),
			// The following rule is used by search().
			array('id, name, path, extension, filename, mimeType, byteSize, createdAt', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('fileLabel', 'ID'),
			'name' => Yii::t('fileLabel', 'Nimi'),
			'path' => Yii::t('fileLabel', 'Polku'),
			'extension' => Yii::t('fileLabel', 'Pääte'),
			'filename' => Yii::t('fileLabel', 'Tiedostonimi'),
			'mimeType' => Yii::t('fileLabel', 'Mime tyyppi'),
			'byteSize' => Yii::t('fileLabel', 'Bitti koko'),
			'createdAt' => Yii::t('fileLabel', 'Luotu'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, true);
		$criteria->compare('path', $this->path, true);
		$criteria->compare('extension', $this->extension, true);
		$criteria->compare('filename', $this->filename, true);
		$criteria->compare('mimeType', $this->mimeType, true);
		$criteria->compare('byteSize', $this->byteSize, true);
		$criteria->compare('createdAt', $this->createdAt, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	/**
	 * Returns the full filename for this file.
	 * @return string the filename.
	 */
	public function resolveFilename()
	{
		return $this->name . '-' . $this->id . '.' . $this->extension;
	}

	/**
	 * Returns the relative path for this file.
	 * @return string the path.
	 */
	public function resolvePath()
	{
		return $this->path !== null ? $this->path . '/' : '';
	}

	/**
	 * Returns the full path with the filename for this file.
	 * @return string the path.
	 */
	public function resolveFilePath()
	{
		return $this->resolvePath() . $this->resolveFilename();
	}
}