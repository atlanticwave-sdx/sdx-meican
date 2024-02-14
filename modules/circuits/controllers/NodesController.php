<?php
/**
 * @copyright Copyright (c) 2012-2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

namespace meican\circuits\controllers;

use Yii;
use yii\helpers\Url;
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
   
    if(!self::can("sdxCircuit/create")){
            return $this->goHome();
        }

    //calling API for topology
    $api_url=API_URL;
    $meican_url=MEICAN_URL;
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

