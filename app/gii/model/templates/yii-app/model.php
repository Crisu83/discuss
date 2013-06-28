<?php
/**
 * This is the template for generating the model class of a specified table.
 *
 * @var ModelCode $this the ModelCode object.
 * @var string $tableName the table name for this class (prefix is already removed if necessary).
 * @var string $modelClass the model class name.
 * @var string $connectionId the database component id.
 * @var array $columns list of table columns (name=>CDbColumnSchema).
 * @var array $labels list of attribute labels (name=>label).
 * @var array $rules: list of validation rules.
 * @var array $relations list of relations (name=>relation declaration)
 */
?>
<?php echo "<?php\n"; ?>

/**
* This is the model class for table "<?php echo $tableName; ?>".
*
* The followings are the available columns:
<?php foreach($columns as $column): ?>
* @property <?php echo $column->type.' $'.$column->name."\n"; ?>
<?php endforeach; ?>
<?php if(!empty($relations)): ?>
*
* The followings are the available relations:
<?php foreach($relations as $name=>$relation): ?>
* @property <?php
    if (preg_match("~^array\(self::([^,]+), '([^']+)', '([^']+)'\)$~", $relation, $matches))
    {
        $relationType = $matches[1];
        $relationModel = $matches[2];

        switch($relationType){
            case 'HAS_ONE':
                echo $relationModel.' $'.$name."\n";
                break;
            case 'BELONGS_TO':
                echo $relationModel.' $'.$name."\n";
                break;
            case 'HAS_MANY':
                echo $relationModel.'[] $'.$name."\n";
                break;
            case 'MANY_MANY':
                echo $relationModel.'[] $'.$name."\n";
                break;
            default:
                echo 'mixed $'.$name."\n";
        }
    }
    ?>
<?php endforeach; ?>
<?php endif; ?>
*/
class <?php echo $modelClass; ?> extends <?php echo $this->baseClass."\n"; ?>
{
    /**
    * Returns the static model of the specified AR class.
    * @param string $className active record class name.
    * @return <?php echo $modelClass; ?> the static model class.
    */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
<?php if($connectionId!='db'):?>

    /**
    * @return CDbConnection database connection
    */
    public function getDbConnection()
    {
        return Yii::app()-><?php echo $connectionId ?>;
    }
<?php endif?>

    /**
    * @return string the associated database table name.
    */
    public function tableName()
    {
        return '<?php echo $tableName; ?>';
    }

    /**
    * @return array the behavior configurations (name=>config).
    */
    public function behaviors()
    {
        return array_merge(parent::rules(), array(
        ));
    }

    /**
    * @return array validation rules for model attributes.
    */
    public function rules()
    {
        return array_merge(parent::rules(), array(
<?php foreach($rules as $rule): ?>
            <?php echo $rule.",\n"; ?>
<?php endforeach; ?>
            // The following rule is used by search().
            array('<?php echo implode(', ', array_keys($columns)); ?>', 'safe', 'on' => 'search'),
        ));
    }

    /**
    * @return array relational rules.
    */
    public function relations()
    {
        return array_merge(parent::relations(), array(
<?php foreach($relations as $name=>$relation): ?>
            <?php echo "'$name' => $relation,\n"; ?>
<?php endforeach; ?>
        ));
    }

    /**
    * @return array customized attribute labels (name=>label).
    */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
<?php foreach($labels as $name=>$label): ?>
            <?php echo "'$name' => t('label', '$label'),\n"; ?>
<?php endforeach; ?>
        ));
    }

    /**
    * Retrieves a list of models based on the current search/filter conditions.
    * @return CActiveDataProvider the data provider.
    */
    public function search()
    {
        $criteria=new CDbCriteria;

<?php
foreach ($columns as $name => $column)
{
    if($column->type==='string')
        echo "\t\t\$criteria->compare('$name', \$this->$name, true);\n";
    else
        echo "\t\t\$criteria->compare('$name', \$this->$name);\n";
}
?>

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }
}