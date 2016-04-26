<?php
use yii\helpers\Url;
use ruskid\stripe\StripeCheckout;

?>


<?php
if (isset($_POST['Order']['status'])){

    echo StripeCheckout::widget([
        'action' => '"'.Yii::$app->request->baseUrl.'"',
        'name' => 'Pay',
        'description' => count($products).'widgets ("'.$total.'")',
        'amount' => $total,
    ]);

} else {
    //return $this->redirect(['catalog/list']);
    ?><p>Payment view</p><?php
}
?>