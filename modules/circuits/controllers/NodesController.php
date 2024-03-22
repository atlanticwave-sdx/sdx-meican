<?php
/**
 * @copyright Copyright (c) 2012-2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

namespace meican\circuits\controllers;

use Yii;
use yii\helpers\Url;
use DateTime;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;

use meican\nsi\NSIParser;
use meican\aaa\RbacController;
use meican\base\utils\DateUtils;
use meican\circuits\models\Reservation;
use meican\circuits\models\Connection;
use meican\circuits\models\ConnectionAuth;
use meican\circuits\models\ConnectionPath;
use meican\circuits\models\CircuitsPreference;
use meican\circuits\models\Protocol;
use meican\circuits\models\ConnectionEvent;
use meican\circuits\models\ReservationPath;
use meican\circuits\models\CircuitNotification;
use meican\circuits\forms\ReservationForm;
use meican\circuits\forms\ReservationSearch;
use meican\topology\models\Port;
use meican\topology\models\Domain;
use meican\topology\models\Network;
use meican\topology\models\Service;

include(__DIR__ . '/../../../config/db.php');

/**
 * @author Maurício Quatrin Guerreiros
 */

/* This is the controller for handling SDX-topology and creating connection requests to SDX-Controller */
class NodesController extends RbacController {

    public $enableCsrfValidation = false;

    public function actionCreate(){ // this route manages the view and backend logic for creating a circuit request

        $request = Yii::$app->request->getRawBody(); // getting the JSON request body from the create connection form through MEICAN dashboard
        $request=stripslashes($request);
        $api_url=API_URL;
        $curl = curl_init();

        /* CURL request to SDX-Controller endpoint for creating a circuit request*/

        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url.'connection',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$request,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
          ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
    
    public function actionShow() {   // this function manages the mapping of SDX-topology and displays on the MEICAN UI
      
      $api_url=API_URL;
      $meican_url=MEICAN_URL;

      $userId = Yii::$app->user->id;
      $rows = (new \yii\db\Query())
          ->select(['token', 'expiration'])
          ->from('meican_cilogon_auth')
          ->where(['user_id' => $userId])
          ->all();
      
      date_default_timezone_set('America/New_York');
      $token_date = isset($rows[0]['expiration']) ? $rows[0]['expiration'] : null;
      $token = isset($rows[0]['token']) ? $rows[0]['token'] : null;
      if ($token_date !== null && $token !== null) {

        $token_date_parsed = DateTime::createFromFormat('Y-m-d H:i:s', $token_date);
        $curr_datetime = new DateTime();
        print_r($token_date_parsed);
        print_r($curr_datetime);

        if ($token_date_parsed !== null && $curr_datetime !== null) {
            $diff = $token_date_parsed->diff($curr_datetime);
            print_r($diff);

            // $total_days = $diff->days; // Total days
            $total_seconds = $diff->days * 24 * 60 * 60 + $diff->h * 60 * 60 + $diff->i * 60 + $diff->s;
            // $total_hours = $total_days * 24 + $diff->h; // Total hours including days

            print_r($total_seconds);
            if ($total_seconds < 172800) {
                echo "The difference is less than 2 days.";
            } else {
                echo "The difference is 2 days or more.";
            }
        } else {
          // should I create the curl here?
            echo "Error: Unable to parse datetime strings.";
        }

        if (!empty($rows[0]['token']) && isset($total_seconds) && $total_seconds <= 172800){
          print_r("Entered IF");
          header("Location: https://".$meican_url."/circuits/nodes/show&scope=openid+profile+email");
        }
        else {
          print_r("Entered ELSE");
          $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

          if (strpos($actual_link,'code') !== false) {
              $code=$_GET['code'];
  
            $curl = curl_init();
  
            curl_setopt_array($curl, array(
              CURLOPT_URL => 'https://cilogon.org/oauth2/token?grant_type=authorization_code&client_id=cilogon%3A%2Fclient_id%2F25669b5df0f0c45880aa56c410bd32b9&redirect_uri=https%3A%2F%2F'.$meican_url.'%2Fcircuits%2Fnodes%2Fshow&client_secret=Vib4sbOGQXI8tt9s-uQ16hQnJlNUxUsAvAMISiCJBPFlLf2c82ZyUDKBYpv-eNX6qV-wW-OzRzoAaVslxCsozQ&code='.$code.'',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
            ));
  
            $response = curl_exec($curl);
  
            curl_close($curl);
            $response_arr=json_decode($response,true);
            if(array_key_exists('access_token',$response_arr)){
              $access_token=$response_arr['access_token'];
              
              $result = Yii::$app->db->createCommand()->update(
                'meican_cilogon_auth',
                ['token' => $access_token, 'expiration' => (new DateTime())->format('Y-m-d H:i:s')],
                'user_id = ' .$userId
              )->execute();
  
              if ($result) {
                  print_r('Update Success');
              } else {
                  print_r('Update Failed');
              }
            }
            else{
              header("Location: https://cilogon.org/authorize?response_type=code&client_id=cilogon:/client_id/25669b5df0f0c45880aa56c410bd32b9&redirect_uri=https://".$meican_url."/circuits/nodes/show&scope=openid+profile+email");
              exit();
            } 
          }
          else {
            header("Location: https://cilogon.org/authorize?response_type=code&client_id=cilogon:/client_id/25669b5df0f0c45880aa56c410bd32b9&redirect_uri=https://".$meican_url."/circuits/nodes/show&scope=openid+profile+email");
            exit();
          }
        }
        
      }
      else {
        print_r("Entered ELSE");
        $actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        if (strpos($actual_link,'code') !== false) {
            $code=$_GET['code'];

          $curl = curl_init();

          curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://cilogon.org/oauth2/token?grant_type=authorization_code&client_id=cilogon%3A%2Fclient_id%2F25669b5df0f0c45880aa56c410bd32b9&redirect_uri=https%3A%2F%2F'.$meican_url.'%2Fcircuits%2Fnodes%2Fshow&client_secret=Vib4sbOGQXI8tt9s-uQ16hQnJlNUxUsAvAMISiCJBPFlLf2c82ZyUDKBYpv-eNX6qV-wW-OzRzoAaVslxCsozQ&code='.$code.'',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
          ));

          $response = curl_exec($curl);

          curl_close($curl);
          $response_arr=json_decode($response,true);
          if(array_key_exists('access_token',$response_arr)){
            $access_token=$response_arr['access_token'];

            $result = Yii::$app->db->createCommand()->insert('meican_cilogon_auth', [
              'user_id' => $userId,
              'token' => $access_token,
              'expiration' => (new DateTime())->format('Y-m-d H:i:s')
              ])->execute();

            if ($result) {
                print_r('Insert Success');
            } else {
                print_r('Insert Failed');
            }
          }
          else{
            header("Location: https://cilogon.org/authorize?response_type=code&client_id=cilogon:/client_id/25669b5df0f0c45880aa56c410bd32b9&redirect_uri=https://".$meican_url."/circuits/nodes/show&scope=openid+profile+email");
            exit();
          } 
        }
        else {
          header("Location: https://cilogon.org/authorize?response_type=code&client_id=cilogon:/client_id/25669b5df0f0c45880aa56c410bd32b9&redirect_uri=https://".$meican_url."/circuits/nodes/show&scope=openid+profile+email");
          exit();
        }
      }

