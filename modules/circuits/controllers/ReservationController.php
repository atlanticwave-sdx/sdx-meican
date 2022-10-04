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
class ReservationController extends RbacController {

    public $enableCsrfValidation = false;
    
    public function actionCreate() {
        //echo "testing";
        return $this->render('create/create',[
            'reserveForm' => new ReservationForm]);
    }

    public function actionRequest() {
        $form = new ReservationForm;
        if ($form->load($_POST)) {
            
            //Confere se usuário tem permissão para reservas na origem OU no destino
            /*$source = Port::findOne(['id' => $form->src_port]);
            $destination = Port::findOne(['id' => $form->dst_port]);
            $permission = false;
            if($source){
                $source = $source->getDevice()->one();
                if($source){
                    $domain = $source->getDomain()->one();
                    if($domain && self::can('reservation/create', $domain->name)) $permission = true;
                }
            }
            if($destination){
                $destination = $destination->getDevice()->one();
                if($destination){
                    $domain = $destination->getDomain()->one();
                    if($domain &&self::can('reservation/create', $domain->name)) $permission = true;
                }
            }
            if(!$permission){ //Se ele não tiver em nenhum dos dois, exibe aviso
                return -1;
            }*/

            if ($form->save()) {
                Yii::$app->getSession()->addFlash('success', Yii::t('circuits', 'Circuit reservation successfully registered. Please wait while we check for required resources.'));
                return $form->reservation->id;
            }
        }

        return $this->redirect("create");
    }
    
    public function actionConfirm() {
        self::beginAsyncAction();
        
        $reservation = Reservation::findOne($_POST['id']);
        $reservation->confirm();

        return "";
    }
    
    //Verificar, pois a cada atualizacao da pagina ele vai verificar as autorizações, 
    //isso está fora do contexto dessa função. Deveria ser feito por workflows.
    public function actionView($id) {
        $reservation = Reservation::findOne($id);
        $totalConns = $reservation->getConnections()->count();
        Yii::trace($totalConns);
        if ($totalConns == 1) {
            return $this->redirect(['/circuits','id'=>$reservation->
                getConnections()->
                select(['id'])->
                asArray()->
                one()['id']]);
        }
        
        //Confere se algum pedido de autorização da expirou
        /*
        if($reservation){
            $connectionsExpired = $conn = Connection::find()->where(['reservation_id' => $reservation->id])->andWhere(['<=','start', DateUtils::now()])->all();
            foreach($connectionsExpired as $connection){
                $requests = ConnectionAuth::find()->where(['connection_id' => $connection->id, 'status' => Connection::AUTH_STATUS_PENDING])->all();
                foreach($requests as $request){
                    $request->changeStatusToExpired();
                    $connection->auth_status= Connection::AUTH_STATUS_EXPIRED;
                    $connection->save();
                    Notification::createConnectionNotification($connection->id);
                }
            }
        }

        //Confere a permissão
        $domains_name = [];
        foreach(self::whichDomainsCan('reservation/read') as $domain) $domains_name[] = $domain->name;
        $permission = false;
        if(Yii::$app->user->getId() == $reservation->request_user_id) $permission = true; //Se é quem requisitou
        else {
            $conns = Connection::find()->where(['reservation_id' => $reservation->id])->select(["id"])->all();
            if(!empty($conns)){
                $conn_ids = [];
                foreach($conns as $conn) $conn_ids[] = $conn->id;
            
                $paths = ConnectionPath::find()
                         ->where(['in', 'domain', $domains_name])
                         ->andWhere(['in', 'conn_id', $conn_ids])
                         ->select(["conn_id"])->distinct(true)->one();
                 
                if(!empty($paths)) $permission = true;
            }
        }
        
        if(!$permission){ //Se ele não tiver permissão em nenhum domínio do path e não for quem requisitou
            return $this->goHome();
        }*/
        
        $connDataProvider = new ActiveDataProvider([
                'query' => $reservation->getConnections(),
                'sort' => false,
                'pagination' => [
                    'pageSize' => 5,
                ]
        ]);

        return $this->render('view/view',[
                'reservation' => $reservation,
                'connDataProvider' => $connDataProvider
        ]);
    }

    public function actionStatus() {
        $searchModel = new ReservationSearch;
        $allowedDomains = self::whichDomainsCan('reservation/read', true);
        
        $data = $searchModel
            ->searchByDomains(Yii::$app->request->get(), $allowedDomains);

        if(Yii::$app->request->isPjax) {
            switch ($_GET['_pjax']) {
                case '#circuits-pjax':
                    return $this->renderAjax('status/_grid', [
                        'gridId' => 'circuits-grid',
                        'searchModel' => $searchModel, 
                        'data' => $data,
                        'allowedDomains' => $allowedDomains,
                    ]);
            }
        }
        
        //deve ser feito quando ha duas ou mais grids na mesma pagina   
        //$scheduledData->pagination->pageParam = 'scheduled-page';
        //$finishedData->pagination->pageParam = 'finished-page';

        return $this->render('status/status', [
            'searchModel' => $searchModel,
            'data' => $data,
            'allowedDomains' => $allowedDomains
        ]);
    }

    
    //////REST functions

    public function actionGetPortByDevice($id, $cols=null) {
        $query = Port::find()->where(['device_id'=>$id])->orderBy(['name'=>'SORT ASC'])->asArray();

        if (!CircuitsPreference::findOne(CircuitsPreference::CIRCUITS_UNIPORT_ENABLED)->getBoolean()) {
            $query->andWhere(['directionality'=>Port::DIR_BI]);
        }

        if (CircuitsPreference::findOne(CircuitsPreference::CIRCUITS_PROTOCOL)->value == Service::TYPE_NSI_CSP_2_0) {
            $query->andWhere(['type'=>Port::TYPE_NSI]);
        }

        $cols ? $data = $query->select(json_decode($cols))->all() : $data = $query->all();

        $temp = Json::encode($data);
        Yii::trace($temp);
        return $temp;
    }

    public function actionDummy() {
        return True;
    }
}
