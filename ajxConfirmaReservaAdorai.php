<?php
include '_system/_functionsMain.php';

$infoReserva = $_GET["arrInfo"];
$cod_empresa = 274;
$canalWhats = fnLimpaCampoZero($_GET["cal"]);
$tipoMsg = fnLimpaCampo($_GET["tipoMsg"]);
if ($canalWhats == 0 || $canalWhats == "") {
    $canalWhats = 1;
}

// $retorno = file_get_contents("https://adm.bunker.mk/wsjson/externoAdorai.do?tipo=35&cal=$canalWhats&uuid=$uuid");

$curl = curl_init();

$urlMensagem = "https://bunker.mk/servicoAdorai/mensageria.php?COD_EMPRESA=274&cal=$canalWhats&uuid=" . $infoReserva["uuid"];

curl_setopt_array($curl, array(
    CURLOPT_URL => $urlMensagem,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_HTTPHEADER => array(
        'Cookie: PHPSESSID=g49pkbh0hc9h1et27gcb88hbkrt5fg5oqbq99t9isihssq38m1f0'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
// echo $response;

/* PASSO EXTRA - FAZ O ENVIO DA RESERVA - FIM ************************************************** */

// $msgEnvio = "*Parabéns $infoReserva[nome]*, sua reserva no Roteiros Adorai está confirmada!\r\n\r\nChalé: $infoReserva[chale] \r\nPeríodo: $infoReserva[dat_ini] - $infoReserva[dat_fim] \r\nCidade: Piedade/SP\r\n\r\nNome da propriedade: Adorai Chalés\r\n\r\nPara que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.\r\n\r\nRecomendamos que leia atentamente.\r\n\r\n\r\n1- COMO CHEGAR NO ADORAI CHALÉS:\r\n- Basta consultar no Google Maps ou Waze o destino: Adorai Chalés.\r\n- Endereço: R. Dimas Silva, s/n - Caetezal, Piedade - SP, 18170-000\r\n- Link: https://maps.app.goo.gl/mic8zfNG5T2uc63T7\r\n\r\n\r\n2- DATA DA ESTADIA:\r\n-Confirme no voucher que lhe enviamos por e-mail a data e o chalé que você reservou...\r\n\r\n\r\n3- DIAS E HORÁRIOS DE ENTRADA:\r\n-Check-in - início da estadia: A partir das 16hs.\r\n-Check-out - fim da estadia: Até 12h (meio dia)\r\n\r\n\r\n4- SUPORTE E AJUDA DURANTE A ESTADIA:\r\n- A nossa equipe da recepção entrará em contato 72hrs antes do seu check-in para enviar as informações a respeito de como chegar, as instruções do self-check-in, senhas e o funcionamento do seu chalé.\r\n- Para agilizar ainda mais o seu atendimento, pedimos que durante a sua estadia entre em contato somente através do número pelo qual você fechou a reserva\r\n- Nossa equipe estará disponível das 9 às 21hrs de segunda a quinta, das 9 às 17:00hrs na sexta e das 13 às 21:00hrs aos domingos e feriados. Não temos expediente do restante da equipe aos sábados, mas a recepção terá alguém de plantão para te atender em caso de emergências. 😉\r\n\r\n\r\n5- INTERNET E TV:\r\n- Cada chalé é equipado com um roteador independente.\r\n- A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix.\r\n- A conexão pode oscilar e falhar como em qualquer lugar, entretanto nosso contrato com o provedor garante que exceto em caso de fortes ventos e vendavais, caso haja interrupção eles restabelecem a conexão em até 4 horas.\r\n- A senha da internet estará disponível dentro do chalé.\r\n\r\n\r\n6- REFEIÇÕES:\r\nAs hospedagens funcionam como auto serviço, onde você viverá a experiência como se estivesse em sua casa de campo, veja as opções:\r\nA - Compras no mercadinho no local que funciona 24 horas no sistema de auto-atendimento, você poderá comprar suas refeições, alimentos, carnes, bebidas e sobremesas;\r\nB - Poderá pedir Delivery, pizzas, lanches, comida japonesa, etc…\r\nC - Montar sua cesta café da manhã com os itens da sua preferência;\r\nD - Comer em deliciosos e pitorescos restaurantes da cidade;\r\nE - Fazer um churrasco ou cozinhar no chalé.\r\n\r\nCaso você tenha pago antecipadamente as refeições ou kit foundue, o valor será inserido como crédito em um cartão pré-pago que estará a sua disposição no chalé, com ele você poderá comprar no mercadinho refeições prontas, alimentos, sobremesas e bebidas ou pedir delivery, com isto terá a liberdade de fazer as refeições da forma que preferir!\r\n\r\n\r\n7- PETS:\r\nSeu pet é super bem-vindo, veja algumas regras e orientações:\r\n- Eles não podem subir na cama.\r\n- Recolher e limpar a sujeira é de responsabilidade do hóspede\r\n- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia. Mas a maioria dos cães ficam soltos próximo do chalé.\r\n- Quando temos a visita de pets retiramos os tapetes do chalé.\r\n- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.\r\n\r\n\r\n8- ÁGUA:\r\n- A água do chalé é de poço, nós e os funcionários bebem, mas recomendamos que você traga a sua água mineral.\r\n\r\n\r\n9- MANUTENÇÃO DURANTE A ESTADIA:\r\n-Em caso de pane de equipamentos ou qualquer outro problema durante a hospedagem, nossa equipe fará o máximo possível para que seja solucionado em até 2h.\r\n\r\n\r\n10- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:\r\n- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.\r\n\r\n\r\n11- PRIVACIDADE DOS CHALÉS:\r\n- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.\r\n- Caso queiram usar a banheira ou piscinas dos chalés com janelas abertas é permitido desde que estejam de sungas ou biquínis como numa praia ou clube.\r\n- É proibido intimidades com janelas ou portas abertas.\r\n\r\n\r\n12- SILÊNCIO:\r\n- Adorai Chalés é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.\r\n\r\n\r\n13- USO DAS PISCINAS AQUECIDAS/BANHEIRA E PISCINA COLETIVA:\r\n- Não é permitido o uso de shampoos e sabonetes para fazer espuma nas piscinas aquecidas, seu uso deve ser feito apenas nos chalés que possuem banheira.\r\n- Caso no momento do Check-out seja detectado uso de espumas, sais de banho ou produtos químicos nas piscinas será cobrado no momento do check-out uma taxa de R$500,00.\r\n- A piscina coletiva é de uso comum.\r\n\r\n\r\n14- UTENSÍLIOS DOS CHALÉS:\r\n- Utensílios básicos como: Panelas, pratos, copos, taças de vinho, talheres, abridor de vinho, pano de prato, aparelho de fondue, faca e tábua de churrasco, cafeteira e jogo americano.\r\n- Material de consumo: Sabonete líquido, detergente, bucha, saco de lixo, papel higiênico, rodo, vassoura, pano de chão.\r\n- Toda roupa de cama, banho e cozinha.\r\n- Os hóspedes devem trazer apenas coisas pessoais.\r\n\r\n\r\n15- O QUE NÃO É PERMITIDO:\r\n- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.\r\n- Caso seja detectado uso será cobrado taxa de R$ 500,00 no momento do check-out.\r\n- A voltagem é 127v no chalé, com tomadas de 3 pinos.\r\n\r\n\r\n16- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:\r\n- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.\r\n- Caso não consiga abrir o arquivo, basta acessar esse link: https://docs.google.com/document/d/1SbAQmNyIeE3pjQFzhCHK_G7Hj91CR2lJXlUgLM0FJHY/edit?usp=sharing\r\n\r\n\r\nQualquer dúvida, estamos à disposição. 😉\r\n\r\nObrigado por escolher o Roteiros Adorai. 😊";

// $msgsbtr=  str_replace(["\r\n", "\r", "\n"], '\n', $msgEnvio);

// include "_system/whatsapp/wstAdorai.php";

// $sql = "SELECT *
//         from SENHAS_WHATSAPP
//         WHERE COD_EMPRESA = 274
//         AND COD_UNIVEND = $canalWhats
//         ORDER BY COD_SENHAPARC DESC LIMIT 1";

// $arrayQuery = mysqli_query($connAdm->connAdm(), $sql);

// $qrBuscaModulos = mysqli_fetch_assoc($arrayQuery);

// $session = $qrBuscaModulos['NOM_SESSAO'];
// $des_token = $qrBuscaModulos[DES_TOKEN];
// $des_authkey = $qrBuscaModulos[DES_AUTHKEY];
// $log_login = $qrBuscaModulos[LOG_LOGIN];
// $port = $qrBuscaModulos[PORT_SERVICAO];

// $num_celular = $infoReserva[num_celular];

// $codPais = substr($num_celular, 0, 2);

// if($codPais != "55"){
//     $num_celular = "55".$infoReserva[num_celular];
// }

// $resultcreate = FnsendText($qrBuscaModulos['NOM_SESSAO'], $qrBuscaModulos['DES_AUTHKEY'], $num_celular,$msgsbtr,3,$port);

// header('Content-Type: text/html; charset=utf-8');

// $sql = "SELECT DES_EMAIL FROM SITE_EXTRATO WHERE COD_EMPRESA = $cod_empresa";
// $qrEmail = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql));

// $sqlEmp = "SELECT NOM_FANTASI FROM EMPRESAS WHERE COD_EMPRESA = $cod_empresa";
// $qrEmp = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlEmp));

// include "_system/EMAIL/PHPMailer/PHPMailerAutoload.php";
// include 'externo/email/envio_sac.php';

// $texto="<b>Parabéns $infoReserva[nome]</b>, sua reserva no Roteiros Adorai está confirmada!<br /><br />Chalé: $infoReserva[chale]<br />Período: $infoReserva[dat_ini] - $infoReserva[dat_fim]<br />Cidade: Piedade/SP<br /><br />Nome da propriedade: Adorai Chalés<br /><br />Para que você possa aproveitar sua experiência com maior conforto e tranquilidade, separamos abaixo as dicas e orientações.<br /><br />Recomendamos que leia atentamente.<br /><br /><br />1- COMO CHEGAR NO ADORAI CHALÉS:<br />- Basta consultar no Google Maps ou Waze o destino: Adorai Chalés.<br />- Endereço: R. Dimas Silva, s/n - Caetezal, Piedade - SP, 18170-000<br />- Link: https://goo.gl/maps/fEUmFs8iy2SCydBN8<br /><br /><br />2- DATA DA ESTADIA:<br />-Confirme no voucher que lhe enviamos por e-mail a data e o chalé que você reservou...<br /><br /><br />3- DIAS E HORÁRIOS DE ENTRADA:<br />-Check-in - início da estadia: A partir das 16hs.<br />-Check-out - fim da estadia: Até 12h (meio dia)<br /><br /><br />4- SUPORTE E AJUDA DURANTE A ESTADIA:<br />- A nossa equipe da recepção entrará em contato 72hrs antes do seu check-in para enviar as informações a respeito de como chegar, as instruções do self-check-in, senhas e o funcionamento do seu chalé.<br />- Para agilizar ainda mais o seu atendimento, pedimos que durante a sua estadia entre em contato somente através do número pelo qual você fechou a reserva<br />- Nossa equipe estará disponível das 9 às 21hrs de segunda a quinta, das 9 às 17:00hrs na sexta e das 13 às 21:00hrs aos domingos e feriados. Não temos expediente do restante da equipe aos sábados, mas a recepção terá alguém de plantão para te atender em caso de emergências. 😉<br /><br /><br />5- INTERNET E TV:<br />- Cada chalé é equipado com um roteador independente.<br />- A velocidade é suficiente para fazer videoconferência e assistir Youtube e Netflix.<br />- A conexão pode oscilar e falhar como em qualquer lugar, entretanto nosso contrato com o provedor garante que exceto em caso de fortes ventos e vendavais, caso haja interrupção eles restabelecem a conexão em até 4 horas.<br />- A senha da internet estará disponível dentro do chalé.<br /><br /><br />6- REFEIÇÕES:<br />As hospedagens funcionam como auto serviço, onde você viverá a experiência como se estivesse em sua casa de campo, veja as opções:<br />A - Compras no mercadinho no local que funciona 24 horas no sistema de auto-atendimento, você poderá comprar suas refeições, alimentos, carnes, bebidas e sobremesas;<br />B - Poderá pedir Delivery, pizzas, lanches, comida japonesa, etc…<br />C - Montar sua cesta café da manhã com os itens da sua preferência;<br />D - Comer em deliciosos e pitorescos restaurantes da cidade;<br />E - Fazer um churrasco ou cozinhar no chalé.<br /><br />Caso você tenha pago antecipadamente as refeições ou kit foundue, o valor será inserido como crédito em um cartão pré-pago que estará a sua disposição no chalé, com ele você poderá comprar no mercadinho refeições prontas, alimentos, sobremesas e bebidas ou pedir delivery, com isto terá a liberdade de fazer as refeições da forma que preferir!<br /><br /><br />7- PETS:<br />Seu pet é super bem-vindo, veja algumas regras e orientações:<br />- Eles não podem subir na cama.<br />- Recolher e limpar a sujeira é de responsabilidade do hóspede<br />- Fora do chalé não existem cercas, se precisar, o cão deverá ser preso na guia. Mas a maioria dos cães ficam soltos próximo do chalé.<br />- Quando temos a visita de pets retiramos os tapetes do chalé.<br />- A diária de cães acompanhantes de hóspedes é de R$90,00 por noite.<br /><br /><br />8- ÁGUA:<br />- A água do chalé é de poço, nós e os funcionários bebem, mas recomendamos que você traga a sua água mineral.<br /><br /><br />9- MANUTENÇÃO DURANTE A ESTADIA:<br />-Em caso de pane de equipamentos ou qualquer outro problema durante a hospedagem, nossa equipe fará o máximo possível para que seja solucionado em até 2h.<br /><br /><br />10- LIMPEZA DOS CHALÉS DURANTE A ESTADIA:<br />- O Chalé será entregue limpo e higienizado, a limpeza e arrumação durante o período de locação será de responsabilidade do hóspede.<br /><br /><br />11- PRIVACIDADE DOS CHALÉS:<br />- Cada chalé tem entrada independente e não há necessidade de contato entre hóspedes.<br />- Caso queiram usar a banheira ou piscinas dos chalés com janelas abertas é permitido desde que estejam de sungas ou biquínis como numa praia ou clube.<br />- É proibido intimidades com janelas ou portas abertas.<br /><br /><br />12- SILÊNCIO:<br />- Adorai Chalés é um espaço de relaxamento e paz, portanto, casais, famílias e grupos, antes de alugar precisam concordar com Silêncio. Os sons do seu chalé, música e vozes, não podem ser ouvidos pelos seus vizinhos. Essa regra é muito importante para nossos hóspedes, pois é um espaço para descansar e curtir a natureza e os confortos que oferece.<br /><br /><br />13- USO DAS PISCINAS AQUECIDAS/BANHEIRA E PISCINA COLETIVA:<br />- Não é permitido o uso de shampoos e sabonetes para fazer espuma nas piscinas aquecidas, seu uso deve ser feito apenas nos chalés que possuem banheira.<br />- Caso no momento do Check-out seja detectado uso de espumas, sais de banho ou produtos químicos nas piscinas será cobrado no momento do check-out uma taxa de R$500,00.<br />- A piscina coletiva é de uso comum.<br /><br /><br />14- UTENSÍLIOS DOS CHALÉS:<br />- Utensílios básicos como: Panelas, pratos, copos, taças de vinho, talheres, abridor de vinho, pano de prato, aparelho de fondue, faca e tábua de churrasco, cafeteira e jogo americano.<br />- Material de consumo: Sabonete líquido, detergente, bucha, saco de lixo, papel higiênico, rodo, vassoura, pano de chão.<br />- Toda roupa de cama, banho e cozinha.<br />- Os hóspedes devem trazer apenas coisas pessoais.<br /><br /><br />15- O QUE NÃO É PERMITIDO:<br />- Proibido narguilé e cigarro de qualquer tipo dentro dos chalés, fumar somente é permitido ao ar livre, e as bitucas devem ser apagadas na água e jogadas no lixo.<br />- Caso seja detectado uso será cobrado taxa de R$ 500,00 no momento do check-out.<br />- A voltagem é 127v no chalé, com tomadas de 3 pinos.<br /><br /><br />16- POLÍTICA DE CANCELAMENTO E REAGENDAMENTO:<br />- Todas as informações a respeito de cancelamento, reembolso e reagendamento estão descritas no voucher e em nosso contrato de hospedagem que está anexo logo abaixo.<br />- Caso não consiga abrir o arquivo, basta acessar esse link: https://docs.google.com/document/d/1SbAQmNyIeE3pjQFzhCHK_G7Hj91CR2lJXlUgLM0FJHY/edit?usp=sharing<br /><br /><br />Qualquer dúvida, estamos à disposição. 😉<br /><br />Obrigado por escolher o Roteiros Adorai. 😊";

// $email['email1'] = $infoReserva[email];

// $retorno = fnsacmail(
//       $email,
//       'Roteiros Adorai',
//       "<html>".$texto."</html>",
//       "Confirmação de Reserva",
//       "roteirosadorai.com.br",
//       $connAdm->connAdm(),
//       connTemp($cod_empresa,""),$cod_empresa);

// if($resultcreate != ''){
// 	$sqlConfirma = "UPDATE ADORAI_PEDIDO SET
// 					LOG_CONFIRMA = 'S'
// 					WHERE UUID = '$infoReserva[uuid]'";
// 	mysqli_query(connTemp($cod_empresa,''),$sql);
// }
