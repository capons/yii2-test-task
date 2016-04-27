<?php
//require_once('C:/xampp/htdocs/bogdan/yii_test/yii2-test-task/vendor/autoload.php');
use \yii\helpers\Html;
use \yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;
use Stripe\Stripe;
use yii\base\Application;
use yii\BaseYii;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $products common\models\Product[] */
?>
<h1>Your order</h1>

<div class="container-fluid">
    <div class="row">
        <div class="col-xs-4">

        </div>
        <div class="col-xs-2">
            Price
        </div>
        <div class="col-xs-2">
            Quantity
        </div>
        <div class="col-xs-2">
            Cost
        </div>
    </div>
    <?php foreach ($products as $product):?>
    <div class="row">
        <div class="col-xs-4">
            <?= Html::encode($product->title) ?>
        </div>
        <div class="col-xs-2">
            $<?= $product->price ?>
        </div>
        <div class="col-xs-2">
            <?= $quantity = $product->getQuantity()?>
        </div>
        <div class="col-xs-2">
            $<?= $product->getCost() ?>
        </div>
    </div>
    <?php endforeach ?>
    <div class="row">
        <div class="col-xs-8">

        </div>
        <div class="col-xs-2">
            Total: $<?= $total ?>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <?php
            /* @var $form ActiveForm */
            $form = ActiveForm::begin([
                'id' => 'order-form',
            ]) ?>

            <?= $form->field($order, 'phone') ?>
            <?= $form->field($order, 'email') ?>
            <label>Payment</label>
            <?= $form->field($order, 'status')->checkbox(['label' => 'Stripe pay','value'=>'Stripe'])  ?>
            <?= $form->field($order, 'notes')->textarea() ?>

            <div class="form-group row">
                <div class="col-xs-12">
                    <?= Html::submitButton('Buy', ['class' => 'btn btn-primary','id' => 'buy-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end() ?>


           
            <?php
            if(isset($_SESSION['customer'])){
                echo '<pre>';
                print_r($_POST);
                echo '</pre>';
            }

           
            ?>

        </div>
    </div>
</div>