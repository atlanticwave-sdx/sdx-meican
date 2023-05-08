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
        <form id="login-form" action="/aaa/login/sendemail" method="GET" role="form" name="emailform">
       <p class="login-box-msg">Verify your Email</p>
        <div class="form-group has-feedback">
          <div class="form-group field-loginform-login required">

<input type="text" id="loginform-login" class="form-control" name="email" placeholder="Email" required>
<input type="hidden" name="id" value="<?php echo $orcid_id; ?>">
<input type="hidden" name="name" value="<?php echo $orcid_name; ?>">

<p class="help-block help-block-error"></p>
</div>          <span class="fa fa-user form-control-feedback"></span>
        </div>
       
                <div class="row">
          
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary form-control">Submit</button>          </div>
        </div>
                </form>    </div>
    <!-- /.login-box-body -->
</div>
