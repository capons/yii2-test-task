<?php

namespace backend\models;

use common\models\Settings;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;
use yii\db\QueryBuilder;
/**
 * ProductSearch represents the model behind the search form about `common\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id'], 'integer'],
            [['title', 'description','cat_search'], 'safe'],
            [['price','crop_text'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Product::find()->joinWith('category');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category_id' => $this->category_id,
            'price' => $this->price,
        ]);
        $query->andFilterWhere(['like', 'product.title', $this->title])
              ->andFilterWhere(['like', 'description', $this->description])
              ->andFilterWhere(['like', 'category.id', $this->cat_search]);

        $count = Settings::find() //check have settings or no
            ->count();
        if(isset($this->title) && isset($this->cat_search) && isset($this->category_id) && isset($this->price) && isset($this->crop_text)){
            $setting_title = 'crop_description'; // creat settings title for reques
        }
        if($count > 0) {
           // $return_id = Settings::find()->asArray()->all();
            //$return_id[0]['id']
            if(empty($this->crop_text)){ //if result empty -> insert default result
                $crop_count = 20;
            } else {
                $crop_count = $this->crop_text;
            }
            Yii::$app->db->createCommand()
                ->update('settings', ['crop_text' => $crop_count], 'settings_name = "'.$setting_title.'"')
                ->execute();
            return $dataProvider;
        } else {
            $settings = new Settings();
            $settings->crop_text = $this->crop_text;
            $settings->settings_name = $setting_title;
            $settings->save();
            return $dataProvider;
        }
           // return $dataProvider;





    }
}
