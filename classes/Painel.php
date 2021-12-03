<?php

class Painel
{


    //Formatar para mostra como dinheiro retirando do banco
    public static function convertMoney($valor)
    {
        return number_format($valor, 2, ',', '.');
    }


    //Formatar para incluir no banco
    public static function formatarMoedaBd($valor)
    {
        $valor = str_replace('.', '', $valor);
        $valor = str_replace(',', '.', $valor);
        return $valor;
    }

    public static function logado()
    {
        return isset($_SESSION['login']) ? true : false;
    }

    public static function logout()
    {
        setcookie('lembrar', 'true', time() - 1, '/');
        session_destroy();
        header('Location: ' . INCLUDE_PATH_PAINEL);
    }


    public static function alertJS($msg)
    {
        echo '<script>alert("' . $msg . '")</script>';
    }

    public static function redirect($url)
    {
        echo '<script>location.href="' . $url . '"</script>';
        die();
    }
}
