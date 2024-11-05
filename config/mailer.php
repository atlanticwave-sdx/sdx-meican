<?php
/**
 * @copyright Copyright (c) 2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

return [
    'class' => 'yii\swiftmailer\Mailer',
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'XXX_SMTP_HOST_XXX',
        'username' => 'XXX_SMTP_USER_XXX',
        'password' => 'XXX_SMTP_PASS_XXX',
        'port' => 'XXX_SMTP_PORT_XXX',
        'encryption' => 'ssl',
    ],
];
