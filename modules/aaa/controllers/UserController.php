<?php
/**
 * @copyright Copyright (c) 2012-2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

namespace meican\aaa\controllers;

use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use Yii;

use meican\base\models\Preference;
use meican\aaa\forms\UserForm;
use meican\aaa\forms\AccountForm;
use meican\aaa\forms\UserSearch;
use meican\aaa\models\User;
use meican\aaa\models\UserSettings;
use meican\aaa\models\UserDomainRole;
use meican\aaa\models\Group;
use meican\aaa\RbacController;
use meican\notify\models\Notification;
use meican\topology\models\Domain;

/**
 * @author Maurício Quatrin Guerreiro @mqgmaster
 */
class UserController extends RbacController {

    public function actionIndex() {
        if(self::can("user/read")){
            $allowedDomains = Domain::find()->orderBy(['name' => SORT_ASC])->all();
            $searchModel = new UserSearch;
            $data = $searchModel->searchByDomains(Yii::$app->request->get(), $allowedDomains, true);
        }
        else if(self::can("role/read")){
            $allowedDomains = self::whichDomainsCan('role/read');
            $searchModel = new UserSearch;
            $data = $searchModel->searchByDomains(Yii::$app->request->get(), $allowedDomains, false, true);
        }
        else return $this->goHome();

        
        
        foreach ($data->allModels as $key=>$value) {
            $userId = Yii::$app->user->id;
             if ($value->id == 1 && $userId!=1) {
        unset($data->allModels[$key]); // Removes the entire object
        }
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($value->id);
        foreach ($roles as $role) {
            $group = Group::findOne(['role_name'=>$role->name]);
            $connection=Yii::$app->db;
            $command=$connection->createCommand(
                    "SELECT * FROM meican_group WHERE role_name='".$group->role_name."'")->queryAll();
        }
        $value->role_name=$command[0]['name'];
        }

        return $this->render('index', array(
                'searchModel' => $searchModel,
                'users' => $data,
                'domains' => $allowedDomains,
        ));

    }

    public function actionView($id) {
        $user = User::findOne($id);

        $api_url=API_URL;
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
          CURLOPT_URL => $api_url.'topology/domain',
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
        $domains = json_decode($response, true);

        if(self::can("user/read")){
        	$roles = UserDomainRole::find()->where(['user_id' => $user->id])->all();
        	$filtered = [];
        	foreach($roles as $role){
        		if($role->getGroup()->type == Group::TYPE_DOMAIN) $filtered[] = $role->id;
        	}
        	$queryDomain = UserDomainRole::find()->where(['in', 'id', $filtered]);
        }
        else if(self::can("role/read")){
        	$allowedDomains = self::whichDomainsCan('role/read');
            $domains_name = [];
            foreach($allowedDomains as $domain) $domains_name[] = $domain->name;
            $roles = UserDomainRole::find()->where(['user_id' => $user->id])->andWhere(['in', 'domain', $domains_name])->all();
            $filtered = [];
            foreach($roles as $role){
            	if($role->getGroup()->type == Group::TYPE_DOMAIN) $filtered[] = $role->id;
            }
            $queryDomain = UserDomainRole::find()->where(['in', 'id', $filtered]);
        }
        $domainProvider = new ActiveDataProvider([
                'query' => $queryDomain,
                'pagination' => [
                  	'pageSize' => 5,
                ],
                'sort' => false,
        ]);

        $roles = UserDomainRole::find()->where(['user_id' => $user->id])->all();
        $filtered = [];
        if(self::can("user/read")){
        	foreach($roles as $role){
        		if($role->getGroup()->type == Group::TYPE_SYSTEM) $filtered[] = $role->id;
        	}
        }
        $querySystem = UserDomainRole::find()->where(['in', 'id', $filtered]);
        $systemProvider = new ActiveDataProvider([
        		'query' => $querySystem,
        		'pagination' => [
        			'pageSize' => 5,
        		],
        		'sort' => false,
        ]);

        $selectedDomainsString = (new \yii\db\Query())
            ->select(['domain'])
            ->from('meican_user_topology_domain')
            ->where(['user_id' => $user->id])
            ->scalar();
    
        $selectedDomains = $selectedDomainsString ? explode(',', $selectedDomainsString) : [];
        
        if (Yii::$app->request->isPost) {
            $submittedDomains = Yii::$app->request->post('selected_domains', []);
            
            if (!empty($submittedDomains)) {
                $domainString = implode(',', array_unique($submittedDomains));
                
                if ($selectedDomainsString) {
                    if ($domainString !== $selectedDomainsString) {
                        Yii::$app->db->createCommand()->update('meican_user_topology_domain', [
                            'domain' => $domainString,
                        ], ['user_id' => $user->id])->execute();
        
                        Yii::$app->getSession()->setFlash('success', 'Selected domains have been updated.');
                    }
                } else {
                    Yii::$app->db->createCommand()->insert('meican_user_topology_domain', [
                        'user_id' => $user->id,
                        'domain' => $domainString
                    ])->execute();
        
                    Yii::$app->getSession()->setFlash('success', 'Selected domains have been added.');
                }
            } else {
                Yii::$app->db->createCommand()->delete('meican_user_topology_domain', ['user_id' => $user->id])->execute();
                Yii::$app->getSession()->setFlash('success', 'All domains have been removed.');
            }
        
            return $this->redirect(['view', 'id' => $user->id]);
        }
    
        return $this->render('view', [
            'model' => $user,
            'domainRolesProvider' => $domainProvider,
            'systemRolesProvider' => $systemProvider,
            'domains' => $domains,
            'selectedDomains' => $selectedDomains,
        ]);
    
    }

