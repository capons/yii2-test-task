<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\OrderItem;
use common\models\Product;
use common\models\Payments;
use yii\web\Session;
use yz\shoppingcart\ShoppingCart;
use yii\helpers\Url;
use Stripe\Stripe;

class CartController extends \yii\web\Controller
{
    public function actionAdd($id)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->put($product);
            return $this->goBack();
        }
    }

    public function actionList()
    {
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;

        $products = $cart->getPositions();
        $total = $cart->getCost();

        return $this->render('list', [
           'products' => $products,
           'total' => $total,
        ]);
    }

    public function actionRemove($id)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->remove($product);
            $this->redirect(['cart/list']);
        }
    }

    public function actionUpdate($id, $quantity)
    {
        $product = Product::findOne($id);
        if ($product) {
            \Yii::$app->cart->update($product, $quantity);
            $this->redirect(['cart/list']);
        }
    }

    public function actionOrder()
    {
        $order = new Order();
        $payment = New Payments();
        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;
        /* @var $products Product[] */
        $products = $cart->getPositions();
        $total = $cart->getCost();
        if ($order->load(\Yii::$app->request->post()) && $order->validate()) {
            if(!empty($_POST['Order']['status'])){ //create order with Stripe pay
                //save customer info to session
                $_SESSION['customer']['phone'] = $_POST['Order']['phone'];
                $_SESSION['customer']['email'] = $_POST['Order']['email'];
                $_SESSION['customer']['notes'] = $_POST['Order']['notes'];
                return $this->render('pay',[ //redirect to pat page
                    'products' => $products,
                    'total' => $total,
                ]);
            } else { //create order without pay
                $transaction = $order->getDb()->beginTransaction();
                $order->save(false);

                foreach ($products as $product) {
                    $orderItem = new OrderItem();
                    $orderItem->order_id = $order->id;
                    $orderItem->title = $product->title;
                    $orderItem->price = $product->getPrice();
                    $orderItem->product_id = $product->id;
                    $orderItem->quantity = $product->getQuantity();
                    if (!$orderItem->save(false)) {
                        $transaction->rollBack();
                        \Yii::$app->session->addFlash('error', 'Cannot place your order. Please contact us.');
                        return $this->redirect('catalog/list');
                    }
                }
                $transaction->commit();
                \Yii::$app->cart->removeAll();
                \Yii::$app->session->addFlash('success', 'Thanks for your order. We\'ll contact you soon.');
                $order->sendEmail();
                return $this->redirect('catalog/list');
            }
        }

        //pay condition
        if (isset($_POST['stripeToken'])) {
            //try {
            //start pay by object Stripe
            Stripe::setApiKey("sk_test_Pmtiqut8msdIXyyZqGniDvBy"); //my api key
            $token = $_POST['stripeToken'];
            $customer = \Stripe\Customer::create(array(
                'email' => $_POST['stripeEmail'],
                'card' => $token
            ));
            \Stripe\Charge::create(array(
                'customer' =>$customer->id,
                'amount' => $total,
                'currency' => 'usd'
            ));
            $transaction = $order->getDb()->beginTransaction();
            $order -> phone = $_SESSION['customer']['phone'];
            $order -> email = $_SESSION['customer']['email'];
            $order -> notes = $_SESSION['customer']['notes'];
            $order -> pay = 'pay'; // set new status
            $order->save(false);
            unset($_SESSION['customer']); //unset customer session
            $payment->order_id = $order->id;
            $payment->payment_id = $customer->id;
            $payment->payment_token = $_POST['stripeToken'];
            $payment->payment_method = $_POST['stripeTokenType'];
            $payment->email = $_POST['stripeEmail'];
            $payment->save(); //save payments information
            foreach ($products as $product) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->title = $product->title;
                $orderItem->price = $product->getPrice();
                $orderItem->product_id = $product->id;
                $orderItem->quantity = $product->getQuantity();
                if (!$orderItem->save(false)) {
                    $transaction->rollBack();
                    \Yii::$app->session->addFlash('error', 'Cannot place your order. Please contact us.');
                    return $this->redirect('catalog/list');
                }
            }
            $transaction->commit();
            \Yii::$app->cart->removeAll();

            \Yii::$app->session->addFlash('success', 'Thanks for your order. We\'ll contact you soon.');
            $order->sendEmail();

            return $this->redirect('catalog/list');
            $success = 1;
            $paymentProcessor="Credit card (www.stripe.com)";
            /*
        } catch(Stripe\CardError $e) {
            $error1 = $e->getMessage();
        } catch (Stripe\InvalidRequestError $e) {
            // Invalid parameters were supplied to Stripe's API
            $error2 = $e->getMessage();
        } catch (Stripe\AuthenticationError $e) {
            // Authentication with Stripe's API failed
            $error3 = $e->getMessage();
        } catch (Stripe\ApiConnectionError $e) {
            // Network communication with Stripe failed
            $error4 = $e->getMessage();
        } catch (Stripe\Error $e) {
            // Display a very generic error to the user, and maybe send
            // yourself an email
            $error5 = $e->getMessage();
        } catch (Exception $e) {
            // Something else happened, completely unrelated to Stripe
            $error6 = $e->getMessage();
        }

        if ($success!=1)
        {
            $_SESSION['error1'] = $error1;
            $_SESSION['error2'] = $error2;
            $_SESSION['error3'] = $error3;
            $_SESSION['error4'] = $error4;
            $_SESSION['error5'] = $error5;
            $_SESSION['error6'] = $error6;
            header('Location: checkout.php');
            exit();
        }
            */
        }



        return $this->render('order', [
            'order' => $order,
            'products' => $products,
            'total' => $total,
        ]);



    }
    public function actionPay(){

        /* @var $cart ShoppingCart */
        $cart = \Yii::$app->cart;
        $products = $cart->getPositions();
        $total = $cart->getCost();
        $pay = '';
        return $this->render('pay',[
            'products' => $products,
            'total' => $total,
            'pay' => $pay,
        ]);
    }
}
