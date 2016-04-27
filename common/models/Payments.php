<?php
namespace common\models;

use Yii;
class Payments extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'payments';
    }
    public function getOrder() //relation with category model
    {
        return $this->hasMany(Order::className(), ['id' => 'order_id']);
    }
}