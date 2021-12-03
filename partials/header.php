<?php

include('config.php');

?>
<!doctype html>
<html lang="pt-br">
  <head>
    <title>Câmbio</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="https://getbootstrap.com/docs/4.0/examples/checkout/form-validation.css">
    <link rel="stylesheet" href="../css/style.css">
  </head>
  <body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="<?= INCLUDE_PATH ?>">Money Câmbio</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="<?= INCLUDE_PATH ?>">Home <span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= INCLUDE_PATH ?>login">Login</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?= INCLUDE_PATH ?>cadastro">Cadastro</a>
          </li>
        </ul>
        <span class="navbar-text">
          Aqui vai um texto
        </span>
      </div>
    </nav>
    <div class="container">
    <div class="py-5 text-center">
        <img class="d-block mx-auto mb-4" src="<?= INCLUDE_PATH ?>img/money.png" alt="" width="72" height="72">
        <h2>Comprar Moeda Estrangeira em Espécie</h2>
        <p class="lead">Compre online Dólar, Euro, Libra e muitas outras moedas estrangeiras em espécie. Receba no conforto do seu lar ou em sua empresa.</p>
      </div>