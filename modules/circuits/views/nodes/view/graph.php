<?php 
    use meican\modules\circuits\assets\reservation\ViewAsset;
    use yii\grid\GridView;
    use yii\grid\CheckboxColumn;
    use yii\helpers\Url;
    use yii\widgets\Pjax;
    use yii\jui\Dialog;
    use yii\helpers\Html;
    
    ViewAsset::register($this);
?>

<h1 style="clear: none; float: left; z-index: 999999; position: absolute;">
    <data id="res-id" hidden><?= $reservation->id; ?></data>
    <select id="viewer-type-select" class="select-menu" style="width: 80px;" hidden>
        <optgroup label="<?= Yii::t("topology", "Graph"); ?>">
            <option value="g-s"><?= Yii::t("topology", "Graph"); ?></option>
        </optgroup>
    </select>
    <select id="node-type-select" class="select-menu" style="width: 95px;" hidden>
        <option value="dev"><?= Yii::t("topology", "Ports"); ?></option>
    </select>
</h1>

<div id="subtab-points" class="tab_subcontent">
    <?= $this->render('_formEndpoints', array('label' => Yii::t("circuits", "Source"), 'prefix' => 'src', 
    )); ?>
    <div id="bandwidth_bar">
        <div id="reservation-view-bandwidth-bar">
            <input type="text" id="reservation-view-bandwidth-field" value="<?php echo $reservation->bandwidth . " " . "Mbps" ?>" disabled="disabled" class="ui-widget ui-spinner-input"/>
        </div>
        <div id="bandwidth_bar_inside" style="width: <?= round($reservation->bandwidth * 100 / 1000); ?>%"></div>
    </div>
    <?= $this->render('_formEndpoints', array('label' => Yii::t("circuits", "Destination"), 'prefix' => 'dst',
    )); ?>
</div>

<div id="reservation-tab">
    <div id="reservation-connections">
        <div class="controls">
            <button id="refresh-button" value="true"><?= Yii::t("circuits", "Disable auto refresh"); ?></button>
        </div>
        <?php Pjax::begin([
            'id' => 'connections-pjax',
        ]); ?>
        
        <?=
            GridView::widget([
                'options' => [
                        'id'=>'connections-grid',
                        'class' => 'list'],
                'dataProvider' => $connections,
                'summary' => false,
                'columns' => array(
                        [
                            'format' => 'raw',
                            'value' => function ($model){
                                return '<a href="#">'.Html::img('@web/images/delete_2.png', [
                                    'class' => "cancel-button",
                                    'disabled' => $model->isCancelStatus(),
                                    ])."</a>";
                            },
                            'headerOptions'=>['style'=>'width: 7%;'],       
                        ],
                        [
                            'attribute' => 'external_id',
                            'headerOptions'=>['style'=>'width: 29%;'],      
                        ],
                        [
                            'attribute' => 'start',
                            'format'    => 'datetime',      
                            'headerOptions'=>['style'=>'width: 14%;'],
                        ],
                        [
                            'attribute' => 'finish',
                            'format'    => 'datetime',  
                            'headerOptions'=>['style'=>'width: 14%;'],
                        ],
                        [
                            'attribute' => 'status',
                            'value' => function($model){
                                return $model->getStatus(); 
                            },
                            'headerOptions'=>['style'=>'width: 12%;'],
                        ],
                        [
                            'attribute' => 'auth_status',
                            'value' => function($model){
                                return $model->getAuthStatus();
                             },
                            'contentOptions'=> function ($model){
                                return ['class' => strtolower($model->auth_status)];
                            },
                            'headerOptions'=>['style'=>'width: 12%;'],
                        ],
                        [
                            'attribute' => 'dataplane_status',
                            'value' => function($model){
                                return $model->getDataStatus(); 
                            },
                            'contentOptions'=> function ($model){
                                return ['class' => strtolower($model->dataplane_status)];
                            },
                            'headerOptions'=>['style'=>'width: 12%;'],
                        ],
                    ),
            ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>

<div id="copy-urn-dialog" title="<?= Yii::t("circuits", "Copy the endpoint identifier");?>" hidden>
    <label for="copy-urn-field">URN:</label>
    <br/>
    <input readonly="true" type="text" name="copy-urn-field" id="copy-urn-field" size="50" style="margin-top: 10px;" value="urn"/>
</div>

<div id="cancel-dialog" title="<?= Yii::t("circuits", "Cancel"); ?>" hidden>
    <br>
    <label><?= Yii::t("circuits", "Do you want to cancel this connection?"); ?></label>
    <br/>
</div>

<div style="display: none">
<?php Dialog::begin([
        'id' => 'dialog',
        'clientOptions' => [
            'modal' => true,
            'autoOpen' => false,
            'title' => "Reservation",
        ],
    ]);

    echo '<br></br>';
    echo '<p style="text-align: left; height: 100%; width:100%;" id="message"></p>';
    
    Dialog::end(); 
?>
</div>

<div id="tooltip" class="triangle" hidden></div>



