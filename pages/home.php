

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

                $quoteInformations = array($originValue, $originValue * $payRate,  $originValue * $conversionRate, $conversionValue, $destinationCurrency, $valorComprado) ;
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
            <input type="hidden" name="quote" value="<?= implode('|', $quoteInformations)?>">
            <div class="input-group-append">
            <div class="row">
              <div class="col-6"><button type="button" name="checkout" class="btn btn-success btn-lg ">Finalizar</button></div>
              <div class="col-6"><button type="submit" name="send_mail" class="btn btn-info btn-lg ">E-mail</button></div>
            </div>
            </div>
          </form>
        <?php }
        if (isset($_POST['send_mail'])) {
            $quoteInformations = $vetor = explode('|', $_POST['quote']);
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
                          <span class="text-muted">R$' . Painel::convertMoney($quoteInformations[0]) . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">Taxa de Pagamento</h6>
                          </div>
                          <span class="text-muted">R$' . Painel::convertMoney($quoteInformations[1]) . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                          <div>
                            <h6 class="my-0">Taxa de Conversão</h6>
                          </div>
                          <span class="text-muted">R$' . Painel::convertMoney($quoteInformations[2]) . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-light">
                          <div class="text-success">
                            <h6 class="my-0">Valor Convertido</h6>
                          </div>
                          <span class="text-success">R$' . Painel::convertMoney($quoteInformations[3]) . '</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                          <span>Valor Comprado (' . $quoteInformations[4] . ')</span>
                          <strong>$' . Painel::convertMoney($quoteInformations[5]) . '</strong>
                        </li>
                      </ul>

            ';
            $info = array('assunto' => $assunto,'corpo' => $corpo);
            $mail = new Email(SERVER_MAIL, MAIL_SENDER, PASSWORD_MAIL, 'Cotação');
            $mail->addAdress('contato@dansol.com.br', 'Destinatário do E-mail');
            $mail->formatarEmail($info);
            if ($mail->enviarEmail()) {
                Painel::alertJS('Cotação enviada!');
                Painel::redirect(INCLUDE_PATH);
            } else {
                Painel::alertJS('Ocorrou um erro ao enviar!');
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