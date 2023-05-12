<?php
/**
 * @copyright Copyright (c) 2016 RNP
 * @license http://github.com/ufrgs-hyman/meican#license
 */

return [
    'class' => 'yii\swiftmailer\Mailer',
    'transport' => [
        'class' => 'Swift_SmtpTransport',
        'host' => 'smtp.gmail.com',
        'username' => 'meican.sdx@gmail.com',
        'password' => 'hfnpjukwikpltiks',
        'port' => '465',
        'encryption' => 'ssl',
    ],
];
