<?php 
/**
 * @copyright Copyright (c) 2012-2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

use yii\helpers\Url;
use meican\aaa\RbacController;

$this->params['header'] = [$model->name, [Yii::t("aaa", 'Home'), Yii::t("aaa", 'Users'), $model->name]];

?>

<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t("aaa", "Profile"); ?></h3>
                <div class="box-tools">
                    <a href="<?= Url::to(["update", 'id'=>$model->id]) ?>" class="btn btn-sm btn-default"><i class="fa fa-pencil"></i> Edit</a>
                </div>
            </div>
            <div class="box-body">                
                <?= $this->render("_profile", ['model'=>$model]); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <?= $this->render("@meican/aaa/views/role/_domain", ['rolesProvider'=>$domainRolesProvider, 'userId' => $model->id]); ?>
        <?php if(RbacController::can("user/read")) echo $this->render("@meican/aaa/views/role/_system", ['rolesProvider'=>$systemRolesProvider, 'userId' => $model->id]); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Yii::t("aaa", "Topology Roles"); ?></h3>
            </div>
            <div class="box-body">
                <?php if (!empty($domains)): ?>
                    <form method="POST" action="">
                    <?= \yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?= Yii::t("aaa", "Domain") ?></th>
                                    <th><?= Yii::t("aaa", "Select") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($domains as $domain): ?>
                                    <tr>
                                        <td><?= $domain ?></td>
                                        <td>
                                            <input type="checkbox" name="selected_domains[]" value="<?= $domain ?>"
                                                <?= in_array($domain, $selectedDomains) ? 'checked' : '' ?>>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <button type="submit" name="action_type" value="submit_selected" class="btn btn-primary">
                            <?= Yii::t("aaa", "Submit") ?>
                        </button>
                    </form>
                <?php else: ?>
                    <p><?= Yii::t("aaa", "No domains available."); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>