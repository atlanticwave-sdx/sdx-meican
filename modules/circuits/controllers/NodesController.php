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
class NodesController extends RbacController {

    public $enableCsrfValidation = false;
    
    public function actionShow() {
   
	$response='{
    "id": "urn:sdx:topology:amlight.net",
    "name": "AmLight-OXP",
    "version": 1,
    "model_version": "1.0.0",
    "timestamp": "2000-01-23T04:56:07Z",
    "links": [
        {
            "id": "urn:sdx:link:amlight:Sao Paulo:Cape Town",
            "name": "amlight_B1_B2",
            "ports": [
                "urn:sdx:port:amlight.net:B1:2",
                "urn:sdx:port:amlight.net:B2:2"
            ],
            "type": "inter",
            "bandwidth": 125000000,
            "residual_bandwidth": 100,
            "latency": 146582.15146899645,
            "packet_loss": 59.621339166831824,
            "availability": 56.37376656633328,
            "status": "up",
            "state": "enabled"
        },
        {
            "id": "urn:sdx:link:amlight:Miami:Sao Paulo",
            "name": "amlight_A1_B1",
            "ports": [
                "urn:sdx:port:amlight.net:A1:1",
                "urn:sdx:port:amlight.net:B1:3"
            ],
            "type": "inter",
            "bandwidth": 125000000,
            "residual_bandwidth": 100,
            "latency": 146582.15146899645,
            "packet_loss": 59.621339166831824,
            "availability": 56.37376656633328,
            "status": "up",
            "state": "enabled"
        },
        {
            "id": "urn:sdx:link:amlight:Cape Town:Miami",
            "name": "amlight_A1_B2",
            "ports": [
                "urn:sdx:port:amlight.net:A1:2",
                "urn:sdx:port:amlight.net:B2:3"
            ],
            "type": "inter",
            "bandwidth": 125000000,
            "residual_bandwidth": 100,
            "latency": 146582.15146899645,
            "packet_loss": 59.621339166831824,
            "availability": 56.37376656633328,
            "status": "up",
            "state": "enabled"
        },
        {
            "id": "urn:sdx:link:nni:Miami:Sao Paulo",
            "name": "nni_Miami_Sanpaolo",
            "ports": [
                "urn:sdx:port:amlight:B1:1",
                "urn:sdx:port:sax:B1:1"
            ],
            "type": "inter",
            "bandwidth": 125000000,
            "residual_bandwidth": 1000,
            "latency": 146582.15146899645,
            "packet_loss": 59.621339166831824,
            "availability": 56.37376656633328,
            "status": "up",
            "state": "enabled"
        },
        {
            "id": "urn:sdx:link:nni:Sao Paulo:Cape Town",
            "name": "nni_BocaRaton_Fortaleza",
            "ports": [
                "urn:sdx:port:amlight.net:B2:1",
                "urn:sdx:port:sax:B2:1"
            ],
            "type": "inter",
            "bandwidth": 125000000,
            "residual_bandwidth": 100,
            "latency": 146582.15146899645,
            "packet_loss": 59.621339166831824,
            "availability": 56.37376656633328,
            "status": "up",
            "state": "enabled"
        }
    ],
    "nodes": [
        {
            "id": "urn:sdx:node:amlight.net:B1",
            "location": {
                "address": "Miami",
                "latitude": 25.75633040531146,
                "longitude": -80.37676058477908,
                "ISO3166-2-lvl4": "US-FL"
            },
            "name": "amlight_Novi01",
            "ports": [
                {
                    "id": "urn:sdx:port:amlight:B1:1",
                    "name": "Novi01_1",
                    "node": "urn:sdx:node:amlight.net:B1",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                },
                {
                    "id": "urn:sdx:port:amlight.net:B1:2",
                    "name": "Novi01_2",
                    "node": "urn:sdx:node:amlight.net:B1",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                },
                {
                    "id": "urn:sdx:port:amlight.net:B1:3",
                    "name": "Novi01_3",
                    "node": "urn:sdx:node:amlight.net:B1",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                }
            ]
        },
        {
            "id": "urn:sdx:node:amlight.net:B2",
            "location": {
                "address": "BocaRaton",
                "latitude": 26.381437356374075,
                "longitude": -80.10225977485742,
                "ISO3166-2-lvl4": "US-FL"
            },
            "name": "amlight_Novi02",
            "ports": [
                {
                    "id": "urn:sdx:port:amlight.net:B2:1",
                    "name": "Novi02_1",
                    "node": "urn:sdx:node:amlight.net:B2",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                },
                {
                    "id": "urn:sdx:port:amlight.net:B2:2",
                    "name": "Novi02_2",
                    "node": "urn:sdx:node:amlight.net:B2",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                },
                {
                    "id": "urn:sdx:port:amlight.net:B2:3",
                    "name": "Novi02_3",
                    "node": "urn:sdx:node:amlight.net:B2",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                }
            ]
        },
        {
            "id": "urn:sdx:node:amlight.net:A1",
            "location": {
                "address": "redclara",
                "latitude": 30.34943181039702,
                "longitude": -81.66666016473143,
                "ISO3166-2-lvl4": "US-FL"
            },
            "name": "amlight_Novi100",
            "ports": [
                {
                    "id": "urn:sdx:port:amlight.net:A1:1",
                    "name": "Novi100_1",
                    "node": "urn:sdx:node:amlight.net:A1",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                },
                {
                    "id": "urn:sdx:port:amlight.net:A1:2",
                    "name": "Novi100_2",
                    "node": "urn:sdx:node:amlight.net:A1",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                }
            ]
        },
        {
            "id": "urn:sax:node:ampath.net:B1",
            "location": {
                "address": "Sao Paulo, Brazil",
                "latitude": -23.549421623110316,
                "longitude": -46.63699698680052,
                "ISO3166-2-lvl4": "BR-SP"
            },
            "name": "sax_sw1",
            "ports": [
                {
                    "id": "urn:sax:port:ampath.net:B1:1",
                    "name": "Saxsw1_1",
                    "node": "urn:sax:node:ampath.net:B1",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                },
                {
                    "id": "urn:sax:port:ampath.net:B1:2",
                    "name": "Saxsw1_2",
                    "node": "urn:sax:node:ampath.net:B1",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                }
            ]
        },
        {
            "id": "urn:tenet:node:tenet.ac.za:B1",
            "location": {
                "address": "Cape Town, Western Cape",
                "latitude": -34.00315245,
                "longitude": 18.467604352310875,
                "ISO3166-2-lvl4": "ZA-WC"
            },
            "name": "tenet_sw1",
            "ports": [
                {
                    "id": "urn:tenet:port:tenet.ac.za:B1:1",
                    "name": "Tenetsw1_1",
                    "node": "urn:tenet:node:tenet.ac.za:B1",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                },
                {
                    "id": "urn:tenet:port:tenet.ac.za:B1:2",
                    "name": "Tenetsw1_2",
                    "node": "urn:tenet:node:tenet.ac.za:B1",
                    "type": "10GE",
                    "status": "up",
                    "state": "enabled"
                }
            ]
        }
    ]
}
';

