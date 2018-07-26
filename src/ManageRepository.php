<?php

namespace maximniko\manage;

use yii\db\ActiveRecord;
use yii\helpers\StringHelper;
use yii\web\NotFoundHttpException;

/**
 * Class ManageRepository
 * @package common\Repositories\Crud
 */
abstract class ManageRepository
{
    /* @var ActiveRecord $recordClass */
    protected $recordClass;

    /**
     * @param mixed $id or $condition
     * @return null|ActiveRecord
     * @throws NotFoundHttpException
     */
    public function get($id)
    {
        if (!$record = $this->recordClass::findOne($id)) {
            throw new NotFoundHttpException(StringHelper::basename($this->recordClass) . ' is not found.');
        }
        return $record;
    }

    /**
     * @param ActiveRecord $record
     */
    public function save(ActiveRecord $record)
    {
        /* @var ActiveRecord $record */
        if (!$record->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

    /**
     * @param ActiveRecord $record
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function remove(ActiveRecord $record)
    {
        if (!$record->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }
}