    if(!self::can("sdxCircuit/create")){
            return $this->goHome();
        }

    //calling API for topology
    $curl = curl_init();

    /* CURL request to SDX-Controller endpoint for getting topology and mapping on the MEICAN UI */

    curl_setopt_array($curl, array(
      CURLOPT_URL => $api_url.'topology',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    /* Processing topology JSON */
    function find_subnode_by_id($nodes_array,$node_id){

      
      $temp=array();
      foreach ($nodes_array as $key => $value) {
        // code...
        $sub_nodes=$value['sub_nodes'];

        foreach ($sub_nodes as $key2 => $value2) {
          // code...
          if($value2['id']==$node_id){

            $temp2=array();
            array_push($temp2,$value['latitude']);
            array_push($temp2,$value['longitude']);
            $temp['node']=$key;
            $temp['latlngs']=$temp2;
            break;

          }
        }
      }

      return $temp;


    }


    
    $json_response=json_decode($response);
    $nodes=$json_response->nodes;
    $links=$json_response->links;
    $nodes_array=array();

    foreach ($nodes as $key => $value) {
        // code...
        
        $location = json_decode(json_encode($value->location), true);
        $ports = json_decode(json_encode($value->ports), true);
        
        if (!array_key_exists($location['iso3166_2_lvl4'],$nodes_array)){

            $nodes_array[$location['iso3166_2_lvl4']]['sub_nodes']=array();
            $nodes_array[$location['iso3166_2_lvl4']]['latitude']=$location['latitude'];
            $nodes_array[$location['iso3166_2_lvl4']]['longitude']=$location['longitude'];
            $temp_arr=array(
            'sub_node_name'=>$location['address'],
            'ports'=>$ports,
            'name'=>$value->name,
            'id'=>$value->id
            );
            array_push($nodes_array[$location['iso3166_2_lvl4']]['sub_nodes'],$temp_arr);

        }
        else{
            $temp_arr=array(
            'sub_node_name'=>$location['address'],
            'ports'=>$ports,
            'name'=>$value->name,
            'id'=>$value->id
            );
            array_push($nodes_array[$location['iso3166_2_lvl4']]['sub_nodes'],$temp_arr);
        }
    }
    
    $latlng_array=array();
    $links_array=array();

    foreach ($links as $key => $value) {
      // code...
      $latlng=find_subnode_by_id($nodes_array,$value->ports[0]->node);
      $latlng2=find_subnode_by_id($nodes_array,$value->ports[1]->node);
      if(!empty($latlng)&&!empty($latlng2)){
        $temp_node=array();
        $temp_node['link']=$latlng['node']."-".$latlng2['node'];
        $temp_node['latlngs']=array(
          $value->id => array($latlng['latlngs'],$latlng2['latlngs'])
        );
       
       array_push($latlng_array,$temp_node);

       $link_temp = json_decode(json_encode($value), true);
       if (!array_key_exists($temp_node['link'],$links_array)){

         $links_array[$temp_node['link']]=array();
         array_push($links_array[$temp_node['link']], $link_temp);


       }

       else{
        array_push($links_array[$temp_node['link']], $link_temp);

       }
      
        
      }
      
    }
        
        return $this->render('nodes/nodes',['nodes_array'=>$nodes_array,'latlng_array'=>$latlng_array,'links_array'=>$links_array,'meican_url'=>$meican_url]);
    }


}

