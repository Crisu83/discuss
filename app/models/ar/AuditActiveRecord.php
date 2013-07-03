<?php

/**
 * @property string $createdAt
 * @property string $updatedAt
 * @property string $deletedAt
 */
class AuditActiveRecord extends ActiveRecord
{
    /**
     * Returns a list of behaviors that this model should behave as.
     * @return array the behavior configurations (behavior name=>behavior configuration)
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), array(
            'audit' => array(
                'class' => 'app.behaviors.AuditBehavior',
            )
        ));
    }
}