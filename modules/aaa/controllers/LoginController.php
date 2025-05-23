<?php
/**
 * @copyright Copyright (c) 2012-2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

namespace meican\aaa\controllers;

use Yii;
use yii\helpers\Url;

use meican\base\BaseController;
use meican\aaa\models\User;
use meican\aaa\models\Group;
use meican\base\models\Preference;
use meican\aaa\forms\LoginForm;
use meican\aaa\forms\CafeUserForm;
use meican\aaa\forms\ForgotPasswordForm;
use meican\aaa\models\AaaPreference;

/**
 * @author Maurício Quatrin Guerreiro @mqgmaster
 */

/* Controller for managing Authentication and ORCID auhtorization in MEICAN*/
class LoginController extends BaseController {
    
    public $layout = 'login-layout';
    //VERIFICAR
    public $enableCsrfValidation = false;

    public function actionAdmin() { // route for controlling MEICAN Admin authentication
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        $model = new LoginForm;
        
        if($model->load($_POST)) {
            if($model->login()) {
                return $this->goHome();
            }
        }
            
        return $this->render('index', array(
            'model'=>$model,
            'federation' => AaaPreference::isFederationEnabled(),
        ));
    }

    public function actionVerifyemail(){ //route for verifying emails new ORCID user signups on MEICAN

        $registration_token = $_GET['token'];
    
        $user=User::findByRegistrationToken($registration_token);
        if(!empty($user)){
            $email=$user->email;
            $user->is_active=1;
            $userId=$user->id;
            try {
                Yii::$app->db->createCommand()->insert('meican_user_domain', [
                    'id' => $userId,
                    'user_id' => $userId,
                ])->execute();
            } catch (\yii\db\Exception $e) {
                echo "Insert failed: " . $e->getMessage();
            }
            try {
                Yii::$app->db->createCommand()->insert('meican_auth_assignment', [
                    'item_name' => 'g10',
                    'user_id' => $userId,
                ])->execute();
            } catch (\yii\db\Exception $e) {
                echo "Insert failed: " . $e->getMessage();
            }
             try {
                Yii::$app->db->createCommand()->insert('meican_user_topology_domain', [
                    'id' => $userId,
                    'user_id' => $userId,
                    'domain' => 'ampath.net,sax.net,tenet.ac.za',
                ])->execute();
            } catch (\yii\db\Exception $e) {
                echo "Insert failed: " . $e->getMessage();
            }

            if($user->save()){
                $mail=Yii::$app->mailer->compose()
                ->setFrom('meican.sdx@gmail.com')
                ->setTo($email)
                ->setSubject('MEICAN email verification successfull')
                ->setTextBody('Plain text content')
                ->setHtmlBody('Your email has been successfully verified.')
                ->send();
                $this->redirect(array('index'));
                }
            }

    }

