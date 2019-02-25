<?php 
/**
 * @copyright Copyright (c) 2012-2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use meican\topology\models\Domain;
use meican\topology\models\Provider;

$this->params['header'] = ["Providers", ['Home', 'Topology']];

$form= ActiveForm::begin([
    'id'        => 'provider-form',
    'method'    => 'post',
    'layout'    => 'horizontal'
]);

?>

<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['box-title']; ?></h3>
    </div>
    <div class="box-body">
        <?= $form->field($model,'name')->textInput(['size'=>50]); ?>
        <?= $form->field($model,'nsa')->textInput(['size'=>50]); ?>
       <?= $form->field($model,'type')->dropDownList(ArrayHelper::map(Provider::getTypes(), 'id', 'name')); ?>
        <?= $form->field($model,'latitude')->textInput(['size'=>20]); ?>
        <?= $form->field($model,'longitude')->textInput(['size'=>20]); ?>
       <?= $form->field($model,'domain_id')->dropDownList(ArrayHelper::map(Domain::find()->select(['id','name'])->asArray()->all(), 'id', 'name')); ?>
    </div>
    <div class="box-footer">
        <div class="form-group">
            <div class="col-sm-offset-3 col-sm-6">
                <button type="submit" class="btn btn-primary"><?= Yii::t("topology", 'Save'); ?></button>
            </div>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>
