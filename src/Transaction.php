<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 08.04.2018
 * Time: 20:59
 */

namespace maximniko\manage;


/**
 * Class Transaction
 * @package common\Services
 */
class Transaction
{

    /**
     * @param callable $callback
     * @throws \yii\db\Exception
     * @throws \Throwable
     */
    public function wrap(callable $callback, ?\yii\db\Connection $connect = null)
    {
        $connection = $connect ?: \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $callback();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
        }
    }
}