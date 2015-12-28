<?php

namespace meican\topology\assets\domain;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle
{
    public $sourcePath = '@meican/topology/assets/public';

    public $js = [
        'domain/index.js',
    ];

    public $depends = [
    ];
}
