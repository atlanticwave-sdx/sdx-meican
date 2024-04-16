<?php
/**
 * @copyright Copyright (c) 2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

 defined('MEICAN_URL') or define('MEICAN_URL', 'localhost');
 defined('API_URL') or define('API_URL', 'http://aw-sdx-controller.renci.org:8080/SDX-Controller/1.0.0/');
 defined('ORCID_CLIENT_ID') or define('ORCID_CLIENT_ID', 'APP-6U5WZH9AC4EYDVAD');
 defined('ORCID_CLIENT_SECRET') or define('ORCID_CLIENT_SECRET', 'c839f6ee-8991-4b4e-9ae3-aab528adc22c');
 defined('ENABLE_CILOGON_PAGE') or define('ENABLE_CILOGON_PAGE', true); // Cilogon environment variable for enabling/disabling cilogon


require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();