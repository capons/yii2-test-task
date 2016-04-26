<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ruskid\stripe\StripeCheckout;
use Stripe\Stripe;

?>
<?php
/*
$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
]) ?>
<?= $form->field($model, 'username') ?>
<?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Login', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end() ;
*/
?>

<?php
if (isset($_POST['Order']['status'])){

    echo StripeCheckout::widget([
        'action' => '',
        'name' => 'Pay',
        'description' => count($products).' widgets ("'.$total.'")',
        'amount' => $total,
    ]);



} else {
    //return $this->redirect(['catalog/list']);
    ?><p>Payment view</p><?php
}
?>