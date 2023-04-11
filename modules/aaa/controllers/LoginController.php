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
class LoginController extends BaseController {
    
    public $layout = 'login-layout';
    //VERIFICAR
    public $enableCsrfValidation = false;
    
    public function actionIndex() {
    	
        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      	//echo $actual_link;
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
        CURLOPT_POSTFIELDS => 'client_id=APP-6U5WZH9AC4EYDVAD&client_secret=c839f6ee-8991-4b4e-9ae3-aab528adc22c&grant_type=authorization_code&redirect_uri=https%3A%2F%2Flocalhost%2Faaa%2Flogin&code='.$code.'',
        CURLOPT_HTTPHEADER => array(
          'Accept: application/json',
          'Content-Type: application/x-www-form-urlencoded',
          'Cookie: X-Mapping-fjhppofk=C9BF87EDFB3F654BC98D87552A592F57'
        ),
      ));

      $response = curl_exec($curl);

      curl_close($curl);
      //echo $response;
      $response_arr=json_decode($response,true);
      print_r($response_arr);
      $orcid_id=$response_arr['orcid'];
      echo $orcid_id;
      $user=User::findByUsername($orcid_id);
      if(!empty($user)){
      print_r($user);
      $duration = 3600*24; // one day
      Yii::$app->user->login($user, $duration);
      return $this->goHome(); 
        }
      //return $this->goHome();
      //exit();

      // if(!array_key_exists('access_token',$response_arr)){
      // header("Location: https://orcid.org/oauth/authorize?client_id=APP-6U5WZH9AC4EYDVAD&response_type=code&scope=/authenticate&redirect_uri=https://localhost/aaa/login");
      // }
      } 

      else {
          header("Location: https://orcid.org/oauth/authorize?client_id=APP-6U5WZH9AC4EYDVAD&response_type=code&scope=/authenticate&redirect_uri=https://localhost/aaa/login");
       }

        // if (!\Yii::$app->user->isGuest) {
        //     return $this->goHome();
        // }
        
      $model = new LoginForm;
        
      //   if($model->load($_POST)) {
      //       if($model->login()) {
      //           return $this->goHome();
      //       }
      //   }
            
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
