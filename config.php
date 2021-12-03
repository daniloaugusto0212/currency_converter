<?php

session_start();
date_default_timezone_set('America/Sao_Paulo');
$autoload = function ($class) {
    if ($class == 'Email') {
        require_once('classes/phpmailer/PHPMailerAutoload.php');
    }
    include('classes/' . $class . '.php');
};

spl_autoload_register($autoload);

$url = explode('/', $_SERVER['REQUEST_URI']);

//INSTÂNCIA DA CLASSE DE API
$obEconomia = new Economia();


//Localhost
define('INCLUDE_PATH', 'http://localhost/cambio/');
define('INCLUDE_PATH_PAINEL', INCLUDE_PATH . 'painel/');

//Conectar com o banco de dados
define('HOST', 'localhost');
define('USER', 'root');
define('PASSWORD', '');
define('DATABASE', 'cambio');

//Configurações de E-mail

define('SERVER_MAIL', 'smtp.hostinger.com.br');
define('MAIL_SENDER', 'noreply@dansol.com.br');
define('PASSWORD_MAIL', '*****');
