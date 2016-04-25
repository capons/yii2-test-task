<?php
/**
 * Created by PhpStorm.
 * User: Ира
 * Date: 25.04.2016
 * Time: 20:18
 */

namespace common\models;

use Yii;
use yii\rest\Controller;

class Settings extends \yii\db\ActiveRecord
{
    public $crop_text_input;
    public static function tableName()
    {
        return 'settings';
    }
    public function rules()
    {
        return [
            [['crop_text_input'], 'number'],

        ];
    }
    public static function find()
    {
        return parent::find();
    }
}