    function find_subnode($nodes_array,$location){

      
      $temp=array();
      foreach ($nodes_array as $key => $value) {
        // code...
        $sub_nodes=$value['sub_nodes'];

        foreach ($sub_nodes as $key2 => $value2) {
          // code...
          $sub_node_name = explode(',', $value2['sub_node_name']);
          if($sub_node_name[0]==$location){

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
        
        if (!array_key_exists($location['ISO3166-2-lvl4'],$nodes_array)){

            $nodes_array[$location['ISO3166-2-lvl4']]['sub_nodes']=array();
            $nodes_array[$location['ISO3166-2-lvl4']]['latitude']=$location['latitude'];
            $nodes_array[$location['ISO3166-2-lvl4']]['longitude']=$location['longitude'];
            $temp_arr=array(
            'sub_node_name'=>$location['address'],
            'ports'=>$ports,
            'name'=>$value->name,
            'id'=>$value->id
            );
            array_push($nodes_array[$location['ISO3166-2-lvl4']]['sub_nodes'],$temp_arr);

        }
        else{
            $temp_arr=array(
            'sub_node_name'=>$location['address'],
            'ports'=>$ports,
            'name'=>$value->name,
            'id'=>$value->id
            );
            array_push($nodes_array[$location['ISO3166-2-lvl4']]['sub_nodes'],$temp_arr);
        }
    }
    
    $latlng_array=array();
    $links_array=array();

    foreach ($links as $key => $value) {
      // code...
      $explode=explode(':',$value->id);
      $latlng=find_subnode($nodes_array,$explode[4]);
      $latlng2=find_subnode($nodes_array,$explode[5]);
      if(!empty($latlng)&&!empty($latlng2)){
        $temp_node=array();
       $temp_node['link']=$latlng['node']."-".$latlng2['node'];
       $temp_node['latlngs']=array();
       
       array_push($temp_node['latlngs'],$latlng['latlngs']);
       array_push($temp_node['latlngs'],$latlng2['latlngs']);

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
    
        return $this->render('nodes/nodes',['nodes_array'=>$nodes_array,'latlng_array'=>$latlng_array,'links_array'=>$links_array]);
    }


}