    public function actionSendemail(){ //route for sending verification emails to new ORCID user signups on MEICAN

        
        $base_url=MEICAN_URL;
        $userId=$_GET['id'];
        
       if(isset($_GET['email'])){
        
           $email=$_GET['email'];
           $user = User::findOne($userId);
           $registration_token=$user->registration_token;
            $mail=Yii::$app->mailer->compose()
           ->setFrom('meican.sdx@gmail.com')
           ->setTo($email)
           ->setSubject('Verify your MEICAN email')
           ->setTextBody('Plain text content')
           ->setHtmlBody('<b>Verify your email</b><p>Click on the link below to verify your MEICAN email</p>
            <a href="https://'.$base_url.'/aaa/login/verifyemail?token='.$registration_token.'">https://'.$base_url.'/aaa/login/verifyemail?token='.$registration_token.'</a>')
           ->send();
           if($mail){
                 echo "<script>alert('Email Sent successfully');</script>";
                }
           else{
                 foreach ($user->getErrors() as $key => $value) {
                    echo "<script>alert('$value[0]');</script>";
                }
                }
           }
        return $this->render('email-form',array('user_id'=>$userId));

    }
    
    public function actionIndex() { //redirect users to ORCID login if they are not logged in with their ORCID credentials

        $model = new LoginForm;
        $base_url=MEICAN_URL;
    	
        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      	if (strpos($actual_link,'code') !== false) {
          $code=$_GET['code'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://orcid.org/oauth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'client_id='.ORCID_CLIENT_ID.'&client_secret='.ORCID_CLIENT_SECRET.'&grant_type=authorization_code&redirect_uri=https%3A%2F%2F'.$base_url.'%2Faaa%2Flogin&code='.$code.'',
        CURLOPT_HTTPHEADER => array(
          'Accept: application/json',
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: X-Mapping-fjhppofk=C9BF87EDFB3F654BC98D87552A592F57'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      $response_arr=json_decode($response,true);
      $orcid_id=$response_arr['orcid'];
      $name=$response_arr['name'];
      $user=User::findByUsername($orcid_id);
      if(!empty($user)){
      $duration = 3600*24; // one day
      Yii::$app->user->login($user, $duration);
      header("Location: https://$base_url/circuits/nodes/show");
        exit(); 
        }
        else{
        $email='test@test.com';
        $user = new User;
        $user->login = $orcid_id;
        $user->authkey = Yii::$app->getSecurity()->generateRandomString();
        $user->password = Yii::$app->getSecurity()->generatePasswordHash('test');
        $user->language = 'en-US';
        $user->date_format = 'dd/MM/yyyy';
        $user->time_zone = 'HH:mm';
        $user->time_format = 'New_York';
        $user->name = $name;
        $user->email = $email;
        $registration_token=Yii::$app->getSecurity()->generateRandomString();
        $user->registration_token=$registration_token;
        $user->save();
        header("Location: https://orcid.org/oauth/authorize?client_id=".ORCID_CLIENT_ID."&response_type=code&scope=/authenticate&redirect_uri=https://$base_url/aaa/login");
        exit();

        }
      
      } 

      else {
          header("Location: https://orcid.org/oauth/authorize?client_id=".ORCID_CLIENT_ID."&response_type=code&scope=/authenticate&redirect_uri=https://$base_url/aaa/login");
          exit();
       }

        
            
        return $this->render('index', array(
            'model'=>$model,
            'federation' => AaaPreference::isFederationEnabled(),
        ));
    }
     
    public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }
    
    public function actionPassword() {
        $model = new ForgotPasswordForm;
        
        if($model->load($_POST)) {        	
            if(isset($_POST['g-recaptcha-response'])) $captcha=$_POST['g-recaptcha-response'];
            if(!isset($captcha) || !$captcha){
                $model->addError('login', Yii::t('home', 'Please, check the captcha'));
                return $this->render('forgotPassword', array('model'=>$model));
            }
            $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret="
                .Yii::$app->params["google.recaptcha.secret.key"]
                ."&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR']);
            if($response){
                if($model->validate()){
                    if($model->sendEmail()){
                        return $this->redirect('index');
                    }
                    else {
                        return $this->render('forgotPassword', array('model'=>$model));
                    }
                }
                else{
                    return $this->render('forgotPassword', array('model'=>$model));
                }
            }
            else {            	
                $model->addError('login', Yii::t('home', 'Please, check the captcha'));
                return $this->render('forgotPassword', array('model'=>$model));
            }
        }

        return $this->render('forgotPassword', array('model'=>$model));
    }
    
    public function actionCafe() {
        $cafeUser = new CafeUserForm;
        if ($cafeUser->load($_POST) && $cafeUser->validate()) {
            $user = new User;
            $data = Yii::$app->session["data_from_cafe"];
            $data = json_decode($data);
            $user->setFromData($cafeUser->login, $cafeUser->password, $data->name,
                $data->email, 
                Preference::findOneValue(AaaPreference::AAA_FEDERATION_GROUP), 
                Preference::findOneValue(AaaPreference::AAA_FEDERATION_DOMAIN));
            if($user->save()) {
                $loginForm = new LoginForm;
                 $loginForm->createSession($user);
                 return $this->goHome();
            } else {
                foreach($user->getErrors() as $attribute => $error) {
                    $cafeUser->addError('', $error[0]);
                }

                return $this->render('createCafeUser', array('model'=>$cafeUser));
            }
        }

        $data = Yii::$app->session["data_from_cafe"];
        if ($data) {
            $data = json_decode($data);
            $user = User::findOneByEmail($data->email);
            if ($user) {
                $loginForm = new LoginForm;
                 $loginForm->createSession($user);
                 return $this->goHome();
            } else {
                return $this->render('createCafeUser', array('model'=>$cafeUser));
            }
        } 
        return $this->goHome();
    }

}
?>
