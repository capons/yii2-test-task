<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\Category;
use yii\helpers\ArrayHelper;
use common\models\Settings;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Products';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Product', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'title',
            //'description',   //:ntext
            [
                'attribute' => 'description',
                'value' =>
                    function ($res) { //show product description and only 20 latter remaining
                        $count = Settings::find() //check have settings or no
                        ->count();
                        if($count > 0) {
                            $settings = Settings::find()->asArray()->all();
                            $convert_sring = substr($res->description, 0, $settings[0]['crop_text']) . '...'; //crop text
                            return $convert_sring;
                        } else {
                            return $res->description;
                        }
                    },
                'filter' => Html::activeDropDownList($searchModel, 'cat_search', ArrayHelper::map(Category::find()->all(), 'id', 'title'),['class'=>'form-control','prompt' => 'Select Category']),
            ],
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return empty($model->category_id) ? '-' : $model->category->title;
                },
            ],
            'price',
            [
                'attribute' => 'cat_search',
                'value' =>
                    function ($res) { //show product category
                        return empty($res->category_id) ? '-' : $res->category->title;
                    },
                'filter' => Html::activeDropDownList($searchModel, 'cat_search', ArrayHelper::map(Category::find()->all(), 'id', 'title'),['class'=>'form-control','prompt' => 'Select Category']),
            ],
            [
                'attribute' => 'crop_text',
                 'value' => function(){
                     return 'test';
                 },


            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {images} {delete}',
                'buttons' => [
                    'images' => function ($url, $model, $key) {
                         return Html::a('<span class="glyphicon glyphicon glyphicon-picture" aria-label="Image"></span>', Url::to(['image/index', 'id' => $model->id]));
                    }
                ],
            ],
        ],
    ]);
    ?>

</div>
