<?php   
/**
 * @copyright Copyright (c) 2012-2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

namespace meican\aaa\forms;

use yii\base\Model;
use Yii;

use meican\aaa\models\User;

/**
 */
class UserSearchForm extends Model {
    
    public $id;
    public $login;
    public $name;
    public $email;
    public $numRoles;
    public $role_name;

    /**
     */
    public function rules()    {
        return [
            [['login', 'name', 'id', 'numRoles','email'], 'required'],
        ];
    }
    
    public function attributeLabels() {
        return [
            'login'=>Yii::t('aaa', 'User'),
            "numRoles"=>Yii::t('aaa', 'Roles in Domain'),
            'name' => Yii::t('aaa', 'Name'),
            'email' => Yii::t('aaa', 'Email'),
        ];
    }
    
    public function setData($user, $numRoles) {
        $this->id = $user->id;
        $this->login = $user->login;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->numRoles = $numRoles;
    }
}
