<?php
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');

Yii::setAlias('frontendWebroot', 'http://shop.local/');
Yii::setAlias('backendWebroot', 'http://admin.shop.local/');
Yii::setAlias('@basepath', 'http://localhost/bogdan/yii_test/yii2-test-task/frontend/web/index.php'); //web bath path