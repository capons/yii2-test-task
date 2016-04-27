<?php
use yii\helpers\Url;
use ruskid\stripe\StripeCheckout; //load Stripe
?>
<?php
if (isset($_SESSION['customer'])){
    echo StripeCheckout::widget([
        'action' => '',  //redirect to (In our case, redirect works on URL where payment began from)
        'name' => 'Pay',
        'description' => count($products).'widgets ("'.$total.'")',
        'amount' => $total,
    ]);

} else {
    return Yii::$app->response->redirect(Url::to(['catalog/list']));
}
?>