<?php
/**
 * Created by PhpStorm.
 * User: Maxim
 * Date: 08.04.2018
 * Time: 20:59
 */

namespace common\Services;


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
    public function wrap(callable $callback)
    {
        $transaction = \Yii::$app->db->beginTransaction();
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