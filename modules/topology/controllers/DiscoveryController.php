<?php

namespace app\modules\topology\controllers;

use yii\web\Controller;
use Yii;

use app\models\TopologySynchronizer;
use app\modules\topology\models\NSIParser;

/*
 * Classe que implementa o módulo Servidor do protocolo NSI Document Distribution Service (DDS), 
 * também conhecido como Discovery Service.
 * 
 * Recebe mensagens de provedores NSI para notificar de alterações da topologia. Ele chama o Synchronizer
 * para atualizar a topologia conhecida se assim estiver configurado.
 *
 * Esta classe NÃO deve extender o RbacControler, pois ela recebe respostas de provedores.
 */

class DiscoveryController extends Controller {

    public $enableCsrfValidation = false;
    
    public function actionNotification() {
        $sync = $this->getSynchronizer(Yii::$app->request->getRawBody());
        if ($sync) $sync->execute();
    }

    private function getSynchronizer($notificationXml) {
        $parser = new NSIParser; 
        $parser->loadXml($notificationXml);
        if($parser->isTD()) {
            $parser->parseNotifications();
            foreach ($parser->getData()['nots'] as $subId => $not) {
                $sync = TopologySynchronizer::find()->where(['provider_nsa' => $not['providerId']])->andWhere(['subscription_id'=> $subId])->one();
                if ($sync) {
                    $parser->parseTopology();
                    $sync->parser = $parser;
                    return $sync;
                }
                break; //VERIFICAR CASO EM QUE DUAS NOTIFICATIONS SAO RECEBIDAS NUMA MESMA MSG
            }
        }

        return null;
    }
}
