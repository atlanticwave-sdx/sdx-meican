<?php

namespace meican\topology\assets\sync;

use yii\web\AssetBundle;

class ChangeAsset extends AssetBundle
{
    public $sourcePath = '@meican/topology/assets/public';

    public $js = [
        'sync/changes.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}

?>