    public function actionCreate() {
        if(!self::can("user/create")){
          //if(!self::can("userdomain/create")){
            if(!self::can("user/read")) return $this->goHome();
            else{
                Yii::$app->getSession()->addFlash('warning', Yii::t('aaa', 'You are not allowed to create users'));
                return $this->redirect(array('index'));
            }
          //}
        }

        $userForm = new UserForm;
        $userForm->scenario = UserForm::SCENARIO_CREATE;

        if($userForm->load($_POST) && $userForm->validate()) {
            $user = new User;

            if($userForm->createUser($user)){
                Yii::$app->getSession()->addFlash("success", Yii::t('aaa', 'User added successfully'));

                return $this->redirect(array('index'));
            }
        }

        return $this->render('create',array(
                'user' => $userForm,
        ));
    }

    public function actionUpdateMyAccount() {
        $user = User::findOne(Yii::$app->user->id);
        $userForm = new UserForm;
        $userForm->scenario = UserForm::SCENARIO_UPDATE_ACCOUNT;
        return $this->edit($user, $userForm);
    }

    public function actionUpdate($id) {
        $user = User::findOne($id);
        $userForm = new UserForm;
        $userForm->scenario = UserForm::SCENARIO_UPDATE;
        return $this->edit($user, $userForm);
    }

    private function edit($user, $userForm) {
        /*if(!self::can("user/update")){
            if(!self::can("user/read")) return $this->goHome();
            else{
                Yii::$app->getSession()->addFlash('warning', Yii::t('aaa', 'You are not allowed to update users'));
                return $this->redirect(array('index'));
            }
        }

        if(!$user){
            if(!self::can("user/read")) return $this->goHome();
            else{
                Yii::$app->getSession()->addFlash('warning', Yii::t('topology', 'User not found'));
                return $this->redirect(array('index'));
            }
        }*/

        if($userForm->load($_POST)) {
            if ($userForm->validate()) {
                if ($userForm->updateUser($user)) {
                    Yii::$app->getSession()->addFlash("success", Yii::t('aaa', 'User updated successfully'));
                    return $this->redirect(array('index'));
                }
            }

        } else {
            $userForm->setFromRecord($user);
        }

        return $this->render('update',array(
                'user' => $userForm,
        ));
    }

    public function actionDelete() {
        if(!self::can("user/delete")){
            Yii::$app->getSession()->addFlash('warning', Yii::t('aaa', 'You are not allowed to delete users'));
            return $this->redirect(array('index'));
        }

        if(isset($_POST['delete'])){
            foreach ($_POST['delete'] as $userId) {
                $user = User::findOne($userId);
                if ($user->delete()) {
                    Yii::$app->getSession()->addFlash('success', Yii::t('aaa', 'User {user} deleted successfully', ['user'=>$user->login]));
                } else {
                    Yii::$app->getSession()->addFlash('error', Yii::t('aaa', 'Error deleting user').' '.$user->login);
                }
            }
        }

        return $this->redirect(array('index'));
    }

    public function actionAccount() {
        $user = User::findOne(Yii::$app->user->id);

        $rolesProvider = new ActiveDataProvider([
                'query' => $user->getRoles(),
                'pagination' => [
                  'pageSize' => 10,
                ],
                'sort' => false,
        ]);

        return $this->render('account', array(
                'model' => $user,
                'rolesProvider' => $rolesProvider
        ));
    }
}
