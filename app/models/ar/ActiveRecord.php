<?php

class ActiveRecord extends CActiveRecord
{
	// Default active record statuses.
	const STATUS_DELETED = -1;
	const STATUS_DEFAULT = 0;

	/**
	 * Returns the default named scope that should be implicitly applied to all queries for this model.
	 * @return array the query criteria.
	 */
	public function defaultScope()
	{
		$scope = parent::defaultScope();
		if ($this->hasAttribute('status'))
		{
			$tableAlias = $this->getTableAlias(true, false/* do not check scopes */);
			$condition = $tableAlias . '.status >= 0';
			if (isset($scope['condition']))
			{
				if (strpos($scope['condition'], 'status') === false)
					$scope['condition'] = '(' . $scope['condition'] . ') AND (' . $condition . ')';
			}
			else
				$scope['condition'] = $condition;
		}
		return $scope;
	}
}

