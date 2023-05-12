<?php
/**
 * @copyright Copyright (c) 2012-2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;

\meican\aaa\assets\Login::register($this);

?>

<div class="login-box">
    <div class="login-logo">
    <?= Html::img("@web/images/meican_new.png", ['style'=>'width: 240px;','title' => 'MEICAN']); ?>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        
       <p class="login-box-msg">We sent you an email to confirm your account. please complete verification.</p>
           </div>
    <!-- /.login-box-body -->
</div>
