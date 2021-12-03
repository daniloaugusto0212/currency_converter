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

      <div class="row">
        <div class="col-md-4 order-md-2 mb-4">
        <?php
        if (isset($_POST['action'])) {
            $originValue = $_POST['origin_value'];
            //Verificar se o valor está entre 900 e 900.000
            if (!($originValue > 900 && $originValue < 900000)) {
                die('Valor está fora dos parâmetros');
            } else {
                $originCurrency = 'BRL';
                $destinationCurrency = $_POST['destination_currency'];
                $paymentMethod = $_POST['payment_method'];


                //Aplicar taxa de acordo com o método de pagamento
                if ($paymentMethod == 'boleto') {
                    $payRate = 0.0137;
                } else {
                    $payRate = 0.0773;
                }

                if ($originValue < 2700) {
                    //Se o valor for abaixo de 2700, aplicar taxa de conversão de 2% (somente sobre o valor da compra)
                    $conversionRate = 0.02;
                } elseif ($originValue > 4000) {
                    //Se o valor for acima de 4000, aplicar taxa de conversão de 1% (somente sobre o valor da compra)
                    $conversionRate = 0.01;
                } else {
                    $conversionRate = 0;
                }

                $conversionValue = $originValue - ($originValue * $payRate) - ($originValue * $conversionRate);

                $dadosCotacao = $obEconomia->consultarCotacao($originCurrency, $destinationCurrency);

                $dadosCotacao = $dadosCotacao[$originCurrency . $destinationCurrency] ?? [];

                $conversao = $dadosCotacao['bid'];

                $valorComprado = $conversionValue * $conversao;

                $quoteInformation = array($originValue, $originValue * $payRate,  $originValue * $conversionRate, $conversionValue, $destinationCurrency, $valorComprado) ;
            }

            ?>
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Informações de Compra</span>
          </h4>
          <ul class="list-group mb-3">
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Valor Pago</h6>
              </div>
              <span class="text-muted">R$<?= Painel::convertMoney($originValue) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Taxa de Pagamento</h6>
              </div>
              <span class="text-muted">R$<?= Painel::convertMoney($originValue * $payRate) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                <h6 class="my-0">Taxa de Conversão</h6>
              </div>
              <span class="text-muted">R$<?= Painel::convertMoney($originValue * $conversionRate) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between bg-light">
              <div class="text-success">
                <h6 class="my-0">Valor Convertido</h6>
              </div>
              <span class="text-success">R$<?= Painel::convertMoney($conversionValue) ?></span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Valor Comprado (<?= $destinationCurrency ?>)</span>
              <strong>$<?= Painel::convertMoney($valorComprado) ?></strong>
            </li>
          </ul>
          <form method="POST" class="card p-2">
            <input type="hidden" name="quote" value="<?= implode('|', $quoteInformation)?>">
            <div class="input-group-append">
            <div class="row">
              <div class="col-6"><button type="button" name="checkout" class="btn btn-success btn-lg ">Finalizar</button></div>
              <div class="col-6"><button type="submit" name="send_mail" class="btn btn-info btn-lg ">E-mail</button></div>
            </div>
            </div>
          </form>
        <?php }
        if (isset($_POST['send_mail'])) {
            $quoteInformation = $vetor = explode('|', $_POST['quote']);
            $assunto = 'Cotação de Câmbio';
            $corpo = '<h3>Cotação solicitada</h3>
                      <p>Você está recebendo a cotação efetuado em nossa plataforma</p>
                      <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Informações de Compra</span>
                      </h4>
                      <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">Valor Pago</h6>
                          </div>
                          <span class="text-muted">R$' . Painel::convertMoney($quoteInformation[0]) . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">Taxa de Pagamento</h6>
                          </div>
                          <span class="text-muted">R$' . Painel::convertMoney($quoteInformation[1]) . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">Taxa de Conversão</h6>
                          </div>
                          <span class="text-muted">R$' . Painel::convertMoney($quoteInformation[2]) . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-light">
                          <div class="text-success">
                            <h6 class="my-0">Valor Convertido</h6>
                          </div>
                          <span class="text-success">R$' . Painel::convertMoney($quoteInformation[3]) . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                          <span>Valor Comprado (' . $quoteInformation[4] . ')</span>
                          <strong>$' . Painel::convertMoney($quoteInformation[5]) . '</strong>
                        </li>
                      </ul>

            ';
            $info = array('assunto' => $assunto,'corpo' => $corpo);
            $mail = new Email(SERVER_MAIL, MAIL_SENDER, PASSWORD_MAIL, 'Sistema Acabamento');
            $mail->addAdress('contato@dansol.com.br', 'Sistema Acabamento');
            $mail->formatarEmail($info);
            if ($mail->enviarEmail()) {
                Painel::alertJS('Cotação enviada!');
                Painel::redirect(INCLUDE_PATH);
            } else {
                echo 'erro';
            }
        }
        ?>
          
        </div>
        <div class="col-md-8 order-md-1">

          <h4 class="mb-3">Comprar Moeda</h4>
          <form method="POST" class="needs-validation" novalidate>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="origin_value">Moeda de origem</label>
                <input type="text" class="form-control" id="origin_value" name="origin_value" placeholder="Valor da compra..." value="" required>
                <div class="invalid-feedback">
                    Digite o valor de compra
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="destination_currency">Moeda de destino</label>
                <select class="custom-select d-block w-100" name="destination_currency"id="destination_currency" required>
                  <option value="">Selecione</option>
                  <option value="USD">Dólar</option>
                  <option value="EUR">Euro</option>
                  <option value="RUB">Rublo Russo</option>
                </select>
                <div class="invalid-feedback">
                    Selecione a moeda de destino
                </div>
              </div>
            </div>

            <h4 class="mb-3">Método de Pagamento</h4>

            <div class="d-block my-3">
              <div class="custom-control custom-radio">
                <input id="credit" name="payment_method" value="credit" type="radio" class="custom-control-input" required>
                <label class="custom-control-label" for="credit">Cartão de Crédito</label>
              </div>
              <div class="custom-control custom-radio">
                <input id="boleto" name="payment_method" value="boleto" type="radio" class="custom-control-input" required>
                <label class="custom-control-label" for="boleto">Boleto</label>
              </div>
            </div>

            <hr class="mb-4">
            <button class="btn btn-primary btn-lg" name="action" type="submit">Calcular</button>
        </form>
        </div>
      </div>

      <footer class="my-5 pt-5 text-muted text-center text-small">
        <p class="mb-1">&copy; 2017-2018 Company Name</p>
        <ul class="list-inline">
          <li class="list-inline-item"><a href="#">Privacy</a></li>
          <li class="list-inline-item"><a href="#">Terms</a></li>
          <li class="list-inline-item"><a href="#">Support</a></li>
        </ul>
      </footer>
    </div>

      
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/holder.min.js"></script>
    <script>
      // Example starter JavaScript for disabling form submissions if there are invalid fields
      (function() {
        'use strict';

        window.addEventListener('load', function() {
          // Fetch all the forms we want to apply custom Bootstrap validation styles to
          var forms = document.getElementsByClassName('needs-validation');

          // Loop over them and prevent submission
          var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
              if (form.checkValidity() === false) {
                event.preventDefault();
                event.stopPropagation();
              }
              form.classList.add('was-validated');
            }, false);
          });
        }, false);
      })();
    </script>
  </body>
</html>