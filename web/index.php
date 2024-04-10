<?php
/**
 * @copyright Copyright (c) 2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

// comment out the following two lines when deployed to production
//defined('YII_DEBUG') or define('YII_DEBUG', true);
//defined('YII_ENV') or define('YII_ENV', 'dev');
defined('MEICAN_URL') or define('MEICAN_URL', 'XXX_MEICAN_URL_XXX');
defined('API_URL') or define('API_URL', 'XXX_SDX_CONTROLLER_URL_XXX');
defined('ORCID_CLIENT_ID') or define('ORCID_CLIENT_ID', 'XXX_ORCID_CLIENT_ID_XXX');
defined('ORCID_CLIENT_SECRET') or define('ORCID_CLIENT_SECRET', 'XXX_ORCID_CLIENT_SECRET_XXX');
defined('ENABLE_CILOGON_PAGE') or define('ENABLE_CILOGON_PAGE', true); // Cilogon environment variable for enabling/disabling cilogon

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
