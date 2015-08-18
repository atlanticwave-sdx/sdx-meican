<?php

namespace app\modules\topology\controllers\services;

use Yii;

use app\models\TopologySynchronizer;
use app\modules\topology\models\NSIParser;
use app\models\Preference;
use yii\helpers\Url;

/*
 * Classe que implementa o módulo Cliente do protocolo NSI Document Distribution Service (DDS), 
 * também conhecido como Discovery Service.
 *
 * Envia mensagens para provedores NSI para criar, alterar ou remover subscrições para notificações.
 */

class DiscoveryClient {
    
    static function subscribe($url) {
        $ch = curl_init();
         
        $options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_POST            => 1,
                CURLOPT_POSTFIELDS  => '<?xml version="1.0" encoding="UTF-8"?><tns:subscriptionRequest '.
                    'xmlns:tns="http://schemas.ogf.org/nsi/2014/02/discovery/types">'.
                '<requesterId>'.Preference::findOneValue(Preference::MEICAN_NSA).'</requesterId>'.
                //'<callback>'.Url::toRoute("/topology/discovery/notification", "http").'</callback>'.
                '<callback>http://143.52.12.245/meican2/web/topology/discovery/notification</callback>'.
                '<filter>'.
                    '<include>'.
                        '<event>All</event>'.
                    '</include>'.
                '</filter>'.
                '</tns:subscriptionRequest>',
                CURLOPT_HTTPHEADER => array(
                        'Accept-encoding: application/xml;charset=utf-8',
                        'Content-Type: application/xml;charset=utf-8'),
                CURLOPT_USERAGENT => 'Meican',
                CURLOPT_URL => $url.'/subscriptions',
        );
         
        curl_setopt_array($ch , $options);
         
        $output = curl_exec($ch);
        Yii::trace($output);

        curl_close($ch);

        $parser = new NSIParser;
        $parser->loadXml($output);
        $parser->parseSubscriptions();

        foreach ($parser->getData()['subs'] as $subId => $sub) {
            return (string) $subId;
        }
    }

    static function unsubscribe() {

    }
}
