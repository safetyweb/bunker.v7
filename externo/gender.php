<?php
include '../_system/_functionsMain.php';
$cod = $_REQUEST['empresa'];
$conncliente = connTemp($cod, '');
$sqlclientes = "SELECT * FROM clientes WHERE COD_empresa=$cod  and COD_SEXOPES in (0,3)";
$rwclientes = mysqli_query($conncliente, $sqlclientes);

while ($rscliente = mysqli_fetch_assoc($rwclientes)) {
    ob_start();
    unset($teste1);
    $teste1 = explode(' ', strtoupper($rscliente['NOM_CLIENTE']));
    //    sleep(1);
    echo '<pre>';
    print_r($teste1);
    echo '</pre>';

    $response = file_get_contents("https://adm.bunker.mk/wsjson/gender.do?login=diogo.master&senha=123456&idcliente=2&NOME=" . $teste1[0]);
    //  $response=file_get_contents("https://api.genderize.io/?name=".$teste1[0]);
    $teste = json_decode($response, TRUE);

    echo '<pre>';
    print_r($teste);
    echo '</pre>';

    if ($teste['Sexo_maiorPorcentagem'] == 'M' || $teste['Sexo_maiorPorcentagem'] == 'm') {
        $sexo = '1';
    } elseif ($teste['Sexo_maiorPorcentagem'] == 'F' || $teste['Sexo_maiorPorcentagem'] == 'f') {

        $sexo = '2';
    } else {
        $sexo = '3';
    }
    /*
        if($teste['gender']=='male')
        {
            $sexo='1'; 
        }elseif ($teste['gender']=='female') {    
           $sexo='2'; 
        }else{
            $sexo='3'; 
        }
         * 
         */
    if ($rscliente['COD_SEXOPES'] != $sexo) {
        echo "<br>.........................................................<br>";
        echo "CPF: " . $rscliente['NUM_CGCECPF'] . '<br>';
        echo "NOME: " . $rscliente['NOM_CLIENTE'] . '<br>';
        echo "SEXO_atual: " . $rscliente['COD_SEXOPES'] . '<br>';
        echo "SEXO_NOVO: " . $sexo . '<br>';
        echo "<br>.........................................................<br>";
        $update = "UPDATE clientes SET COD_SEXOPES='$sexo' WHERE COD_CLIENTE=$rscliente[COD_CLIENTE] and cod_empresa=$cod";
        mysqli_query($conncliente, $update);
    }
    $codigo = $rscliente['COD_CLIENTE'];


    ob_end_flush();
    ob_flush();
    flush();
}
echo $codigo;
