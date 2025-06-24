<?php

//ini_set('output_buffering',4092);
//ini_set('post_max_size', '512M');
//ini_set('max_execution_time', '30');
//date_default_timezone_set('America/Sao_Paulo');
date_default_timezone_set('Etc/GMT+3');
ini_set('default_charset', 'UTF-8');
ignore_user_abort(true);
ini_set("default_socket_timeout", 10);
//ini_set('default_socket_timeout', 30);
//ini_set('soap.wsdl_cache_enabled', '0'); 
//ini_set('soap.wsdl_cache_ttl', '0');
//ini_set("soap.wsdl_cache_enabled", "0");


function array_to_xml($array, &$xml_user_info)
{
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            if (!is_numeric($key)) {
                $subnode = $xml_user_info->addChild("$key");
                array_to_xml($value, $subnode);
            } else {
                $subnode = $xml_user_info->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        } else {
            $xml_user_info->addChild("$key", htmlspecialchars("$value"));
        }
    }
}
function fnEncode($pure_string)
{
    $dirty = array("+", "/", "=");
    $clean = array("p£", "s£", "¢");
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $_SESSION['iv'] = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, '123456', utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv_size);
    $encrypted_string = base64_encode($encrypted_string);
    return trim(str_replace($dirty, $clean, $encrypted_string));
}

//-----------FIM
//
//-------------DECRYPT       
function fnDecode($encrypted_string)
{
    $dirty = array("+", "/", "=");
    $clean = array("p£", "s£", "¢");
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $string = base64_decode(str_replace($clean, $dirty, $encrypted_string));
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, '123456', $string, MCRYPT_MODE_ECB, $iv_size);
    return trim($decrypted_string);
}
function recursive_array_search($needle, $haystack)
{
    foreach ($haystack as $key => $value) {
        $current_key = $key;
        if ($needle === $value or (is_array($value) && recursive_array_search($needle, $value) !== false)) {
            return $current_key;
        }
    }
    return false;
}
function fnMemInicial($conn, $opcao, $user)
{
    $datahora = DATE("d/m/Y H:i:s");
    if ($opcao == "true") {

        $mem_usage = memory_get_usage(true);

        if ($mem_usage < 1024) {

            $logqueryinsert = 'insert into teste_marka.log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("' . $mem_usage . " bytes" . '","' . $_GET['mod'] . '","' . $datahora . '","' . $user . '");';
            mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
        } elseif ($mem_usage < 1048576) {

            $logqueryinsert = 'insert into teste_marka.log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("' . round($mem_usage / 1024, 2) . " kilobytes" . '","' . $_GET['mod'] . '","' . $datahora . '","' . $user . '");';
            mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
        } else {

            $logqueryinsert = 'insert into teste_marka.log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario) values ("' . round($mem_usage / 1048576, 2) . " megabytes" . '","' . $_GET['mod'] . '","' . $datahora . '","' . $user . '");';
            mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
        }
    }
}
function fngravalogxml($array)
{
    $inserarray = 'INSERT INTO ' . $array['tables'] . ' (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,NUM_CGCECPF,DES_VENDA,COD_PDV,CUPOM)values
                    ("' . date("Y-m-d H:i:s") . '","' . $_SERVER['REMOTE_ADDR'] . '","' . $_SERVER['REMOTE_PORT'] . '",
                     "' . $array['cod_usuario'] . '","' . $array['login'] . '","' . $array['cod_empresa'] . '","' . $array['idloja'] . '","' . $array['idmaquina'] . '","' . $array['cpf'] . '","' . $array['xml'] . '","' . $array['pdv'] . '","' . $array['cupom'] . '")';
    $arraP = mysqli_query($array['conn'], $inserarray);
    $COD_LOG = mysqli_insert_id($array['conn']);
    return $COD_LOG;
}

function Grava_log_msgxml($conn, $table, $id_log, $MSG, $retorno)
{
    $msg1 = 'INSERT INTO ' . $table . ' (ID,DATA_HORA,MSG,origem_retorno)values
                   (' . $id_log . ',"' . date("Y-m-d H:i:s") . '","' . $MSG . '","' . $retorno . '")';
    mysqli_query($conn, $msg1);
    return $msg1;
}
function fnLimpaCampoZero($campo)
{

    if ($campo == "" || is_int($campo) || empty($campo) || !is_numeric($campo) || is_null($campo)) {

        //return $campo= (int)0;

        $campo = 0;
        return $campo;
    } else {

        return $campo;
    }
}

function fnformatavalorretorno($Num)
{
    $valor = str_replace(".", "", $Num);
    $valor = str_replace(",", ".", $Num);
    $valor = number_format($valor, 2, ",", ".");
    return $valor; //retorna o valor formatado para gravar no banco 

}
function fnvalorretorno($Num, $dec)
{
    //$valor = str_replace(".", "", $Num);
    //$valor = str_replace(",", ".", $Num); 
    //$valor=number_format ($valor,$dec,",",".");
    // return $valor; //retorna o valor formatado para gravar no banco 
    // $valor = str_replace(".", "", $Num);
    $valor = str_replace(",", ".", $Num);
    $valor = bcmul($valor, '100', $dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
    $valor = bcdiv($valor, '100', $dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
    $valor = number_format($valor, $dec, ",", ".");
    // return $valor; //retorna o valor formatado para gravar no banco 

    return $valor; //retorna o valor formatado para gravar no banco  
}

//calcular idade
function calc_idade($data_nasc)
{

    $data_nasc = explode("/", $data_nasc);

    $data = date("d/m/Y");

    $data = explode("/", $data);

    $anos = $data[2] - $data_nasc[2];

    if ($data_nasc[1] > $data[1]) {

        return $anos - 1;
    }
    if ($data_nasc[1] == $data[1]) {

        if ($data_nasc[2] <= $data[2]) {

            return $anos;
        } else {

            return $anos - 1;
        }
    }
    if ($data_nasc[1] < $data[1]) {

        return $anos;
    }
}

//========================================================
function fnValorSQL($Num, $Dec)
{
    $valor = str_replace(",", ".", $Num);
    $valor = number_format($valor, $Dec, ".", ",");
    //echo $valor; //retorna o valor formatado para apresentação em tela  
    return $valor;
}

/*function fnFormatvalor($Num,$dec)
{ 
  //if (empty($Num) || is_null($Num) ) {$Numero = 0;} else {$Numero = $Num;}		
  $valor = str_replace(".", "", $Num);
  $valor = str_replace(",", ".", $Num); 
 // $valor=number_format ($valor,3,".",".");
 $valor = bcmul($valor, '100', $dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
 $valor = bcdiv($valor, '100', $dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
 //$valor=number_format ($valor,2,".","");
 return $valor; //retorna o valor formatado para gravar no banco 
}    
*/
function fnFormatvalor($brl, $casasDecimais = 2)
{
    // Se já estiver no formato USD, retorna como float e formatado
    if (preg_match('/^\d+\.{1}\d+$/', $brl))
        return (float) number_format($brl, $casasDecimais, '.', '');
    // Tira tudo que não for número, ponto ou vírgula
    $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
    // Tira o ponto
    $decimal = str_replace('.', '', $brl);
    // Troca a vírgula por ponto
    $decimal = str_replace(',', '.', $decimal);
    return (float) number_format($decimal, $casasDecimais, '.', '');
}



function fnlimpaCPF($valor)
{
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

function fnlimpaCEP($valor)
{
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

function fn_calvenda($arrayiten, $dec)
{
    $valortotalbruto = fnFormatvalor($arrayiten['valortotalbruto'], $dec);
    $descontototalvalor = fnFormatvalor($arrayiten['descontototalvalor'], $dec);
    $valortotalliquido = fnFormatvalor($arrayiten['valortotalliquido'], $dec);
    $cod = $valortotalbruto - $descontototalvalor;
    $cod = number_format($cod, $dec, ".", "");

    if (rtrim(trim(fnFormatvalor($valortotalliquido, $dec))) == $cod) {
        $retorno = 1;
        return $retorno;
    } else {
        return $cod1;
    }
}
function fn_calValor($arrayiten, $dec)
{

    if (count($arrayiten['itens']['vendaitem']['quantidade']) == 1) {
        $vltotal = fnFormatvalor($arrayiten['valortotalbruto'], $dec);

        $quantidade = fnFormatvalor($arrayiten['itens']['vendaitem']['quantidade'], $dec);
        $valor = fnFormatvalor($arrayiten['itens']['vendaitem']['valorliquido'], $dec);
        $vl = $valor * $quantidade;
        $diferenca = $vltotal - fnFormatvalor($vl, $dec);
        $diferenca = fnValorSQL($diferenca, $dec);
        $diferenca = str_replace("-", " ", $diferenca);
        $diferenca = str_replace(",", "", $diferenca);
        if ($diferenca <= '2.00') {
            $retorno = 1;
            return $retorno;
        } else {
            return $vl;
        }
    } else {
        $vltotal = fnFormatvalor($arrayiten['valortotalbruto'], $dec);

        foreach ($arrayiten['itens']['vendaitem'] as $key => $chave) {
            $cod[] = fnFormatvalor($chave['valorliquido'], $dec) * fnFormatvalor($chave['quantidade'], $dec);
        }
        $sum = array_sum($cod);

        $diferenca = $vltotal - $sum;
        $diferenca = fnValorSQL($diferenca, $dec);
        $diferenca = str_replace("-", " ", $diferenca);
        $diferenca = str_replace(",", "", $diferenca);
        if ($diferenca <= '2.00') {
            //if($arrayiten['cartao']=='01734200014')
            //{
            //	return $diferenca ;
            //}else{
            $retorno = 1;
            return $retorno;
            //}
        } else {
            return $sum;
        }
    }
}
function fnmemoria($conn, $opcao, $user, $pagina, $empresa)
{


    $datahora = DATE("d/m/Y H:i:s");

    $mem_usage = memory_get_usage(true);
    if ($opcao == "true") {

        $mtimei = time();

        $mem_usage = memory_get_usage(true);

        if ($mem_usage < 1024) {

            $logqueryinsert = 'insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario,EMPRESA) values ("' . $mem_usage . " bytes" . '","' . $pagina . '","' . $datahora . '","' . $user . '","' . $empresa . '");';
            mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
            $COD_log = mysqli_insert_id($conn);
            return $COD_log;
        } elseif ($mem_usage < 1048576) {

            $logqueryinsert = 'insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario,EMPRESA) values ("' . round($mem_usage / 1024, 2) . " kilobytes" . '","' . $pagina . '","' . $datahora . '","' . $user . '","' . $empresa . '");';
            mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
            $COD_log = mysqli_insert_id($conn);
            return $COD_log;
        } else {

            $logqueryinsert = 'insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario,EMPRESA) values ("' . round($mem_usage / 1048576, 2) . " megabytes" . '","' . $pagina . '","' . $datahora . '","' . $user . '","' . $empresa . '");';
            mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
            $COD_log = mysqli_insert_id($conn);
            return $COD_log;
        }
    }
}
function fnmemoriafinal($conn, $ID)
{
    $mtimef = time();

    $finaltime = $mtimef - $mtimei;
    // $finaltime1=(microtime(TRUE) - $time);

    $tempo_carregamento = round((microtime(true) - $_SERVER['REQUEST_TIME']), 5);


    $mem_usage = memory_get_usage(true);

    if ($mem_usage < 1024) {
        $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', TP_CARGA='" . $finaltime . "',NEM_FINAL='" . $mem_usage . "',ativo=1 WHERE ID=$ID and ativo='0'";
        mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    } elseif ($mem_usage < 1048576) {
        $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', TP_CARGA='" . $finaltime . "',NEM_FINAL='" . round($mem_usage / 1024, 2) . " kilobytes" . "',ativo=1 WHERE ID=$ID and ativo=0";
        mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    } else {
        $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', TP_CARGA='" . $finaltime . "',NEM_FINAL='" . round($mem_usage / 1048576, 2) . " megabytes" . "',ativo=1 WHERE ID=$ID and  ativo=0";
        mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    }
    //Picos de memoria
    $mem_usage = memory_get_peak_usage(true);

    if ($mem_usage < 1024) {
        $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', TP_CARGA='" . $finaltime . "',NEM_PICO='" . $mem_usage . "',MEN_PICO=1 WHERE ID=$ID and MEN_PICO='0'";
        mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    } elseif ($mem_usage < 1048576) {
        $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', TP_CARGA='" . $finaltime . "',NEM_PICO='" . round($mem_usage / 1024, 2) . " kilobytes" . "',MEN_PICO=1 WHERE ID=$ID and MEN_PICO=0";
        mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    } else {
        $SqlUpdate = "UPDATE log_men SET TP_CARGA_PAGINA='" . $tempo_carregamento . "', TP_CARGA='" . $finaltime . "',NEM_PICO='" . round($mem_usage / 1048576, 2) . " megabytes" . "',MEN_PICO=1 WHERE  ID=$ID and MEN_PICO=0";
        mysqli_query($conn, $SqlUpdate) or die(mysqli_error());
    }
    return $SqlUpdate;
}

function fncompletadoc($cpfcnpj, $tipo)
{
    $tipo = strtoupper($tipo);
    if ($tipo = 'F') {
        $retun = str_pad($cpfcnpj, 11, '0', STR_PAD_LEFT); // Resultado: 00009   
        return $retun;
    } elseif ($tipo = 'J') {
        $retun = str_pad($cpfcnpj, 14, '0', STR_PAD_LEFT); // Resultado: 00009   
        return $retun;
    } else {
        return $cpfcnpj;
    }
}
function fn_consultaBase($array)
{

    //consulta cliente 
    ///////////////////////////////////////////////////////////////////////////////////////////////////
    if ($array['empresa'] == '19') {

        if (trim($array['generico']) != "" || trim($array['tokem']) != "") {

            $sql = "select clientes.COD_CLIENTE from  clientes
                         inner join veiculos on veiculos.COD_CLIENTE_EXT=clientes.num_cartao
                         where  clientes.COD_EMPRESA='" . $array['empresa'] . "' and veiculos.DES_PLACA ='" . $array['generico'] . "'";
            $RScliente = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $sql));
            if ($RScliente['COD_CLIENTE'] <= 0) {
                $sql1 = "SELECT C.COD_CLIENTE FROM clientes C 
                            INNER JOIN tokem T ON T.cod_cliente=C.NUM_CARTAO 
                            WHERE T.des_tokem='" . $array['tokem'] . "' AND COD_EMPRESA='" . $array['empresa'] . "'";
                $RScliente = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $sql1));
            }
            $codcliente = "COD_EMPRESA='" . $array['empresa'] . "' and COD_CLIENTE=" . $RScliente['COD_CLIENTE'];
        } else {
            $codcliente = '';
            $msg = 'Cliente  não localizado!';
        }

        if ($array['venda'] == 'venda') {
            $cpf = 'COD_EMPRESA="' . $array['empresa'] . '" and NUM_CGCECPF="' . $array['cpf'] . '"';
        } else {
            if (trim($array['cartao']) != "") {
                $cartao = 'COD_EMPRESA="' . $array['empresa'] . '" and NUM_CARTAO="' . $array['cartao'] . '"';
            } else {
                $cartao = '';
                $msg = 'Cliente  não localizado!';
            }

            if (trim($array['cpf']) != "") {
                $cpf = 'COD_EMPRESA="' . $array['empresa'] . '" and NUM_CGCECPF="' . $array['cpf'] . '"';
                $cartao = '';
            } else {
                $cpf = '';
                $msg = 'Cliente  não localizado!';
            }
        }
        $sqlconsultaBase = "SELECT count(COD_CLIENTE)as contador, clientes.* FROM clientes where $cpf $cartao $codcliente";

        /*  $sqlconsultaBase= "SELECT count(COD_CLIENTE)as contador, clientes.* FROM clientes where 
                                               case 
						    when num_cartao='".$array['cpf']."' and COD_EMPRESA='".$array['empresa']."' then  1
                                                    when num_cgcecpf='".$array['cartao']."'  and COD_EMPRESA='".$array['empresa']."' then 2
                                                    when COD_CLIENTE= '".$RScliente['COD_CLIENTE']."' and COD_EMPRESA='".$array['empresa']."' then  3	
                                               ELSE 0 END  IN (1,2,3)";
               */
        $row1 = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $sqlconsultaBase));
        //  fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$sqlconsultaBase);


    } else {
        if ($array['venda'] == 'venda') {

            if (trim($array['cartao']) != "") {
                $cartao = 'COD_EMPRESA="' . $array['empresa'] . '" and (NUM_CARTAO="' . $array['cartao'] . '" or NUM_CGCECPF="' . $array['cpf'] . '")';
            } else {
                $cartao = '';
            }
            //if(trim($array['cpf'])!=""){$CPF='COD_EMPRESA="'.$array['empresa'].'" and NUM_CGCECPF="'.$array['cpf'].'"';}else{$CPF='';}
            //if(trim($array['cpf'])==''){$cpfcartao=$array['cartao'];}else{$cpfcartao=$array['cpf'];} 
            $sqlconsultaBase = "SELECT count(COD_CLIENTE)as contador, clientes.* FROM clientes where $cartao";
            $row1 = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $sqlconsultaBase));
            //fngravalogMSG($array['conn'],$array['login'],$array['empresa'],$cpfcartao,$array['idloja'],$array['idmaquina'],$array['codvendedor'],$array['nomevendedor'],'BuscaConsumidor',$sqlconsultaBase);


        } else {
            if (trim($array['cpf']) != "") {
                $CPF = ' and NUM_CGCECPF="' . $array['cpf'] . '"';
            } elseif (trim($array['cartao']) != "") {
                $cartao = ' and NUM_CARTAO="' . $array['cartao'] . '"';
            }

            if (trim($array['cnpj']) != "") {
                $CNPJ = ' and NUM_CGCECPF="' . $array['cnpj'] . '"';
            } else {
                $CNPJ = '';
            }
            if (trim($array['email']) != "") {
                $email = 'and DES_EMAILUS="' . $array['email'] . '"';
            } else {
                $email = '';
            }
            //if(trim($array['telefone'])!=""){$tel=' and NUM_TELEFON="'.$array['telefone'].'"';}else{$tel='';}
            if (trim($array['telefone']) != "") {
                $cel = ' and NUM_CELULAR="' . $array['telefone'] . '"';
            } else {
                $cel = '';
            }
            if (trim($array['cod_cliente']) != "") {
                $codcliente = ' and COD_CLIENTE="' . $array['cod_cliente'] . '"';
            } else {
                $codcliente = '';
            }
            if (trim($array['cpf']) == '') {
                $cpfcartao = $array['cartao'];
            } else {
                $cpfcartao = $array['cpf'];
            }
            $sqlconsultaBase = "SELECT count(COD_CLIENTE)as contador, clientes.* FROM clientes where  COD_EMPRESA='" . $array['empresa'] . "' $cel $cartao $CPF $CNPJ $tel $codcliente";
            $row1 = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $sqlconsultaBase));
        }
    }


    //PERGUNTA SE O DADOS DO CLIENTES VEM DA BASE OU IFARO


    if ($row1['contador'] >= 1) {
        //VOU CARREGAR DA BASE DE DADOS  

        if ($row1['DES_SENHAUS'] == '' || fnDecode($row1['DES_SENHAUS']) == '') {
            $se = '0';
        } else {
            $se = fnDecode($row1['DES_SENHAUS']);
        }
        if ($row1['LOG_ESTATUS'] == 'N') {
            $LOG_ESTATUS = 'S';
        } else {
            $LOG_ESTATUS = 'N';
        }

        $nome = $row1['NOM_CLIENTE'];
        $cpf = $row1['NUM_CGCECPF'];
        $cnpj = $row1['NUM_CGCECPF'];
        $NUM_RGPESSO = $row1['NUM_RGPESSO'];
        $sexo = $row1['COD_SEXOPES'];
        $dt_nascime = $row1['DAT_NASCIME'];
        $COD_CLIENTE = $row1['COD_CLIENTE'];
        $cartao1 = $row1['NUM_CARTAO'];
        $TIP_CLIENTE = $row1['TIP_CLIENTE'];
        $NOM_CLIENTE = $row1['NOM_CLIENTE'];
        $COD_ESTACIV = $row1['COD_ESTACIV'];
        $NUM_TELEFON = $row1['NUM_TELEFON'];
        $NUM_COMERCI = $row1['NUM_COMERCI'];
        $DES_EMAILUS = $row1['DES_EMAILUS'];
        $COD_PROFISS = $row1['COD_PROFISS'];
        $DAT_CADASTR = $row1['DAT_CADASTR'];
        $DES_ENDEREC = $row1['DES_ENDEREC'];
        $NUM_ENDEREC = $row1['NUM_ENDEREC'];
        $DES_BAIRROC = $row1['DES_BAIRROC'];
        $DES_COMPLEM = $row1['DES_COMPLEM'];
        $NOM_CIDADEC = $row1['NOM_CIDADEC'];
        $COD_ESTADOF = $row1['COD_ESTADOF'];
        $NUM_CEPOZOF = $row1['NUM_CEPOZOF'];
        $NUM_CARTAO = $row1['NUM_CARTAO'];
        $DAT_ALTERAC = $row1['DAT_ALTERAC'];
        $NUM_CELULAR = $row1['NUM_CELULAR'];
        $senha_cliente = $se;
        $codatendente = $row1['COD_ATENDENTE'];
        $codunivend = $row1['COD_UNIVEND'];
        $LOG_FIDELIZADO = $row1['LOG_FIDELIZADO'];
        $DES_TOKEN = $row1['DES_TOKEN'];
        $LOG_TERMO = $row1['LOG_TERMO'];
        $LOG_AVULSO = $row1['LOG_AVULSO'];
        $BLOQUEADO = $LOG_ESTATUS;
        //$sql=$sqlconsultaBase;



        $msg = 'Cliente localizado na base de dados!';
        $cod_msg = '14';
        fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpf, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], 'BuscaConsumidor', $msg, $array['LOG_WS']);
    } else {
        //AQUI VOU CARREGAR IFARO    

        if ($array['consultaativa'] == 'S') {

            if (valida_cpf($array['cpf'])) {
                //busco no log cadastrado da ifaro
                //================================================

                $sqlifaro = "select count(CPF) as TEM,log_cpf.* from log_cpf where CPF = '" . $array['cpf'] . "'";
                $resultifaro = mysqli_query($array['conn'], $sqlifaro);
                $rowifaro = mysqli_fetch_assoc($resultifaro);
                if ($rowifaro['TEM'] != 0) {
                    $nome = $rowifaro['NOME'];
                    $cpf = $rowifaro['CPF'];
                    if ($rowifaro['SEXO'] == 'M') {
                        $sexo = '1';
                    } else {
                        $sexo = '2';
                    }
                    $dt_nascime = $rowifaro['DT_NASCIMENTO'];
                    if ($rowifaro['COD_EMPRESA'] != $array['empresa']) {
                        $intermediaria = "INSERT INTO log_cpfqtd ( DATA_HORA,IP, CPF, NOME, SEXO, DT_NASCIMENTO, COD_EMPRESA, USUARIO, ID_LOJA, ID_MAQUINA) 
                                                                                  VALUES 
                                                                                  ('" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "', '$cpf','$nome' ,'$sexo', '$dt_nascime', '" . $array['empresa'] . "','" . $array['login'] . "','" . $array['idloja'] . "','" . $array['idmaquina'] . "');";
                        mysqli_query($array['conn'], $intermediaria);
                    }

                    $msg = 'Consulta Interna OK!';
                    $cod_msg = '0';
                    fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpf, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], 'BuscaConsumidor', $msg, $array['LOG_WS']);
                } else {
                    //==========================================================

                    //FUNÇÃO QUE BUSCA DA IFARO OS DADOS DO CPF
                    include 'func_ifaro.php';
                    $resultIfaro = ifaro($array['cpf']);
                    if ($resultIfaro['msg'] == 1 || $resultIfaro['msg'] == 16) {
                        $msg = 'Nenhum cadastro encontrado';
                        fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $array['cpf'], $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], 'BuscaConsumidor', $msg, $array['LOG_WS']);
                        return  array(
                            'msgerro' => $msg,
                            'coderro' => '13'
                        );
                        exit();
                    } else {

                        $nome = $resultIfaro['nome'];
                        $cpf = $resultIfaro['cpf'];
                        if ($resultIfaro['sexo'] == 'M') {
                            $sexo = '1';
                        } else {
                            $sexo = '2';
                        }


                        if ($resultIfaro['coderro'] == '250') {
                            $msg = $resultIfaro['msg'];
                            $cod_msg = '250';
                            $dt_nascime = $resultIfaro['datanascimento'];
                            $sql = "insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,Time_consulta,msg,SEXO,DT_NASCIMENTO) values
                                                                                                  ('" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "','0','0','" . $array['empresa'] . "','" . $array['login'] . "','" . $array['idloja'] . "','" . $array['idmaquina'] . "','" . $resultIfaro['timeCo'] . "','" . $resultIfaro['msg'] . "','" . $resultIfaro['sexo'] . "','" . $resultIfaro['datanascimento'] . "')";
                            mysqli_query($array['conn'], $sql);
                        } else {
                            $dt_nascime = $resultIfaro['datanascimento'];
                            $sql = "insert into log_cpf (DATA_HORA,IP,CPF,NOME,COD_EMPRESA,USUARIO,ID_LOJA,ID_MAQUINA,Time_consulta,msg,SEXO,DT_NASCIMENTO) value
                                                                                                  ('" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "','" . $resultIfaro['cpf'] . "','" . $resultIfaro['nome'] . "','" . $array['empresa'] . "','" . $array['login'] . "','" . $array['idloja'] . "','" . $array['idmaquina'] . "','" . $resultIfaro['timeCo'] . "','" . $resultIfaro['msg'] . "','" . $resultIfaro['sexo'] . "','" . $resultIfaro['datanascimento'] . "')";
                            mysqli_query($array['conn'], $sql);
                            $intermediaria = "INSERT INTO log_cpfqtd ( DATA_HORA,IP, CPF, NOME, SEXO, DT_NASCIMENTO, COD_EMPRESA, USUARIO, ID_LOJA, ID_MAQUINA) 
                                                                                                          VALUES 
                                                                                                          ('" . date("Y-m-d H:i:s") . "','" . $_SERVER['REMOTE_ADDR'] . "', '" . $resultIfaro['cpf'] . "','" . $resultIfaro['nome'] . "' ,'" . $resultIfaro['sexo'] . "', '" . $resultIfaro['datanascimento'] . "', '" . $array['empresa'] . "','" . $array['login'] . "','" . $array['idloja'] . "','" . $array['idmaquina'] . "');";
                            mysqli_query($array['conn'], $intermediaria);


                            $msg = $resultIfaro['msg'];
                            $cod_msg = '12';
                        }
                        fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpf, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], 'BuscaConsumidor', 'CONSULTA IFARO', $array['LOG_WS']);
                    }
                    /////////////////////////////////////////=================================                         
                }
            } else {
                $msg = 'Nenhum cadastro encontrado';
                fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpf, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], 'BuscaConsumidor', $msg, $array['LOG_WS']);
                return  array(
                    'msgerro' => $msg,
                    'coderro' => '13'
                );
            }
        }
    }
    if ($array['consultaativa'] != 'S') {
        if ($row1['contador'] <= 0) {

            $msg = 'Nenhum cadastro encontrado';
            fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpf, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], 'BuscaConsumidor', $msg, $array['LOG_WS']);
            return  array(
                'msgerro' => $msg,
                'coderro' => '13'
            );
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////  
    $arraydadosBase = array(
        'COD_CLIENTE' => $row1['COD_CLIENTE'],
        'nome' => $nome,
        'cartao' => $cartao1,
        'cpf' => $cpf,
        'rg' => $NUM_RGPESSO,
        'tipocliente' => $TIP_CLIENTE,
        'cnpj' => $cnpj,
        'nomeportador' => $NOM_CLIENTE,
        'grupo' => '',
        'sexo' => $sexo,
        'datanascimento' => $dt_nascime,
        'estadocivil' => $COD_ESTACIV,
        'telresidencial' => $NUM_TELEFON,
        'telcomercial' => $NUM_COMERCI,
        'telcelular' => $NUM_CELULAR,
        'email' => $DES_EMAILUS,
        'profissao' => $COD_PROFISS,
        'clientedesde' => $DAT_CADASTR,
        'endereco' => $DES_ENDEREC,
        'numero' => $NUM_ENDEREC,
        'bairro' => $DES_BAIRROC,
        'complemento' => $DES_COMPLEM,
        'cidade' => $NOM_CIDADEC,
        'estado' => $COD_ESTADOF,
        'cep' => $NUM_CEPOZOF,
        'cartaotitular' => $NUM_CARTAO,
        'bloqueado' => $BLOQUEADO,
        'motivo' => '',
        'dataalteracao' => $DAT_ALTERAC,
        'adesao' => '',
        'codatendente' => $codatendente,
        'lojapreferencia' => $codunivend,
        'senha' => $senha_cliente,
        'fontedados' => '',
        'retornoGenerico' => '',
        'coderro' => $cod_msg,
        'msgerro' => $msg,
        'funcionario' => $row1['LOG_FUNCIONA'],
        'LOG_FIDELIZADO' => $LOG_FIDELIZADO,
        'conformidade' => $LOG_TERMO,
        'tokenvalido' => $DES_TOKEN,
        'LOG_AVULSO' => $LOG_AVULSO
    );

    return $arraydadosBase;

    //return $sqlconsultaBase;

}
//order by array
//SORT_ASC
//SORT_DESC
function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}
//inicio gera tkt

function fngeratkt($arrayDados)
{


    // flag da pergunta se vai ou nao exibir a lista
    //Select busca configuração TKT
    $selconfig = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =" . $arrayDados['cod_empresa'] . "   and LOG_ATIVO_TKT = 'S'";
    $conf = mysqli_query($arrayDados['connempresa'], $selconfig);
    $rwconfig = mysqli_fetch_assoc($conf);

    //select codigo blacklist
    $blacklist = "select * from 	blacklisttkt where cod_exclusa =0 and COD_BLKLIST in (" . $rwconfig['COD_BLKLIST'] . ")";
    $confblacklist = mysqli_query($arrayDados['connempresa'], $blacklist);
    $rsblk = mysqli_fetch_assoc($confblacklist);


    $arraydia = explode(";", $rwconfig['NUM_HISTORICO_TKT']);
    $max_historico_tkt = $arraydia[1];
    $min_historico_tkt = $arraydia[0];
    $qtd_compras_tkt = $rwconfig['QTD_COMPRAS_TKT'];
    $cod_categorBlk = $rsblk['COD_CATEGOR'];
    $QTD_PRODUTOS_CAT = $rwconfig['QTD_PRODUTOS_CAT'];
    $cod_empresa = $arrayDados['cod_empresa'];
    //$cod_loja=$dadosLogin['idloja'];
    $cod_loja = $arrayDados['idloja'];
    $regrapreco = $rwconfig['DES_PRATPRC'];
    if ($rwconfig['DES_VALIDADE'] == '') {
        $DES_VALIDADE = 0;
    } else {
        $DES_VALIDADE = $rwconfig['DES_VALIDADE'] - 1;
    }
    ///
    $LOG_EMISDIA = $rwconfig['LOG_EMISDIA'];
    $cod_template_tkt = $rwconfig['COD_TEMPLATE_TKT'];
    ////
    $qtd_ofertas_tkt = $rwconfig['QTD_OFERTAS_TKT'];
    $qtd_produtos_tkt = $rwconfig['QTD_PRODUTOS_TKT'];



    if (!$conf || !$confblacklist || $rwconfig['LOG_ATIVO_TKT'] == 'N') {
        $xamls = addslashes("Não existe configuração no TICKET!");
        fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], 'acao_B_Ticket_de_Ofertas', $xamls, $arrayDados['LOG_WS']);
    } else {

        /*  
 gravalog do que eu quizer.
 verificar o log no site    http://adm.bunker.mk/relatorios/relwsnovo.do  
$teste= addslashes($selconfig);
fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],'diogo_teste',$teste);
  */

        //busca personas do cliente - PERFIL
        $sqlPersonaCli = "SELECT  A.COD_PERSONA 
	FROM PERSONACLASSIFICA A
	WHERE A.COD_CLIENTE = " . $arrayDados['id_cliente'];

        $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlPersonaCli);
        $qtdPersonaOn = mysqli_num_rows($arrayQuery);
        $contaLinha = 1;
        if ($qtdPersonaOn > 0) {
            while ($qrPersonaCli = mysqli_fetch_assoc($arrayQuery)) {
                //if ( $contaLinha !=  (int) ($qtdPersonaOn) ){$addOr = " OR ";} else {$addOr = " ";}
                //$personaProduto .=" FIND_IN_SET('".$qrPersonaCli['COD_PERSONA']."',A.COD_PERSONA_TKT) $addOr ";  
                $personaProduto1 .= $qrPersonaCli['COD_PERSONA'] . ',';
                $contaLinha++;
            }
            $personaProduto1 = substr($personaProduto1, 0, -1);
            //$personaProduto  = "AND ( ".$personaProduto." )";	
        } else {
            //$personaProduto  = " ";	
            $personaProduto1  = "0";
        }


        //Select Habitos de compra
        //verifica se o cod_cliente existe na base de dados
        //if($arraybusca['COD_CLIENTE']!='')  
        //{    
        if ($cod_categorBlk != '') {
            $cod_categorBlkand = "AND C.COD_CATEGOR NOT IN ($cod_categorBlk)";
        }
        /* $sqlhabitos="SELECT  DISTINCT  C.DES_PRODUTO, C.COD_PRODUTO,C.COD_EXTERNO 
                   FROM VENDAS A,ITEMVENDA B, PRODUTOCLIENTE C
                   WHERE A.COD_CLIENTE = ".$arrayDados['id_cliente']." AND
                   A.COD_VENDA=B.COD_VENDA AND
                   B.COD_PRODUTO=C.COD_PRODUTO AND
                   C.COD_EMPRESA=$cod_empresa  AND
                   A.DAT_CADASTR >= ADDDATE( NOW(), INTERVAL - $max_historico_tkt DAY) AND
                   A.DAT_CADASTR <= ADDDATE( NOW(), INTERVAL - $min_historico_tkt DAY) 
                   $cod_categorBlkand
                        AND C.COD_PRODUTO NOT IN(SELECT A.COD_PRODUTO FROM BLACKLISTTKTPROD A, BLACKLISTTKT B
                         WHERE A.COD_BLKLIST=B.COD_BLKLIST AND
                         B.COD_CATEGOR IS NULL)    
                   ORDER BY rand(DES_PRODUTO) LIMIT $qtd_compras_tkt";*/
        $sqlhabitos = "SELECT 
                                C.DES_PRODUTO, 
                                C.COD_PRODUTO, 
                                C.COD_EXTERNO,
                                SUM(B.QTD_PRODUTO) AS quantidade_vendas
                            FROM 
                                VENDAS A
                            inner JOIN  ITEMVENDA B ON A.COD_VENDA = B.COD_VENDA
                            inner JOIN  PRODUTOCLIENTE C ON B.COD_PRODUTO = C.COD_PRODUTO
                            WHERE 
                                A.COD_CLIENTE = " . $arrayDados['id_cliente'] . " 
                                AND A.COD_EMPRESA = $cod_empresa  
                                AND A.DAT_CADASTR BETWEEN ADDDATE(NOW(), INTERVAL - $max_historico_tkt DAY) 
                                                    AND ADDDATE(NOW(), INTERVAL - $min_historico_tkt DAY) 
                                $cod_categorBlkand
                                AND C.COD_PRODUTO NOT IN (
                                    SELECT A.COD_PRODUTO 
                                    FROM BLACKLISTTKTPROD A
                                    JOIN BLACKLISTTKT B ON A.COD_BLKLIST = B.COD_BLKLIST 
                                    WHERE B.COD_CATEGOR IS NULL
                                )
                            GROUP BY 
                                C.COD_PRODUTO
                            HAVING 
                                SUM(B.QTD_PRODUTO) > 0
                            ORDER BY 
                                quantidade_vendas DESC
                            LIMIT $qtd_compras_tkt;
                            ";
        //$teste= addslashes($sqlhabitos);
        //fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],'diogo_teste',$teste);

        $habitosexec = mysqli_query($arrayDados['connempresa'], $sqlhabitos);


        if (!$habitosexec) {
            fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $arrayDados['pagina'], $xamls, $arrayDados['LOG_WS']);
            $habitos[] = array('msgerro' => 'Cliente que nao for cadastrado não gera habito de compra!');
        } else {
            //verifica se tem itens na lista de produtos
            if (mysqli_num_rows($habitosexec) == 0) {
                $msghab = 'Não há Habito de compras!';
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $arrayDados['pagina'], $msghab, $arrayDados['LOG_WS']);
                $habitos[] = array('msgerro' => $msghab, 'coderro' => '88');
            }
            // exibi itens na lista de ws    
            while ($rwhabitos = mysqli_fetch_assoc($habitosexec)) {
                $cod_habito .= $rwhabitos['COD_PRODUTO'] . ',';
                $habitos[] = array(
                    'codigoexterno' => !empty($rwhabitos['COD_EXTERNO']) ? $rwhabitos['COD_EXTERNO'] : 0,
                    'codigointerno' => $rwhabitos['COD_PRODUTO'],
                    'descricao' => !empty($rwhabitos['DES_PRODUTO']) ? fnacentos($rwhabitos['DES_PRODUTO']) : 'Erro Envio Integracao'
                );
            }
            /*if($arrayDados['id_cliente']=='744529')
{    
    print_r($habitos);
    exit();
}*/

            fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $arrayDados['pagina'], 'HABITO DE COMPRAS OK', $arrayDados['LOG_WS']);
        }

        //=========================================FIM DO HABITO DE COMPRAS

        $sqltkt = "CALL SP_BUSCA_TKT('$cod_empresa', '$cod_loja','$personaProduto1','N','S',$QTD_PRODUTOS_CAT,$qtd_produtos_tkt, 'CAD');";

        /*if ($cod_empresa == '559') {
            print_r($sqltkt);
            $sqllog = "INSERT INTO teste (des_teste) VALUES ('" . addslashes($sqltkt) . "')";
            mysqli_query($arrayDados['connempresa'], $sqllog);
        }*/

        $tktexec = mysqli_query($arrayDados['connempresa'], $sqltkt);


        if (!$tktexec) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                mysqli_query($arrayDados['connempresa'], $sqltkt);
            } catch (mysqli_sql_exception $e) {
                $msgsql = $e;
            }
            $msg = $sqltkt;
            $xamls = addslashes($msg);
            fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $arrayDados['pagina'], $xamls, $arrayDados['LOG_WS']);
        } else {
            //verifica se tem itens na lista de produtos
            if (mysqli_num_rows($tktexec) == 0) {
                $msgtkt = 'Não há Produtos no ticket!';
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $arrayDados['pagina'], $msgtkt, $arrayDados['LOG_WS']);
                $ofertasTicket[] = array('msgerro' => $msgtkt, 'coderro' => '88');
            } else {
                // exibi itens na lista de ws    
                while ($rwtkt = mysqli_fetch_assoc($tktexec)) {
                    if ($rwtkt['DES_IMAGEM'] != "") {
                        $IMG1 = "https://img.bunker.mk/media/clientes/$cod_empresa/produtos/" . $rwtkt['DES_IMAGEM'] . "";
                    }

                    $cod_tkt .= $rwtkt['COD_PRODUTO'] . ',';
                    $ofertasTicket[] = array(
                        'num_ordenac' => $rwtkt['NUM_ORDENAC'],
                        'codigoexterno' => $rwtkt['COD_EXTERNO'],
                        'codigointerno' => $rwtkt['COD_PRODUTO'],
                        'descricao' => fnacentos($rwtkt['NOM_PRODTKT']),
                        'categoria' => $rwtkt['DES_CATEGOR'],
                        'preco' => $rwtkt['VAL_PRODTKT'],
                        'valorcomdesconto' => $rwtkt['VAL_PROMTKT'],
                        'desconto' => $rwtkt['ABV_DESCTKT'],
                        'descontopctgeral' => fnvalorretorno($rwtkt['PCT_DESCTKT'], 0),
                        'imagem' => $IMG1,
                        'grupodesc' => $rwtkt['COD_DESCTKT']
                    );
                }



                $ofertasTicket = array_filter($ofertasTicket);
                $ofertasTicket = array_orderby($ofertasTicket, 'num_ordenac', SORT_ASC);

                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $arrayDados['pagina'], 'OFERTASTICKET OK......', $arrayDados['LOG_WS']);
            }
            //diogo        
            mysqli_free_result($rwtkt);
            mysqli_next_result($arrayDados['connempresa']);
        }

        //================================================FIM DAS OFERTAS DO TKT
        //ofertas destaque  
        $sqldestaque = "CALL SP_BUSCA_TKT('$cod_empresa', '$cod_loja','$personaProduto1','S','N',$QTD_PRODUTOS_CAT,$qtd_ofertas_tkt, 'CAD');";

        /*
        if ($cod_empresa == 200) {
            $sqllog = "INSERT INTO teste (des_teste) VALUES ('" . addslashes($sqldestaque) . "')";
            mysqli_query($arrayDados['connempresa'], $sqllog);
        }
            */

        $descexec = mysqli_query($arrayDados['connempresa'], $sqldestaque);

        if (!$descexec) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                mysqli_query($arrayDados['connempresa'], $sqldestaque);
            } catch (mysqli_sql_exception $e) {
                $msgsql = $e;
            }
            $msg = "ofertas destaque: $msgsql";
            $xamls = addslashes($msg);
            fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $arrayDados['pagina'], $xamls, $arrayDados['LOG_WS']);
        } else {
            //verifica se tem itens na lista de produtos
            if (mysqli_num_rows($descexec) == 0) {
                $msgP = 'Não há produtos em promoção!';
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $arrayDados['pagina'], $msgP, $arrayDados['LOG_WS']);
                $ofertapromocao[] = array(
                    'msgerro' => $msgP,
                    'coderro' => '88'
                );
            } else {
                // exibi itens na lista de ws    
                while ($rwdesc = mysqli_fetch_assoc($descexec)) {
                    if ($rwdesc['DES_IMAGEM'] != "") {
                        $IMG2 = "https://img.bunker.mk/media/clientes/$cod_empresa/produtos/" . $rwdesc['DES_IMAGEM'] . "";
                    }
                    $cod_oferta .= $rwdesc['COD_PRODUTO'] . ',';
                    $ofertapromocao[] = array(
                        'codigoexterno' => $rwdesc['COD_EXTERNO'],
                        'codigointerno' => $rwdesc['COD_PRODUTO'],
                        'descricao' => fnacentos($rwdesc['NOM_PRODTKT']),
                        'preco' => $rwdesc['VAL_PRODTKT'],
                        'valorcomdesconto' => $rwdesc['VAL_PROMTKT'],
                        'desconto' => $rwdesc['ABV_DESCTKT'],
                        'descontopctgeral' => fnvalorretorno($rwdesc['PCT_DESCTKT'], 0),
                        'imagem' => $IMG2
                    );
                }
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $arrayDados['pagina'], 'Ofertas destaque OK ...', $arrayDados['LOG_WS']);
            }
            mysqli_free_result($rwdesc);
            mysqli_next_result($arrayDados['connempresa']);
        }

        //===================================FIM ofertas destaque  

        //se cod cliente = vazio passa zero pra nao dar erro no insert
        if ($arrayDados['id_cliente'] == '') {
            $cod_client = 0;
        } else {
            $cod_client = $arrayDados['id_cliente'];
        }
        if ($arrayDados['idmaquina'] == '?' || $arrayDados['idmaquina'] == '') {
            $idmaquina = 0;
        } else {
            $idmaquina = $arrayDados['idmaquina'];
        }
        //=================================================================================================
        /////////ARRAY PARA GRAVA TKT
        $lojas = fnconsultaLoja($arrayDados['connadm'], $arrayDados['connempresa'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['cod_empresa']);

        $todosProdutos = substr($cod_oferta . $cod_tkt . $cod_habito, 0, -1);
        $sql1 = "CALL SP_ALTERA_TICKET (
				0, 
				'" . $cod_client . "', 
				'" . $arrayDados['cod_empresa'] . "', 
				'" . $lojas['COD_UNIVEND'] . "', 
				'" . $lojas['COD_MAQUINA'] . "', 
				'" . $arrayDados['cod_user'] . "', 
				'" . $todosProdutos . "', 
				'CAD'    
				) ";
        /*if ($cod_client = '200') {
            $sqllog = "INSERT INTO teste (des_teste) VALUES ('" . addslashes($sql1) . "')";
            mysqli_query($arrayDados['connempresa'], $sqllog);
        }*/
        $ROWsql = mysqli_query($arrayDados['connempresa'], $sql1);

        $arrayretorno = mysqli_fetch_assoc($ROWsql);
        mysqli_free_result($arrayretorno);
        mysqli_next_result($arrayDados['connempresa']);




        if (!empty($ofertapromocao)) {

            $ofertapromocao1 = addslashes(str_replace(array("\n", ""), array("", " "), serialize($ofertapromocao)));
        }

        if (!empty($ofertasTicket)) {
            $ofertasTicket1 = addslashes(str_replace(array("\n", ""), array("", " "), serialize($ofertasTicket)));
        }

        if (!empty($ofertapromocao) || !empty($ofertasTicket)) {
            $ofertasTicket[] = array('msgerro' => 'Não há ofertas', 'coderro' => '88');
        }

        $habitos1 = addslashes(serialize($habitos));

        if ($LOG_EMISDIA == "S") {
            $dtdevolucao = "'" . date('Y-m-d', strtotime("+$DES_VALIDADE days")) . "',";
            //pegar sempre o ultimo tkt pelo codempresa e codcliente
            $sql = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* 
                from ticket_dados where COD_CLIENTE=$cod_client 
                 and COD_EMPRESA=" . $arrayDados['cod_empresa'] . " 
                 and LOG_EMISDIA='S' and DAT_VALIDADE >= '" . date('Y-m-d') . "'   
                ORDER by COD_GERAL DESC limit 1;";
            $misdiatkt = mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'], $sql));
            //SE DATA DA VALIDADE ULTRAPASSAR INSERIR UM NOVO
            if ($misdiatkt['COD_GERAL'] == '') {
                $insert = "INSERT INTO TICKET_DADOS(COD_TICKET,DES_PROMOCAO,DES_TICKET,DES_HABITOS,COD_EMPRESA, COD_CLIENTE,COD_UNIVEND,DAT_VALIDADE,LOG_EMISDIA)VALUES(" . $arrayretorno['COD_TICKET'] . ",'" . $ofertapromocao1 . "','" . $ofertasTicket1 . "','" . $habitos1 . "', " . $arrayDados['cod_empresa'] . "," . $cod_client . "," . $cod_loja . ", $dtdevolucao'" . $LOG_EMISDIA . "' )";
                mysqli_query($arrayDados['connempresa'], rtrim(ltrim(trim($insert))));
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], "Gravando array do ticket gerado!", $arrayDados['LOG_WS']);
                //depois da validade terminar busco de novo    
                sleep(0.25);
                $sql = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and COD_EMPRESA=" . $arrayDados['cod_empresa'] . " and LOG_EMISDIA='S' and DAT_VALIDADE >= '" . date('Y-m-d') . "' ORDER by COD_GERAL DESC limit 1;";
                $misdiatkt = mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'], $sql));
            }
            /*if($arrayDados['id_cliente']=='79')
                {    
                   $sqllog="INSERT INTO teste (des_teste) VALUES ('". addslashes($sql)."')";
                  mysqli_query($arrayDados['connempresa'], $sqllog);
                   // print_r(unserialize($misdiatkt[DES_PROMOCAO]));
                    exit();
                } */
            //===============================================================
            //retorno da array      
            if (date('Y-m-d') <= $misdiatkt['DAT_VALIDADE']) {
                if ($rwconfig['LOG_LISTAWS'] == 'S') {

                    $produtoTicket = unserialize($misdiatkt['DES_TICKET']);

                    //produtos do tkt loop
                    if ($produtoTicket[0]['msgerro'] == 'Não há Produtos no ticket!') {
                        $produtoTicketarr = array('msgerro' => $produtoTicket[0]['msgerro'], 'coderro' => '88');
                    } else {
                        for ($i = 0; $i <= count($produtoTicket) - 1; $i++) {

                            $produtoTicketarr[] = array(
                                'codigoexterno' => $produtoTicket[$i]['codigoexterno'],
                                'codigointerno' => $produtoTicket[$i]['codigointerno'],
                                'descricao' => $produtoTicket[$i]['descricao'],
                                'preco' => fnformatavalorretorno($produtoTicket[$i]['preco']),
                                'valorcomdesconto' => fnformatavalorretorno($produtoTicket[$i]['valorcomdesconto']),
                                'desconto' => $produtoTicket[$i]['desconto'],
                                'descontopctgeral' => $produtoTicket[$i]['descontopctgeral'],
                                'imagem' => $produtoTicket[$i]['imagem']
                            );
                        }
                        $produtoTicketarr = array_filter($produtoTicketarr);
                    }

                    $produtoPromocao = unserialize($misdiatkt['DES_PROMOCAO']);
                    if ($produtoPromocao[0]['msgerro'] == 'Não há produtos em promoção!') {
                        $produtoPromocaoarr = array('msgerro' => $produtoPromocao[0]['msgerro'], 'coderro' => '88');
                    } else {
                        for ($i = 0; $i <= count($produtoPromocao) - 1; $i++) {
                            $produtoPromocaoarr[] = array(
                                'codigoexterno' => $produtoPromocao[$i]['codigoexterno'],
                                'codigointerno' => $produtoPromocao[$i]['codigointerno'],
                                'descricao' => $produtoPromocao[$i]['descricao'],
                                'preco' => fnformatavalorretorno($produtoPromocao[$i]['preco']),
                                'valorcomdesconto' => fnformatavalorretorno($produtoPromocao[$i]['valorcomdesconto']),
                                'desconto' => $produtoPromocao[$i]['desconto'],
                                'descontopctgeral' => $produtoPromocao[$i]['descontopctgeral'],
                                'imagem' => $produtoPromocao[$i]['imagem']
                            );
                        }
                        $produtoPromocaoarr = array_filter($produtoPromocaoarr);
                    }

                    $acao2 = array(
                        'produtoHabito' => unserialize($misdiatkt['DES_HABITOS']),
                        'produtoTicket' => $produtoTicketarr,
                        'produtoPromocao' => $produtoPromocaoarr
                    );

                    /* $acao2= array('produtoHabito'=> unserialize($misdiatkt['DES_HABITOS']),
                              'produtoTicket'=>unserialize($misdiatkt['DES_TICKET']), 
                              'produtoPromocao'=>unserialize($misdiatkt['DES_PROMOCAO']),
                              
                              ); */
                }
            }


            //====================================================================
            //se a emissão nao for diaria      
        } else {
            $dtdevolucao = 'NULL,';
            $insert = "INSERT INTO TICKET_DADOS(COD_TICKET,DES_PROMOCAO,DES_TICKET,DES_HABITOS,COD_EMPRESA, COD_CLIENTE,COD_UNIVEND,DAT_VALIDADE,LOG_EMISDIA)VALUES(" . $arrayretorno['COD_TICKET'] . ",'" . $ofertapromocao1 . "','" . $ofertasTicket1 . "','" . $habitos1 . "', " . $arrayDados['cod_empresa'] . "," . $cod_client . "," . $cod_loja . ", $dtdevolucao'" . $LOG_EMISDIA . "' )";
            mysqli_query($arrayDados['connempresa'], rtrim(ltrim(trim($insert))));
            fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], "Gravando array do ticket gerado!", $arrayDados['LOG_WS']);
            //depois da validade terminar busco de novo  

            sleep(0.25);
            $sql = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and COD_EMPRESA=" . $arrayDados['cod_empresa'] . " ORDER by COD_GERAL DESC limit 1;";
            $misdiatkt = mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'], $sql));
            /*if ($cod_empresa == 200) {
                $sqllog = "INSERT INTO teste (des_teste) VALUES ('" . addslashes($insert) . "')";
                mysqli_query($arrayDados['connempresa'], $sqllog);
                $sqllog = "INSERT INTO teste (des_teste) VALUES ('" . addslashes($sql) . "')";
                mysqli_query($arrayDados['connempresa'], $sqllog);
                exit;
            }*/
            if ($rwconfig['LOG_LISTAWS'] == 'S') {
                $produtoTicket = unserialize($misdiatkt['DES_TICKET']);

                //produtos do tkt loop
                if ($produtoTicket[0]['msgerro'] == 'Não há Produtos no ticket!') {
                    $produtoTicketarr = array('msgerro' => $produtoTicket[0]['msgerro'], 'coderro' => '88');
                } else {
                    for ($i = 0; $i <= count($produtoTicket) - 1; $i++) {

                        $produtoTicketarr[] = array(
                            'codigoexterno' => $produtoTicket[$i]['codigoexterno'],
                            'codigointerno' => $produtoTicket[$i]['codigointerno'],
                            'descricao' => $produtoTicket[$i]['descricao'],
                            'preco' => fnformatavalorretorno($produtoTicket[$i]['preco']),
                            'valorcomdesconto' => fnformatavalorretorno($produtoTicket[$i]['valorcomdesconto']),
                            'desconto' => $produtoTicket[$i]['desconto'],
                            'descontopctgeral' => $produtoTicket[$i]['descontopctgeral'],
                            'imagem' => $produtoTicket[$i]['imagem']
                        );
                    }

                    $produtoTicketarr = array_filter($produtoTicketarr);
                }

                $produtoPromocao = unserialize($misdiatkt['DES_PROMOCAO']);
                if ($produtoPromocao[0]['msgerro'] == 'Não há produtos em promoção!') {
                    $produtoPromocaoarr = array('msgerro' => $produtoPromocao[0]['msgerro'], 'coderro' => '88');
                } else {
                    for ($i = 0; $i <= count($produtoPromocao) - 1; $i++) {
                        $produtoPromocaoarr[] = array(
                            'codigoexterno' => $produtoPromocao[$i]['codigoexterno'],
                            'codigointerno' => $produtoPromocao[$i]['codigointerno'],
                            'descricao' => $produtoPromocao[$i]['descricao'],
                            'preco' => fnformatavalorretorno($produtoPromocao[$i]['preco']),
                            'valorcomdesconto' => fnformatavalorretorno($produtoPromocao[$i]['valorcomdesconto']),
                            'desconto' => $produtoPromocao[$i]['desconto'],
                            'descontopctgeral' => $produtoPromocao[$i]['descontopctgeral'],
                            'imagem' => $produtoPromocao[$i]['imagem']
                        );
                    }
                    $produtoPromocaoarr = array_filter($produtoPromocaoarr);
                }

                $acao2 = array(
                    'produtoHabito' => unserialize($misdiatkt['DES_HABITOS']),
                    'produtoTicket' => $produtoTicketarr,
                    'produtoPromocao' => $produtoPromocaoarr
                );
            }
        }
        //===================================================== 
    }

    //FIM DO IF DA FLAG ATIVA OU DESATIVA  

    return $acao2;
}
//=====fim gera tkt

function valida_cpf($cpf = false)
{
    // Exemplo de CPF: 025.462.884-23

    /**
     * Multiplica dígitos vezes posições 
     *
     * @param string $digitos Os digitos desejados
     * @param int $posicoes A posição que vai iniciar a regressão
     * @param int $soma_digitos A soma das multiplicações entre posições e dígitos
     * @return int Os dígitos enviados concatenados com o último dígito
     *
     */
    if (! function_exists('calc_digitos_posicoes')) {
        function calc_digitos_posicoes($digitos, $posicoes = 10, $soma_digitos = 0)
        {
            // Faz a soma dos dígitos com a posição
            // Ex. para 10 posições: 
            //   0    2    5    4    6    2    8    8   4
            // x10   x9   x8   x7   x6   x5   x4   x3  x2
            //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
            for ($i = 0; $i < strlen($digitos); $i++) {
                $soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);
                $posicoes--;
            }

            // Captura o resto da divisão entre $soma_digitos dividido por 11
            // Ex.: 196 % 11 = 9
            $soma_digitos = $soma_digitos % 11;

            // Verifica se $soma_digitos é menor que 2
            if ($soma_digitos < 2) {
                // $soma_digitos agora será zero
                $soma_digitos = 0;
            } else {
                // Se for maior que 2, o resultado é 11 menos $soma_digitos
                // Ex.: 11 - 9 = 2
                // Nosso dígito procurado é 2
                $soma_digitos = 11 - $soma_digitos;
            }

            // Concatena mais um dígito aos primeiro nove dígitos
            // Ex.: 025462884 + 2 = 0254628842
            $cpf = $digitos . $soma_digitos;

            // Retorna
            return $cpf;
        }
    }

    // Verifica se o CPF foi enviado
    if (! $cpf) {
        return false;
    }

    // Remove tudo que não é número do CPF
    // Ex.: 025.462.884-23 = 02546288423
    $cpf = preg_replace('/[^0-9]/is', '', $cpf);

    // Verifica se o CPF tem 11 caracteres
    // Ex.: 02546288423 = 11 números
    if (strlen($cpf) != 11) {
        return false;
    }

    // Captura os 9 primeiros dígitos do CPF
    // Ex.: 02546288423 = 025462884
    $digitos = substr($cpf, 0, 9);

    // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
    $novo_cpf = calc_digitos_posicoes($digitos);

    // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
    $novo_cpf = calc_digitos_posicoes($novo_cpf, 11);

    // Verifica se o novo CPF gerado é idêntico ao CPF enviado
    if ($novo_cpf === $cpf) {
        // CPF válido
        return true;
    } else {
        // CPF inválido
        return false;
    }
}
function fndate($str)
{
    $strcount = date('Y-m-d', strtotime($str));
    return $strcount;
}

function fnDataSql($str)
{

    $data = str_replace("/", "-", $str);
    $strcount = date('Y-m-d', strtotime($data));
    return $strcount;
}
function fnDataBR($str)
{

    $data = str_replace("-", "/", $str);
    $strcount = date('d/m/Y', strtotime($data));
    return $strcount;
}
function is_Date($str)
{
    if (is_numeric($str) ||  preg_match('^[0-9]^', $str)) {
        return $str;
    } else {
        $str = date('Y-m-d H:i:s');

        return $str;
    }
    return false;
}
function fnTestesql($conn, $sql)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        mysqli_query($conn, $sql);
    } catch (mysqli_sql_exception $e) {

        return $e;
    }
}
function Grava_log($conn, $id_log, $MSG)
{
    $msg1 = 'INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                   (' . $id_log . ',"' . date("Y-m-d H:i:s") . '","' . $MSG . '")';
    mysqli_query($conn, $msg1);
}
function Grava_log_cad($conn, $id_log, $MSG)
{
    $msg1 = 'INSERT INTO msg_cadastra (ID,DATA_HORA,MSG)values
                   (' . $id_log . ',"' . date("Y-m-d H:i:s") . '","' . $MSG . '")';
    mysqli_query($conn, $msg1);
}
function Grava_log_busca($conn, $id_log, $MSG)
{
    $msg1 = 'INSERT INTO msg_busca (ID,DATA_HORA,MSG)values
                   (' . $id_log . ',"' . date("Y-m-d H:i:s") . '","' . $MSG . '")';
    mysqli_query($conn, $msg1);
    return $msg1;
}
function valida_campo($conn, $cliente, $dadosLogin, $responsews, $log, $log_ativo, $campoblk = '0')
{

    $cod = 0;
    $CAMPOSSQL = "select KEY_CAMPOOBG,DES_CAMPOOBG,INTEGRA_CAMPOOBG.TIP_CAMPOOBG  from matriz_campo_integracao                         
                    inner join INTEGRA_CAMPOOBG on INTEGRA_CAMPOOBG.COD_CAMPOOBG=matriz_campo_integracao.COD_CAMPOOBG                         
                    where matriz_campo_integracao.COD_EMPRESA=" . $dadosLogin['idcliente'] . "
                    and matriz_campo_integracao.TIP_CAMPOOBG ='OBG' AND KEY_CAMPOOBG != '" . $campoblk . "'";
    $CAMPOQUERY = mysqli_query($conn, $CAMPOSSQL);
    while ($CAMPOROW = mysqli_fetch_assoc($CAMPOQUERY)) {
        $array[] = array('KEY_CAMPOOBG' => $CAMPOROW['KEY_CAMPOOBG']);
        $arraytype[] = array('KEY_CAMPOOBG' => $CAMPOROW['TIP_CAMPOOBG']);
    }

    foreach ($array as $key) {
        foreach ($key as $chave => $vl) {
            if ($cliente[$vl] == '') {
                $vl1 .= $vl . ',';
            }
        }
    }
    $resultado = substr($vl1, 0, -1);
    if (rtrim(trim($resultado)) != "") {
        fngravalogMSG($conn, $dadosLogin['login'], $dadosLogin['idcliente'], $cliente['cpf'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'AtualizaCadastro', 'Campos listados são obrigatorios: ' . $resultado, $log, $log_ativo);
        $msg =  array($responsews => array(
            'msgerro' => 'Campos listados são obrigatorios: ' . $resultado,
            'coderro' => '15'
        ));
        return $msg;
    } else {
        $cod = 0;
    }
}
//formatando campos
function validaCampo($campo, $nomecampo, $fromato)
{

    if ($nomecampo == 'email') {
        if ($campo != "") {
            if (!filter_var($campo, FILTER_VALIDATE_EMAIL)) {
                $msg = 'O Campo ' . $nomecampo . ' esta com o formato invalido !';
                return $msg;
            }
        }
    }

    if ($nomecampo == 'nome') {

        $count = strlen($campo);
        if ($count >= 3) {
        } else {
            $msg = 'O texto no campo  ' . $nomecampo . ' está menor que o esperado, por favor digitar o nome completo!';
            return $msg;
        }
    }

    if ($fromato == 'DATA_BR') {
        if ($campo != "") {
            if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $campo)) {
                $msg = 'O campo  ' . $nomecampo . '  está invalida digite DD/MM/AAAA!';
                return $msg;
            }
        }
    }

    if ($fromato == 'numeric' && $campo != "") {
        if (!is_numeric($campo)) {
            if ($nomecampo == 'sexo') {
                $msg = 'O Campo ' . $nomecampo . ' precisa ser só numero ex 1 para masculino || 2 para feminino !';
                return $msg;
            } else {
                $msg = 'O Campo ' . $nomecampo . ' precisa ser só numero!';
                return $msg;
            }
        }
    }
}
///////////////////////    
function valida_campo_vazio($campo, $nomecampo, $fromato)
{
    if ((rtrim(trim($campo)) == "") && ($nomecampo != 'email')) {
        $msg = 'Campo ' . $nomecampo . ' precisa ser preenchido!';
        return $msg;
    }

    if ($nomecampo == 'email') {
        if ($campo != "") {
            if (!filter_var($campo, FILTER_VALIDATE_EMAIL)) {
                $msg = 'O Campo ' . $nomecampo . ' esta com o formato invalido !';
                return $msg;
            }
        }
    }

    if ($nomecampo == 'nome') {

        $count = strlen($campo);
        if ($count >= 3) {
        } else {
            $msg = 'O texto no campo  ' . $nomecampo . ' está menor que o esperado, por favor digitar o nome completo!';
            return $msg;
        }
    }

    if ($fromato == 'DATA_BR') {
        //aqui eu preciso ver uma chave para ver se critiva ou nao 
        if ($campo == "") {
            if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $campo)) {
                $msg = 'O campo  ' . $nomecampo . ' data esta invalida digite DD/MM/AAAA!';
                return $msg;
            }
        } else {
            if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $campo)) {
                $msg = 'O campo  ' . $nomecampo . ' data esta invalida digite DD/MM/AAAA!';
                return $msg;
            }
        }
    }
    /*
		//validando telefone residencial
		$telefone = "(82) 5555-5555";
		if(!preg_match('^\(+[0-9]{2,3}\) [0-9]{4}-[0-9]{4}$^', $telefone)){
		echo "Telefone inváildo.";
		$msg = 'O campo  '.$nomecampo.' Telefone invalido (XX) 5555-5555';
		} 
		
		$celular = '(21) 98765-4321';

		if (preg_match('#^\(\d{2}\) (9|)[6789]\d{3}-\d{4}$#', $celular) > 0) {
			 echo 'Validou';
		} else {
			 echo 'Não validou';
		}
		*/

    if ($fromato == 'DATA_US') {
        //aqui eu preciso ver uma chave para ver se critiva ou nao 
        if ($campo == "") {
            if (!preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $campo)) {
                $msg = 'O campo  ' . $nomecampo . ' data esta invalida digite AAAA-MM-DD!';
                return $msg;
            }
        } else {

            if (!preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}$/', $campo)) {
                $msg = 'O campo  ' . $nomecampo . ' data esta invalida digite AAAA-MM-DD!';
                return $msg;
            } else {
            }
        }
    }



    if ($fromato == 'numeric') {
        if (is_numeric($campo)) {
        } else {
            if ($nomecampo == 'sexo') {
                $msg = 'O Campo ' . $nomecampo . ' precisa ser só numero ex 1 para masculino || 2 para feminino !';
                return $msg;
            } else {
                $msg = 'O Campo ' . $nomecampo . ' precisa ser só numero!';
                return $msg;
            }
        }
    }
}

function fnconsultaLoja($CONN1, $CONN2, $ID_LOJA, $ID_MAQUINA, $COD_EMPRESA)
{

    //unidade de venda tem que existir
    $sql = "select count(COD_UNIVEND) as existe, COD_UNIVEND  from unidadevenda where COD_UNIVEND=" . $ID_LOJA;
    $retIDLOJA = mysqli_fetch_assoc(mysqli_query($CONN1, $sql));

    if ($retIDLOJA['existe'] != 0) {
        $MSG = '1';

        $sqlMAQUINA = "select count(*) as DES_MAQUINA, maquinas.COD_MAQUINA from maquinas where DES_MAQUINA='" . $ID_MAQUINA . "'";
        $retIDMAQUINA = mysqli_fetch_assoc(mysqli_query($CONN2, $sqlMAQUINA));
        if ($retIDMAQUINA['DES_MAQUINA'] == 0) {

            $sqlinsert = "insert into maquinas (DES_MAQUINA,
                                               COD_EMPRESA,
                                               COD_UNIVEND
                                               )
                                               values
                                               (
                                                '" . $ID_MAQUINA . "',
                                                '" . $COD_EMPRESA . "',
                                                '" . $retIDLOJA['COD_UNIVEND'] . "'   
                                                )";
            mysqli_query($CONN2, $sqlinsert);

            //codigo de inserção
            $ID_MAQUINA = "SELECT last_insert_id(COD_MAQUINA) as COD_MAQUINA from maquinas ORDER by COD_MAQUINA DESC limit 1;";
            $id_return = mysqli_fetch_assoc(mysqli_query($CONN3, $ID_MAQUINA));
            $idmaquina = $id_return['COD_MAQUINA'];
        } else {
            //codigo de inserção
            $idmaquina = $retIDMAQUINA['COD_MAQUINA'];
            //$teste= "INSERT INTO teste1 (des_teste) VALUES ('".$idmaquina."')";
            // mysqli_query($CONN1,$teste);
        }
    } else {
        $MSG = 'ERRO';
    }



    $arraydadosBase = array(

        'msg' => $MSG,
        'COD_MAQUINA' => $idmaquina,
        'COD_UNIVEND' => $retIDLOJA['COD_UNIVEND']
    );

    return $arraydadosBase;
}
function fnConsultaLojaGET($CONN1, $ID_LOJA)
{

    //unidade de venda tem que existir
    $sql = "select count(COD_UNIVEND) as existe, COD_UNIVEND  from unidadevenda where COD_UNIVEND=" . $ID_LOJA;
    $retIDLOJA = mysqli_fetch_assoc(mysqli_query($CONN1, $sql));
    if ($retIDLOJA['existe'] != 0) {
        $MSG = 'OK';
    } else {
        $MSG = 'ERRO';
    }


    $arraydadosBase = array();
    array_push(
        $arraydadosBase,
        array(

            'msg' => $MSG,
            'COD_UNIVEND' => $retIDLOJA['COD_UNIVEND']
        )
    );
    return $arraydadosBase;
}
function fnSQLLOG($conn, $sql, $codogi)
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        // mysqli_query($conn,$sql);

        $msg1 = 'INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                   (' . $codogi . ',"' . date("Y-m-d H:i:s") . '","Comando SQL VALIDADO")';
        mysqli_query($conn, $msg1);
        return $msg1;
    } catch (mysqli_sql_exception $e) {
        $msg1 = 'INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                   (' . $codogi . ',"' . date("Y-m-d H:i:s") . '","COMANDO SQL INVALIDO!")';
        mysqli_query($conn, $msg1);
        return $msg1;
    }
}
function fngravalogMSG($conn, $USUARIO, $EMPRESA, $CPF, $loja, $idmaquina, $codvendedor, $nomevendedor, $pagina, $msgerro, $ativo)
{
    if ($ativo == 'S') {
        $sql = "insert into ws_log (ip,porta,USUARIO,EMPRESA,CPF,loja,idmaquina,codvendedor,nomevendedor,pagina,msgerro)values
                      ('" . $_SERVER['REMOTE_ADDR'] . "',
                       '" . $_SERVER['REMOTE_PORT'] . "',
                       '" . $USUARIO . "',
                       '" . $EMPRESA . "',
                       '" . $CPF . "',  
                       '" . $loja . "',
                       '" . $idmaquina . "',
                       '" . $codvendedor . "',
                       '" . $nomevendedor . "',
                       '" . $pagina . "',    
                       '" . $msgerro . "'    
                     )";

        mysqli_query($conn, $sql);
    }
    //return $ativo;
}
function fnVendedor($conn, $NOM_USUARIO, $COD_MULTEMP, $COD_UNIVEND, $cod_externo, $cod_usuario = 0)
{
    if ($NOM_USUARIO != '') {
        $nome_user = " or NOM_USUARIO='$NOM_USUARIO'";
    } else {
        $nome_user = '';
    }

    $sqlbusca = "select count(*) as exist,COD_USUARIO,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST from usuarios 
	             where COD_EMPRESA='$COD_MULTEMP' and  
				       COD_EXTERNO='$cod_externo' AND
					   FIND_IN_SET('" . $COD_UNIVEND . "',COD_UNIVEND)
					   ";
    $result = mysqli_fetch_assoc(mysqli_query($conn, $sqlbusca));
    if ($result['exist'] <= 0) {
        $sqlbusca = "select count(*) as exist,COD_USUARIO,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST from usuarios 
						where 
						COD_EMPRESA='$COD_MULTEMP' and  
						COD_USUARIO='$cod_externo' AND
					    FIND_IN_SET('" . $COD_UNIVEND . "',COD_UNIVEND)";
        $result = mysqli_fetch_assoc(mysqli_query($conn, $sqlbusca));
    }

    //if($COD_MULTEMP==45){
    //    return $sqlbusca;
    // }
    if ($result['exist'] == 0) {
        //dat_cadastr      
        //NOM_USUARIO, COD_TPUSUARIO = 7, COD_MULTEMP = COD_EMPRESA, COD_UNIVEND = LOJA, COD_DEFSIST = 4
        $sql = 'insert into usuarios (dat_cadastr,NOM_USUARIO,COD_EMPRESA,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST,COD_EXTERNO,LOG_ESTATUS,COD_USUCADA)
                                values
                                (
                                "' . date('Y-m-d H:i:s') . '",
                                "' . $NOM_USUARIO . '",
                                "' . $COD_MULTEMP . '",    
                                "7",
                                "' . $COD_MULTEMP . '",
                                "' . $COD_UNIVEND . '",
                                "7",
                                "' . $cod_externo . '",
				"S",
                                "' . $cod_usuario . '"
                                )';
        mysqli_query($conn, $sql);
        $COD_VENDEDOR = mysqli_insert_id($conn);

        /*$sqllog="INSERT INTO teste (des_teste) VALUES ('". addslashes($sql)."')";
        mysqli_query($conn, $sqllog);
*/
        //return $COD_VENDEDOR;
        return $COD_VENDEDOR;
    } else {

        $COD_VENDEDOR = $result['COD_USUARIO'];
        return $COD_VENDEDOR;
    }
}

function fnreturn($array)
{

    //permissão de modulos de retorno
    $sqlFase = "select matriz_integracao.COD_ACAOINT,INTEGRA_acaomtz.KEY_ACAOINT from matriz_integracao
                      LEFT JOIN INTEGRA_VENDAMTZ ON matriz_integracao.COD_FASEVND = INTEGRA_VENDAMTZ.COD_FASEINT
                      LEFT JOIN INTEGRA_acaomtz ON matriz_integracao.COD_ACAOINT = INTEGRA_acaomtz.COD_ACAOINT
                      where matriz_integracao.cod_empresa=" . $array['empresa'] . " 
                      and INTEGRA_VENDAMTZ.KEY_FASEINT='" . $array['fase'] . "' order by INTEGRA_acaomtz.num_ordenac;";
    $rs = mysqli_query($array['conn'], $sqlFase);


    if (mysqli_num_rows($rs) == 0) {
        $msg = 'Não existe modulos configurado para retorno!' . $array['fase'];
        fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $array['cpf'], $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], 'BuscaConsumidor', $msg, $array['LOG_WS']);

        return  array(
            'msgerro' => 'Não existe modulos configurado para retorno!',
            'coderro' => '16'
        );
        exit();
    }

    $arraybusca = fn_consultaBase($array);
    //conformidade da lgp if
    //perguntar se tem token ativo 

    if ($array['LOG_CADTOKEN'] == 'S') {
        /*
			                   'conformidade'=>$LOG_TERMO,
					            'tokenvalido'=>$DES_TOKEN	
			       */
        if ($arraybusca['conformidade'] == 'S' && $arraybusca['tokenvalido'] > '0') {
            //cliente tem token e aceitou os termos						 
            $conformidade = '1';
            $tokenvalido = '1';
            $msgconformidade = 'Cliente está em conformidade!';
        } elseif ($arraybusca['conformidade'] == 'S' && $arraybusca['tokenvalido'] <= '0') {
            //cliente não tem token e aceitou os termos						 
            $conformidade = '1';
            $tokenvalido = '0';
            $msgconformidade = 'Cadastro precisa de atualização/consentimento, confirme o telefone do cliente para enviar o token por SMS';
        } elseif ($arraybusca['conformidade'] == 'N' && $arraybusca['tokenvalido'] <= '0') {
            //termos OK token zerado					 
            $conformidade = '0';
            $tokenvalido = '0';
            $msgconformidade = 'Cadastro precisa de atualização/consentimento, confirme o telefone do cliente para enviar o token por SMS';
        } elseif ($arraybusca['conformidade'] == 'N' && $arraybusca['tokenvalido'] > '0') {
            //Gerar token para novo cadastr					 
            $conformidade = '0';
            $tokenvalido = '1';
            $msgconformidade = 'Por favor direcionar o cliente ao Totem ou hotsite para aceitar novos termos!';
        } else {
            //DEFAULT					 
            $conformidade = '0';
            $tokenvalido = '0';
            $msgconformidade = 'Confirme se o cliente esta com celular e envie o token!';
        }
    } else {

        if ($arraybusca['conformidade'] == 'S') {
            //cliente tem token e aceitou os termos						 
            $conformidade = '1';
            $tokenvalido = '0';
            $msgconformidade = 'Cliente está em conformidade!';
        } elseif ($arraybusca['conformidade'] == 'N') {
            //cliente não tem token e aceitou os termos						 
            $conformidade = '0';
            $tokenvalido = '0';
            $msgconformidade = 'Por favor direcionar o cliente ao totem ou hotsite para atualizar o cadastro!';
        } else {
            //DEFAULT						 
            $conformidade = '0';
            $tokenvalido = '0';
            $msgconformidade = 'Por favor direcionar o cliente ao totem ou hotsite para atualizar o cadastro!';
        }
    }

    while ($ResultFase = mysqli_fetch_array($rs)) {

        if ($ResultFase['COD_ACAOINT'] == 1) {

            if ($array['cpf'] != '' && $array['cpf']  <= 0) {
                $cpfcartao = $array['cpf'];
            } else {
                if ($array['cartao'] != '') {
                    $cpfcartao = $array['cartao'];
                } else {
                    $cpfcartao = $array['cnpj'];
                }
            }

            $msgr = 'acao_A_cadastro!';
            fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msgr, $array['LOG_WS']);
            $acaoRetorno .= $ResultFase['KEY_ACAOINT'] . ',';

            $totemplayer = "SELECT COD_PLAYERS FROM totem_players WHERE COD_EMPRESA='" . $array['empresa'] . "' and DES_PAGHOME='CAD' AND COD_UNIVEND='" . $array['idloja'] . "' LIMIT 1";
            $rstotemplayer = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $totemplayer));
            $urltotem = fnEncode(
                $array['login'] . ';'
                    . $array['senha'] . ';'
                    . $array['idloja'] . ';'
                    . $array['idmaquina'] . ';'
                    . $array['empresa'] . ';'
                    . $array['codvendedor'] . ';'
                    . $array['nomevendedor'] . ';'
                    . $rstotemplayer['COD_PLAYERS']
            );

            $sqlbusca = "select count(*) as exist,
                            COD_USUARIO,
                            COD_EXTERNO,
                            NOM_USUARIO,
                            COD_TPUSUARIO,
                            COD_MULTEMP,
                            COD_UNIVEND,
                            COD_DEFSIST 
                            from usuarios where COD_EMPRESA=" . $array['empresa'] . " and 
                            COD_MULTEMP='" . $array['empresa'] . "' and 
                            COD_USUARIO='" . $arraybusca['codatendente'] . "'";

            $result = mysqli_fetch_assoc(mysqli_query($array['conn'], $sqlbusca));


            $acao1 = array(
                'nome' => fnAcentos($arraybusca['nome']),
                'cartao' => $arraybusca['cartao'],
                'cpf' => fncompletadoc($arraybusca['cpf'], $arraybusca['tipocliente']),
                'sexo' => $arraybusca['sexo'],
                'rg' => $arraybusca['rg'],
                'cnpj' => fncompletadoc($arraybusca['cnpj'], $arraybusca['tipocliente']),
                'nomeportador' => '',
                'grupo' => $arraybusca['grupo'],
                'datanascimento' => $arraybusca['datanascimento'],
                'estadocivil' => $arraybusca['estadocivil'],
                'telresidencial' => $arraybusca['telresidencial'],
                'telcomercial' => $arraybusca['telcomercial'],
                'telcelular' => $arraybusca['telcelular'],
                'email' => $arraybusca['email'],
                'profissao' => $arraybusca['profissao'],
                'clientedesde' => $arraybusca['clientedesde'],
                'tipocliente' => $arraybusca['tipocliente'],
                'endereco' => $arraybusca['endereco'],
                'numero' => $arraybusca['numero'],
                'bairro' => $arraybusca['bairro'],
                'complemento' => $arraybusca['complemento'],
                'cidade' => $arraybusca['cidade'],
                'estado' => $arraybusca['estado'],
                'cep' => $arraybusca['cep'],
                'cartaotitular' => '',
                'bloqueado' => $arraybusca['bloqueado'],
                'motivo' => '',
                'dataalteracao' => $arraybusca['dataalteracao'],
                'adesao' => $arraybusca['adesao'],
                'codatendente' => $result['COD_EXTERNO'],
                'senha' => $arraybusca['senha'],
                'fontedados' => $arraybusca['fontedados'],
                'retornoGenerico' => '',
                'urltotem' => "https://totem.bunker.mk/consulta_V2.do?key=$urltotem&r=" . date("Ymdhis") . round(microtime(true) * 1000),
                'participafidelidade' => $arraybusca['LOG_FIDELIZADO'],
                'conformidade' => $conformidade,
                'tokenvalido' => $tokenvalido,
                'msgconformidade' => $msgconformidade,
                'tokencadastro' => $arraybusca['tokenvalido'],
                'coderro' => $arraybusca['coderro'],
                'msgerro' => $arraybusca['msgerro']
            );
        }
        if ($ResultFase['COD_ACAOINT'] == 2) {

            if ($array['cpf'] != '') {
                $cpfcartao = $array['cpf'];
            } elseif ($array['cnpj'] != '') {
                $cpfcartao = $array['cnpj'];
            } else {
                $cpfcartao = $array['cartao'];
            }

            $id = fnEncode($array['empresa'] . ';' . $cpfcartao . ';' . $array['COD_UNIVEND']);

            //grava log
            //
            $xmlteste = addslashes(file_get_contents("php://input"));
            $inserarray = 'INSERT INTO log_tkt (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
                                             ("' . date("Y-m-d H:i:s") . '","' . $_SERVER['REMOTE_ADDR'] . '","' . $_SERVER['REMOTE_PORT'] . '",
                                              "' . $array['COD_USUARIO'] . '","' . $array['login'] . '","' . $array['empresa'] . '","' . $array['idloja'] . '","' . $array['idmaquina'] . '","0","' . $cpfcartao . '","' . $xmlteste . '","' . $xmlteste1 . '")';
            $loginputtkt = mysqli_query($array['ConnB'], $inserarray);

            /* if($cpfcartao='01734200014')
			{
                            $teste=array($inserarray);
			    print_r($teste);    
			}*/

            //                      
            mysqli_free_result($loginputtkt);
            mysqli_next_result($array['ConnB']);
            $msgr = 'acao_B_Ticket_de_Ofertas!';
            $totemplayer = "SELECT COD_PLAYERS FROM totem_players WHERE COD_EMPRESA='" . $array['empresa'] . "' and DES_PAGHOME='CAD' AND COD_UNIVEND='" . $array['idloja'] . "' LIMIT 1";
            $rstotemplayer = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $totemplayer));
            $urltotem = fnEncode(
                $array['login'] . ';'
                    . $array['senha'] . ';'
                    . $array['idloja'] . ';'
                    . $array['idmaquina'] . ';'
                    . $array['empresa'] . ';'
                    . $array['codvendedor'] . ';'
                    . $array['nomevendedor'] . ';'
                    . $rstotemplayer['COD_PLAYERS']
            );

            ////////////ofertas
            //=========================

            /////////ARRAY PARA GRAVA TKT

            if ($arraybusca['LOG_AVULSO'] == 'N') {
                if ($arraybusca['COD_CLIENTE'] != '' &&  $cpfcartao != '0' && $cpfcartao != false) {
                    $selconfig = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =" . $array['empresa'] . "   and LOG_ATIVO_TKT = 'S'";
                    $conf = mysqli_query($array['ConnB'], $selconfig);
                    $rwconfig = mysqli_fetch_assoc($conf);

                    if ($rwconfig['LOG_ATIVO_TKT'] == 'S') {
                        $classfpers = "call SP_CLASSIFICA_PERSONA_TKT('" . $arraybusca['COD_CLIENTE'] . "','" . $array['empresa'] . "')";
                        mysqli_query($array['ConnB'], $classfpers);

                        $arrayDados = array(
                            'cod_empresa' => $array['empresa'],
                            'idloja' => $array['idloja'],
                            'idmaquina' => $array['idmaquina'],
                            'cpf' => $array['cpf'],
                            'cartao' => $array['cartao'],
                            'cnpj' => '',
                            'id_cliente' => $arraybusca['COD_CLIENTE'],
                            'login' => $array['login'],
                            'codvendedor' => $array['codvendedor'],
                            'nomevendedor' => $array['nomevendedor'],
                            'pagina' => $array['pagina'],
                            'connadm' => $array['conn'],
                            'connempresa' => $array['ConnB'],
                            'cod_user' => $array['COD_USUARIO'],
                            'database' => $array['database'],
                            'LOG_WS' => $array['LOG_WS']

                        );

                        $fngeratkt = fngeratkt($arrayDados);

                        /*	if($cpfcartao='01734200014')
                                {
                                    print_r($fngeratkt);    
                                }    */

                        //======================================================== 
                        //FIM DO IF DA FLAG ATIVA OU DESATIVA



                        //=========================================================================
                        //'ofertasTicket'=>array('produtoTicket'=>array('descricao'=>'teste')),
                        fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msgr, $array['LOG_WS']);
                        $acaoRetorno .= $ResultFase['KEY_ACAOINT'] . ',';

                        $acao2 = array(
                            'url_ticketdeofertas' => 'https://ticket.fidelidade.mk/?tkt=' . $id,
                            'urltotem' => "https://totem.bunker.mk/consulta_V2.do?key=$urltotem&r=" . date("Ymdhis") . round(microtime(true) * 1000),
                            'regrapreco' => $regrapreco,
                            'ofertasHabito' => array('produtoHabito' => $fngeratkt['produtoHabito']),
                            'ofertasTicket' => array('produtoTicket' => $fngeratkt['produtoTicket']),
                            'ofertasPromocao' => array('produtoPromocao' => $fngeratkt['produtoPromocao']),
                            'coderro' => '17',
                            'msgerro' => 'bem vindo ao tktmania!'
                        );
                    } else {
                        $msg = "tktmania não esta habilitado";
                        fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msg, $array['LOG_WS']);
                        $acao2 = array(
                            'coderro' => '0',
                            'msgerro' => $msg,
                            'urltotem' => "https://totem.bunker.mk/consulta_V2.do?key=$urltotem&r=" . date("Ymdhis") . round(microtime(true) * 1000)
                        );
                    }
                } else {
                    $msg1 = "cliente não está fidelizado por esse motivo não gera ofertas !";
                    fngravalogMSG($array['conn'], $array['login'], $array['idcliente'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msg1, $array['LOG_WS']);

                    $acao2 = array(
                        'coderro' => '58',
                        'msgerro' => $msg1
                    );
                    //   $sqllog="INSERT INTO teste (des_teste,des_teste1) VALUES ('". serialize($arrayDados)."',$arraybusca[COD_CLIENTE])";
                    //  mysqli_query($array['ConnB'], $sqllog);
                }
            } else {
                $msg1 = "cliente não está fidelizado por esse motivo não gera ofertas !";
                fngravalogMSG($array['conn'], $array['login'], $array['idcliente'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msg1, $array['LOG_WS']);

                $acao2 = array(
                    'coderro' => '58',
                    'msgerro' => $msg1
                );
                //   $sqllog="INSERT INTO teste (des_teste,des_teste1) VALUES ('". serialize($arrayDados)."',$arraybusca[COD_CLIENTE])";
                //  mysqli_query($array['ConnB'], $sqllog);
            }
        }
        if ($ResultFase['COD_ACAOINT'] == 3) {
            $sql = 'select * from site_extrato where cod_empresa=' . $array['empresa'];
            $RSSITE = mysqli_query($array['ConnB'], $sql);
            if (!$RSSITE) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                try {
                    mysqli_query($array['conn'], $sql);
                } catch (mysqli_sql_exception $e) {
                    $msgsql = $e;
                }
                $msg = "Error na lista de SITE: $msgsql";
                $xamls = addslashes($msg);
                fngravalogMSG($array['conn'], $array['login'], $array['idcliente'], $array['cpf'], $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], 'ACAO PRODUTO', $xamls, $array['LOG_WS']);
            } else {
                $rwsite = mysqli_fetch_assoc($RSSITE);
                $DES_DOMINIO = $rwsite['DES_DOMINIO'];

                $msgr = 'acao_C_campanha!';
                fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $array['cpf'], $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msgr, $array['LOG_WS']);
                if ($DES_DOMINIO != "") {
                    $site = "https://$DES_DOMINIO.fidelidade.mk/";
                    $msg = "Modelo Padrão de hot site";
                    $cod = '59';
                    $xamls = addslashes($msg);
                    fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $array['cpf'], $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $xamls, $array['LOG_WS']);
                } else {
                    $site = "";
                    $msg = "Hot Site não cadastrado!";
                    $cod = '60';
                    $xamls = addslashes($msg);
                    fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $array['cpf'], $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $xamls, $array['LOG_WS']);
                }
                $totemplayer = "SELECT COD_PLAYERS FROM totem_players WHERE COD_EMPRESA='" . $array['empresa'] . "' and DES_PAGHOME='CAD' AND COD_UNIVEND='" . $array['idloja'] . "' LIMIT 1";
                $rstotemplayer = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $totemplayer));
                $urltotem = fnEncode(
                    $array['login'] . ';'
                        . $array['senha'] . ';'
                        . $array['idloja'] . ';'
                        . $array['idmaquina'] . ';'
                        . $array['empresa'] . ';'
                        . $array['codvendedor'] . ';'
                        . $array['nomevendedor'] . ';'
                        . $rstotemplayer['COD_PLAYERS']
                );
                $acaoRetorno .= $ResultFase['KEY_ACAOINT'] . ',';
                $acao3 = array(
                    'url_campanha' => $site,
                    'urltotem' => "https://totem.bunker.mk/consulta_V2.do?key=$urltotem&r=" . date("Ymdhis") . round(microtime(true) * 1000),
                    'coderro' => $cod,
                    'msgerro' => $msg
                );
            }
        }
        if ($ResultFase['COD_ACAOINT'] == 4) {
            if ($array['cpf'] == '') {
                $cpfcartao = $array['cartao'];
            } else {
                $cpfcartao = $array['cpf'];
            }
            $msgr = 'acao_D_mensagem!';
            fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msgr, $array['LOG_WS']);
            $acaoRetorno .= $ResultFase['KEY_ACAOINT'] . ',';

            //===================================
            $sql = "select * from comunicacao_modelo where cod_tipcomu=4 and cod_empresa=" . $array['empresa'] . " and cod_exclusa=0";
            $sqlexec = mysqli_query($array['ConnB'], $sql);
            if (!$sqlexec) {
                mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                try {
                    mysqli_query($array['ConnB'], $sql);
                } catch (mysqli_sql_exception $e) {
                    $msgsql = $e;
                }
                $msg = "Não há mensagem cadastrada: $msgsql";
                $xamls = addslashes($msg);
                fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msg, $array['LOG_WS']);
            } else {
                if (mysqli_num_rows($sqlexec) == 0) {
                    $msg = 'Não há mensagem cadastrada :-(!';
                    fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msg, $array['LOG_WS']);
                    $coderro = '61';
                    // exit();
                } else {
                    $sqlretorno = mysqli_fetch_assoc($sqlexec);
                    $msg = 'Mensagem PDV Ativa';
                    fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msg, $array['LOG_WS']);
                    $coderro = '62';
                }
            }

            $totemplayer = "SELECT COD_PLAYERS FROM totem_players WHERE COD_EMPRESA='" . $array['empresa'] . "' and DES_PAGHOME='CAD' AND COD_UNIVEND='" . $array['idloja'] . "' LIMIT 1";
            $rstotemplayer = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $totemplayer));
            $urltotem = fnEncode(
                $array['login'] . ';'
                    . $array['senha'] . ';'
                    . $array['idloja'] . ';'
                    . $array['idmaquina'] . ';'
                    . $array['empresa'] . ';'
                    . $array['codvendedor'] . ';'
                    . $array['nomevendedor'] . ';'
                    . $rstotemplayer['COD_PLAYERS']
            );

            //=====================================
            $acao4 = array(
                'txtmensagem' => $sqlretorno['DES_TEXTO_SMS'],
                'urltotem' => "https://totem.bunker.mk/consulta_V2.do?key=$urltotem&r=" . date("Ymdhis") . round(microtime(true) * 1000),
                'coderro' => $coderro,
                'msgerro' => $msg
            );
        }
        if ($ResultFase['COD_ACAOINT'] == 5) {
            if ($array['cpf'] == '') {
                $cpfcartao = $array['cartao'];
            } else {
                $cpfcartao = $array['cpf'];
            }

            $msgr = 'acao_E_ListadeOfertas!';
            fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msgr, $array['LOG_WS']);
            $acaoRetorno .= $ResultFase['KEY_ACAOINT'] . ',';

            //Select busca configuração TKT
            $selconfig = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =" . $array['empresa'] . "   and LOG_ATIVO_TKT = 'S'";
            $rwconfig = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $selconfig));

            if ($rwconfig['COD_CONFIGU'] != "") {

                $qtd_ofertas_tkt = $rwconfig['QTD_OFERTWS_TKT'];
                $regrapreco = $rwconfig['DES_PRATPRC'];

                //lista de ofertas
                $sql = "SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
                                    where  A.COD_EMPRESA = " . $array['empresa'] . " AND
                                       A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                                       A.COD_PRODUTO = C.COD_PRODUTO AND										   
                                       A.LOG_ATIVOTK = 'S' AND 
                                       A.LOG_OFERTAS = 'S' AND
                                       ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET(" . $array['idloja'] . ",A.COD_UNIVEND_AUT))) AND
                                       ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET(" . $array['idloja'] . ",A.COD_UNIVEND_BLK))) AND
                                       ((A.DAT_INIPTKT <= NOW()) AND (A.DAT_FIMPTKT >= NOW()) )  
                                       ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_ofertas_tkt";
                $sqlexec = mysqli_query($array['ConnB'], $sql);

                if (!$sqlexec) {
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                    try {
                        mysqli_query($array['ConnB'], $sql);
                    } catch (mysqli_sql_exception $e) {
                        $msgsql = $e;
                    }
                    $msg = "Error na lista de produto: $msgsql";
                    $xamls = addslashes($msg);
                    //fngravalogMSG($array['conn'],$dadosLogin['login'],$dadosLogin['idcliente'],$cpfcartao,$dadosLogin['idloja'],$dadosLogin['idmaquina'],$dadosLogin['codvendedor'],$dadosLogin['nomevendedor'],$array['pagina'],$xamls,$array['LOG_WS']);
                    fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $xamls, $array['LOG_WS']);
                    $acaoE = array('msgerro' => 'Erro na lista de produto!', 'coderro' => '63');
                } else {

                    //verifica se tem itens na lista de produtos
                    if (mysqli_num_rows($sqlexec) == 0) {
                        $msg = 'Não há Itens para exibir na lista!';
                        fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msg, $array['LOG_WS']);
                        $acaoE =  array('msgerro' => $msg, 'coderro' => '64');
                        // exit();
                    } else {

                        // exibi itens na lista de ws    
                        while ($sqlretorno = mysqli_fetch_assoc($sqlexec)) {

                            $cod_empresa = $array['empresa'];
                            if ($sqlretorno['DES_IMAGEM'] != "") {
                                $IMG = "https://img.bunker.mk/media/clientes/$cod_empresa/produtos/" . $sqlretorno['DES_IMAGEM'] . "";
                            }
                            $msg = 'OK';

                            $acaoE[] = array(
                                'codigoexterno' => $sqlretorno['COD_EXTERNO'],
                                'codigointerno' => $sqlretorno['COD_PRODUTO'],
                                'ean' => '',
                                'descricao' => $sqlretorno['NOM_PRODTKT'],
                                'preco' => fnformatavalorretorno($sqlretorno['VAL_PRODTKT']),
                                'valorcomdesconto' => fnformatavalorretorno($sqlretorno['VAL_PROMTKT']),
                                'imagem' => $IMG,
                                'msgpromocional' => $sqlretorno['DES_MENSGTKT'],
                                'regrapreco' => $regrapreco,
                                'coderro' => '',
                                'msgerro' => $msg
                            );
                        }
                    }
                }
            } else {

                fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], 'Não existe configuração de produtos do TICKET', $array['LOG_WS']);
                $acaoE[] = array('coderro' => 65, 'msgerro' => "Não existe configuração de produtos do TICKET");
            }
            $totemplayer = "SELECT COD_PLAYERS FROM totem_players WHERE COD_EMPRESA='" . $array['empresa'] . "' and DES_PAGHOME='CAD' AND COD_UNIVEND='" . $array['idloja'] . "' LIMIT 1";
            $rstotemplayer = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $totemplayer));
            $urltotem = fnEncode(
                $array['login'] . ';'
                    . $array['senha'] . ';'
                    . $array['idloja'] . ';'
                    . $array['idmaquina'] . ';'
                    . $array['empresa'] . ';'
                    . $array['codvendedor'] . ';'
                    . $array['nomevendedor'] . ';'
                    . $rstotemplayer['COD_PLAYERS']
            );

            $acao5 = array(
                'urltotem' => "https://totem.bunker.mk/consulta_V2.do?key=$urltotem&r=" . date("Ymdhis") . round(microtime(true) * 1000),
                'listaoferta' => $acaoE
            );
        }
        if ($ResultFase['COD_ACAOINT'] == 6) {
            $lista = 'acao_F_desconto!';
            $acaoRetorno .= $ResultFase['KEY_ACAOINT'] . ',';
            if ($array['cpf'] == '') {
                $cpfcartao = $array['cartao'];
            } else {
                $cpfcartao = $array['cpf'];
            }
            fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $lista, $array['LOG_WS']);
            $selconfig = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =" . $array['empresa'] . "   and LOG_ATIVO_TKT = 'S';";
            $rwconfig = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $selconfig));
            $qtd_ofertas_tkt = $rwconfig['QTD_OFERTWS_TKT'];
            $regrapreco = $rwconfig['DES_PRATPRC'];

            mysqli_free_result($rwconfig);
            mysqli_next_result($array['ConnB']);
            //capturar codigo externo do produto que vem da webservice para verificar na lista do cliente
            // se existe esse produto com desconto
            // return array( 'msgerro' =>  print_r($array),'coderro'=>'66');

            if (array_key_exists("codigoproduto", $array['ArrayOfertaProduto']['vendaitemoferta'])) {
                $cod_item = $array['ArrayOfertaProduto']['vendaitemoferta']['codigoproduto'];

                $sqlproduni = "SELECT COD_PRODUTO FROM produtocliente 
										WHERE cod_empresa=" . $array['empresa'] . " 
										  and COD_EXTERNO='" . $cod_item . "'";
                $rsproduni = mysqli_query($array['ConnB'], $sqlproduni);
                // $rwproduni=mysqli_fetch_assoc($rsproduni);

            } else {
                foreach ($array['ArrayOfertaProduto']['vendaitemoferta'] as $key => $dados) {
                    $cod_item .= $dados['codigoproduto'] . ',';
                }
                $cod_item = rtrim($cod_item, ',');
                $sqlproduni = "SELECT  GROUP_CONCAT(COD_PRODUTO SEPARATOR ',') COD_PRODUTO FROM produtocliente 
											WHERE cod_empresa=" . $array['empresa'] . " 
											  and COD_EXTERNO in($cod_item) GROUP BY COD_EMPRESA";
                $rsproduni = mysqli_query($array['ConnB'], $sqlproduni);
            }
            //verifica se tem atividade
            if (mysqli_num_rows($rsproduni) == 0) {

                $lista = 'Nenhum desconto ativo!';
                fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $lista, $array['LOG_WS']);
                $acaoF = array('msgerro' => $lista, 'coderro' => '66');
            } else {

                $rwproduni = mysqli_fetch_assoc($rsproduni);
                $sql = "SELECT prd.COD_PRODUTO,
										prd.COD_EXTERNO,
										des.PCT_DESCONTO,
										des.VAL_DESCONTO,
										des.DES_MENSGTKT 
									FROM DESCONTOS des
									INNER JOIN produtocliente prd ON prd.COD_PRODUTO=des.COD_PRODUTO
									   WHERE  ( des.DAT_INIPTKT <= '" . date('Y-m-d H:i:s') . "' AND 
												des.DAT_FIMPTKT >= '" . date('Y-m-d H:i:s') . "') and 
												des.LOG_PRODTKT ='S' 
												and des.COD_EMPRESA =" . $array['empresa'] . "
												and des.COD_PRODUTO in ($rwproduni[COD_PRODUTO]) 
												AND FIND_IN_SET (" . $array['idloja'] . ", des.COD_UNIVEND_AUT)";
                $EXECSQL = mysqli_query($array['ConnB'], $sql);


                //=======TESTE SQL
                if (!$EXECSQL) {
                    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
                    try {
                        mysqli_query($array['ConnB'], $sql);
                    } catch (mysqli_sql_exception $e) {
                        $msgsql = $e;
                    }
                    $msg = "Error na lista de desconto: $msgsql";
                    $lista = addslashes($msg);
                    fngravalogMSG($array['conn'], $array['login'], $array['idcliente'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $lista, $array['LOG_WS']);
                    $acaoF =  array('msgerro' => $lista, 'coderro' => '0');
                } else {

                    //verifica se tem atividade
                    if (mysqli_num_rows($EXECSQL) == 0) {

                        $lista = 'Nenhum desconto ativo!';
                        fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $lista, $array['LOG_WS']);
                        $acaoF = array('msgerro' => $lista, 'coderro' => '66');
                    } else {
                        //grava log 
                        $lista = 'Lista de desconto OK!';
                        fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $lista, $array['LOG_WS']);
                        //==========================================

                        //retor no da lista
                        $acaoRetorno .= $ResultFase['KEY_ACAOINT'] . ',';
                        while ($rwsql = mysqli_fetch_assoc($EXECSQL)) {
                            $acaoF[] = array(
                                'cod_interno' => $rwsql['COD_PRODUTO'],
                                'cod_externo' => $rwsql['COD_EXTERNO'],
                                'descontosobrepercentual' => fnformatavalorretorno($rwsql['PCT_DESCONTO']),
                                'descontosobrevalor' => fnformatavalorretorno($rwsql['VAL_DESCONTO']),
                                'regrapreco' => fnformatavalorretorno($regrapreco),
                                'coderro' => '67',
                                'msgerro' => $rwsql['DES_MENSGTKT']
                            );
                        }
                        //============================================  

                    }
                    //===================================================
                }
            }
            //fim do IF

            $totemplayer = "SELECT COD_PLAYERS FROM totem_players WHERE COD_EMPRESA='" . $array['empresa'] . "' and DES_PAGHOME='CAD' AND COD_UNIVEND='" . $array['idloja'] . "' LIMIT 1";
            $rstotemplayer = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $totemplayer));
            $urltotem = fnEncode(
                $array['login'] . ';'
                    . $array['senha'] . ';'
                    . $array['idloja'] . ';'
                    . $array['idmaquina'] . ';'
                    . $array['empresa'] . ';'
                    . $array['codvendedor'] . ';'
                    . $array['nomevendedor'] . ';'
                    . $rstotemplayer['COD_PLAYERS']
            );

            $acao6 = array(
                'urltotem' => "https://totem.bunker.mk/consulta_V2.do?key=$urltotem&r=" . date("Ymdhis") . round(microtime(true) * 1000),
                'desconto' => $acaoF
            );
        }
        if ($ResultFase['COD_ACAOINT'] == 7) {


            // cria chave de caatro por cartao/cpf
            if ($array['cpf'] == '') {
                $cpfcartao = $array['cartao'];
            } else {
                $cpfcartao = $array['cpf'];
            }
            $msgr = 'acao_G_Cupomdesconto!';
            fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msgr, $array['LOG_WS']);
            $acaoRetorno .= $ResultFase['KEY_ACAOINT'] . ',';
            $totemplayer = "SELECT COD_PLAYERS FROM totem_players WHERE COD_EMPRESA='" . $array['empresa'] . "' and DES_PAGHOME='CAD' AND COD_UNIVEND='" . $array['idloja'] . "' LIMIT 1";
            $rstotemplayer = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $totemplayer));
            $urltotem = fnEncode(
                $array['login'] . ';'
                    . $array['senha'] . ';'
                    . $array['idloja'] . ';'
                    . $array['idmaquina'] . ';'
                    . $array['empresa'] . ';'
                    . $array['codvendedor'] . ';'
                    . $array['nomevendedor'] . ';'
                    . $rstotemplayer['COD_PLAYERS']
            );
            //97397    
            //|| $array['idloja']=='695'
            // 695   
            //674    
            if ($array['empresa'] == '19') {
                $execupom = "CALL SP_VERIFICA_DESCONTO11('" . $array['empresa'] . "', '" . $array['cupomdesconto'] . "', '" . $array['CODIGOVENDA'] . "','" . $array['idloja'] . "');";
            } else {
                $execupom = "CALL SP_VERIFICA_DESCONTO1('" . $array['empresa'] . "', '" . $array['cupomdesconto'] . "', '" . $array['CODIGOVENDA'] . "','" . $array['idloja'] . "')";
            }

            $rscupom = mysqli_query($array['ConnB'], $execupom);
            while ($rwcupom = mysqli_fetch_assoc($rscupom)) {

                if ($array['empresa'] == '19') {
                    $VAL_PERDESCOT = fnformatavalorretorno($rwcupom['VAL_PERDESCOT']);
                } else {
                    $VAL_PERDESCOT = '0.00';
                }

                $acaoG[] = array(
                    'cod_interno' => $rwcupom['COD_PRODUTO'],
                    'cod_externo' => $rwcupom['COD_EXTERNO'],
                    'numcupom' => $rwcupom['COD_CUPOM'],
                    'descontosobrepercentual' => $VAL_PERDESCOT,
                    'descontosobrevalor' => fnformatavalorretorno($rwcupom['VAL_DESCONTO']),
                    'coderro' => '68',
                    'msgerro' => 'Parabens Voce tem descontos aproveite!'
                );
            }
            //mysqli_free_result($rwcupom);
            mysqli_next_result($array['ConnB']);
            //alteração dar desconto no brinde
            $brind = "Select  9999 AS  COD_CONTROLE,c.COD_VENDA,c.COD_VENDAPDV,c.COD_CUPOM,b.COD_PRODUTO,D.COD_EXTERNO,0 AS VAL_PERDESCOT, FORMAT(a.VAL_LIQUIDO - 0.01, 2, 'pt_BR') as VAL_DESCONTO  
                            from itemvenda_desc a,brindeextra b, vendas_desc  C, produtocliente D
                            WHERE a.cod_produto=b.cod_produto AND 
                                a.COD_CLIENTE=b.cod_cliente AND 
                                B.COD_PRODUTO=D.COD_PRODUTO AND 
                                C.COD_EMPRESA=D.COD_EMPRESA AND 
                                a.cod_venda=c.cod_venda AND 
                                C.COD_EMPRESA='" . $array['empresa'] . "' and
                                b.COD_STATUS=1 AND 
                                    date(dat_expira) > date(NOW())  AND 
                                    a.COD_VENDA='" . $array['CODIGOVENDA'] . "'";
            $rsbrind = mysqli_query($array['ConnB'], $brind);
            while ($rwbrind = mysqli_fetch_assoc($rsbrind)) {

                if ($array['empresa'] == '19') {
                    $VAL_PERDESCOT = fnformatavalorretorno($rwbrind['VAL_PERDESCOT']);
                } else {
                    $VAL_PERDESCOT = '0.00';
                }

                $acaoG[] = array(
                    'cod_interno' => $rwbrind['COD_PRODUTO'],
                    'cod_externo' => $rwbrind['COD_EXTERNO'],
                    'numcupom' => $rwbrind['COD_CUPOM'],
                    'descontosobrepercentual' => $VAL_PERDESCOT,
                    'descontosobrevalor' => $rwbrind['VAL_DESCONTO'],
                    'coderro' => '68',
                    'msgerro' => 'Parabens Voce tem Brindes aproveite!'
                );
            }

            $acao7 = array(
                'urltotem' => "https://totem.bunker.mk/consulta_V2.do?key=$urltotem&r=" . date("Ymdhis") . round(microtime(true) * 1000),
                'cupomdesconto' => $acaoG
            );
        }

        if ($ResultFase['COD_ACAOINT'] == 8) {
            if ($array['cpf'] == '') {
                $cpfcartao = $array['cartao'];
            } else {
                $cpfcartao = $array['cpf'];
            }
            $acaoRetorno .= $ResultFase['KEY_ACAOINT'] . ',';
            fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], 'acao_H_saldo/Consulta saldo', $array['LOG_WS']);


            if ($arraybusca['COD_CLIENTE'] == "") {

                $msg = "Cliente não tem saldo para exibir!";
                fngravalogMSG($array['conn'], $array['login'], $array['empresa'], $cpfcartao, $array['idloja'], $array['idmaquina'], $array['codvendedor'], $array['nomevendedor'], $array['pagina'], $msg, $array['LOG_WS']);
                $acao8 =  array('msgerro' => $msg, 'coderro' => '69');
            } else {
                mysqli_next_result($array['ConnB']);
                $consultasaldo = "CALL SP_CONSULTA_SALDO_CLIENTE(" . $arraybusca['COD_CLIENTE'] . ");";
                $sld = mysqli_query($array['ConnB'], $consultasaldo);
                $retSaldo = mysqli_fetch_assoc($sld);
                $saldodisponivel = fnvalorretorno($retSaldo['CREDITO_DISPONIVEL'], $array['decimal']);
                $saldototal = fnvalorretorno($retSaldo['TOTAL_CREDITO'], $array['decimal']);
                $creditovenda = fnvalorretorno($array['creditovenda'], $array['decimal']);
                $vantagemacumulada = "Quanto mais você usar mais vantagens você terá :-]";
                $msgerro = 'Seu saldo ;-]';
                //fim da consulta
                $totemplayer = "SELECT COD_PLAYERS FROM totem_players WHERE COD_EMPRESA='" . $array['empresa'] . "' and DES_PAGHOME='CAD' AND COD_UNIVEND='" . $array['idloja'] . "' LIMIT 1";
                $rstotemplayer = mysqli_fetch_assoc(mysqli_query($array['ConnB'], $totemplayer));
                $urltotem = fnEncode(
                    $array['login'] . ';'
                        . $array['senha'] . ';'
                        . $array['idloja'] . ';'
                        . $array['idmaquina'] . ';'
                        . $array['empresa'] . ';'
                        . $array['codvendedor'] . ';'
                        . $array['nomevendedor'] . ';'
                        . $rstotemplayer['COD_PLAYERS']
                );
                $urltotemextrato = fnEncode(
                    $array['login'] . ';'
                        . $array['senha'] . ';'
                        . $array['idloja'] . ';'
                        . $array['idmaquina'] . ';'
                        . $array['empresa'] . ';'
                        . $array['codvendedor'] . ';'
                        . $array['nomevendedor'] . ';'
                        . $cpfcartao
                );
                $testeurl = "https://totem.bunker.mk/consulta_V2.do?key=" . rawurlencode($urltotem) . "&r=" . date("Ymdhis") . round(microtime(true) * 1000);
                $acao8 = array(
                    'saldodisponivel' => $saldodisponivel,
                    'saldototal' => $saldototal,
                    'creditovenda' => $creditovenda,
                    'vantagemacumulada' => $vantagemacumulada,
                    'urltotem' => $testeurl,
                    'urlsaldo' => "http://extrato.bunker.mk?key=" . rawurlencode($urltotemextrato),
                    'coderro' => '18',
                    'msgerro' => $msgerro
                );
            }

            mysqli_free_result($retSaldo);
            mysqli_next_result($array['ConnB']);
        }
    };

    $acaoRetorno = substr($acaoRetorno, 0, -1);
    $cod_erro = $array['coderro'];
    $msg = $array['menssagem'];

    //retorno
    //fim aqui
    return array(
        'acoesfidelizacao' => $acaoRetorno,
        'acao_A_cadastro' => $acao1,
        'acao_B_Ticket_de_Ofertas' => $acao2,
        'acao_C_campanha' => $acao3,
        'acao_D_mensagem' => $acao4,
        'acao_E_ListadeOfertas' => $acao5,
        'acao_F_desconto' => $acao6,
        'acao_G_Cupomdesconto' => $acao7,
        'acao_H_saldo' => $acao8,
        'retornoGenerico' => $row1['contador'],
        'coderro' => $cod_erro,
        'msgerro' => $msg
    );
}
function fnGravaArrayvenda($conn, $conadm, $array, $array1, $usercod, $xmlget, $log)
{
    $cartao = fnlimpaCPF($array['cartao']);
    if ($cartao == '') {
        $cartao = '0';
    }
    //$xmlget= mb_convert_encoding($xmlget, 'UTF-8',mb_detect_encoding($xmlget, 'UTF-8, ISO-8859-1', true));

    $dados_login = addslashes(str_replace(array("\n", ""), array("", " "), var_export($array1, true)));
    $arralogin = str_replace(" ", "", $dados_login);
    //$xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($array,true)));
    // $arraynormal = str_replace(" ","",$xamls);
    $xmlgetpost = addslashes($xmlget);
    $inserarray = 'INSERT INTO ORIGEMVENDA (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN,CUPOM)values
                                 ("' . date("Y-m-d H:i:s") . '","' . $_SERVER['REMOTE_ADDR'] . '","' . $_SERVER['REMOTE_PORT'] . '",
                                  "' . $usercod . '","' . $array1['login'] . '","' . $array1['idcliente'] . '","' . $array1['idloja'] . '","' . $array1['idmaquina'] . '","' . $array['id_vendapdv'] . '","' . $cartao . '","' . $xmlgetpost . '","' . $arralogin . '","' . $array['cupomfiscal'] . '")';
    // return $inserarray;                      

    $arraP = mysqli_query($conn, $inserarray);

    if (!$arraP) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($conn, $inserarray);
        } catch (mysqli_sql_exception $e) {
            $msgsql = $e;
        }
        $msg = "Error description SP_ALTERA_CLIENTES_WS: $msgsql";
        $xamls = addslashes($msg);
        fngravalogMSG($conadm, $array1['login'], $array1['idcliente'], $cartao, $array1['idloja'], $array1['idmaquina'], $array1['codvendedor'], $array1['nomevendedor'], 'InsereVenda', $xamls, $log);
        Grava_log($conn, 1, $xamls);
        $msg1 = 'INSERT INTO msg_venda (ID,DATA_HORA,MSG)values
                                            (1,"' . date("Y-m-d H:i:s") . '","' . $xamls . '")';
        mysqli_query($conn, $msg1);
    } else {
        fngravalogMSG($conadm, $array1['login'], $array1['idcliente'], $cartao, $array1['idloja'], $array1['idmaquina'], $array1['codvendedor'], $array1['nomevendedor'], 'InsereVenda', 'log xml OK!', $log);
        $COD_LOG = mysqli_insert_id($conn);
    }
    return $COD_LOG;
}
function venda_avulsa($arrayconn, $dadosLogin, $conn, $dec)
{
    $valortotalbruto = fnFormatvalor($arrayconn['valortotalbruto'], $dec);
    $DescontoTotalvalor = fnFormatvalor($arrayconn['descontototalbruto'], $dec);
    $ValorTotalLiquido = fnFormatvalor($arrayconn['valortotalLiquido'], $dec);



    if ($arrayconn['cartao'] == 0) {
        ////////////////////////////////////////////////
        $cad_venda = "CALL SP_INSERE_VENDA_WS_AVULSO(   0,
                                                            0,
                                                            '" . $arrayconn['cod_empresa'] . "', 
                                                            '" . $arrayconn['cod_avulso'] . "',
                                                            '1',
                                                            '3',
                                                            '" . $arrayconn['cod_univend'] . "',
                                                            '" . $arrayconn['formapag'] . "',
                                                            '" . $valortotalbruto . "',
                                                            0,
                                                            '" . $arrayconn['valor_resgate'] . "',
                                                            '" . $DescontoTotalvalor . "',
                                                            '" . $arrayconn['idpdv'] . "',
                                                            '" . $arrayconn['COD_USUARIO'] . "',
                                                            '" . $arrayconn['TIP_CONTABIL'] . "',
                                                            " . $arrayconn['COD_MAQUINA'] . ",
                                                            '" . $arrayconn['cupom'] . "',
                                                            '" . $arrayconn['cod_vendedor'] . "',
                                                             '" . $arrayconn['datatimews'] . "',
                                                             '" . $arrayconn['COD_ATENDENTE'] . "'    
                                                            );";
        $rewsinsert = mysqli_query($arrayconn['connB'], $cad_venda);
        if (!$rewsinsert) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                mysqli_query($arrayconn['connB'], $cad_venda);
            } catch (mysqli_sql_exception $e) {
                $msgsql = $e;
            }
            $msg = "Error description venda avulsa: $msgsql";
            $xamls = addslashes($msg);
            fngravalogMSG($arrayconn['conn'], $dadosLogin['login'], $dadosLogin['idcliente'], $arrayconn['cartao'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $xamls, $arrayconn['log']);
        } else {
            fngravalogMSG($arrayconn['conn'], $dadosLogin['login'], $dadosLogin['idcliente'], $arrayconn['cartao'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', 'venda avulsa ok', $arrayconn['log']);
            $row_venda = mysqli_fetch_assoc($rewsinsert);
        }

        //=================================================================================================     
    } else {
        //===================================================================================================
        $cad_venda = "CALL SP_INSERE_VENDA_WS( 0,
                                                                            '" . $arrayconn['cod_empresa'] . "', 
                                                                            '" . $arrayconn['cod_cliente'] . "',
                                                                            '1',
                                                                            '3',
                                                                            '" . $arrayconn['cod_univend'] . "',
                                                                            '" . $arrayconn['formapag'] . "',
                                                                            '" . $valortotalbruto . "',
                                                                            0,
                                                                            '" . $arrayconn['valor_resgate'] . "',
                                                                            '" . $DescontoTotalvalor . "',
                                                                            '" . $arrayconn['idpdv'] . "',
                                                                            '" . $arrayconn['COD_USUARIO'] . "',
                                                                            '" . $arrayconn['TIP_CONTABIL'] . "',
                                                                            " . $arrayconn['COD_MAQUINA'] . ",
                                                                            '" . $arrayconn['cupom'] . "',
                                                                            '" . $arrayconn['cod_vendedor'] . "',
                                                                            '" . $arrayconn['datatimews'] . "',
                                                                             '" . $arrayconn['COD_ATENDENTE'] . "'    
                                                                            );";

        $rewsinsert = mysqli_query($arrayconn['connB'], $cad_venda);
        if (!$rewsinsert) {
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
            try {
                mysqli_query($arrayconn['connB'], $cad_venda);
            } catch (mysqli_sql_exception $e) {
                $msgsql = $e;
            }
            $msg = "cliente venda: $msgsql" . addslashes($cad_venda);
            $xamls = addslashes($msg);
            fngravalogMSG($conn, $dadosLogin['login'], $dadosLogin['idcliente'], $arrayconn['cartao'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', $xamls, $arrayconn['log']);
        } else {

            $row_venda = mysqli_fetch_assoc($rewsinsert);
            fngravalogMSG($conn, $dadosLogin['login'], $dadosLogin['idcliente'], $arrayconn['cartao'], $dadosLogin['idloja'], $dadosLogin['idmaquina'], $dadosLogin['codvendedor'], $dadosLogin['nomevendedor'], 'InsereVenda', "Processo de venda concluido!", $arrayconn['log']);
        }


        //====================================================================================================   
    }
    return $row_venda['COD_VENDA'];
}
function fnFormaPAG($conn, $pag, $empresa)
{

    $formaPag = 'select *,count(COD_FORMAPA) as existe from formapagamento where DES_FORMAPA="' . $pag . '" and COD_EMPRESA ="' . $empresa . '"';
    $formaPagR = mysqli_fetch_assoc(mysqli_query($conn, $formaPag));
    if ($formaPagR['existe'] != 0) {
        $formaPagN = $formaPagR['COD_FORMAPA'];
    } else {

        $inserformpa = 'INSERT INTO formapagamento (COD_EXTERNO,DES_FORMAPA,COD_EMPRESA)
                                                                    values
                                                                  (0,"' . $pag . '","' . $empresa . '")';
        mysqli_query($conn, $inserformpa);
        $formaPagN = mysqli_insert_id($conn);
    }
    return $formaPagN;
}
function limitarTexto($texto, $limite)
{
    //$texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
    $texto = mb_strimwidth($texto, 0, $limite, "...");
    return $texto;
}
function limitarTextoLimpo($texto, $limite)
{
    //$texto = substr($texto, 0, strrpos(substr($texto, 0, $limite), ' ')) . '...';
    $texto = mb_strimwidth($texto, 0, $limite, "");
    return $texto;
}
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
function fnVerificasaldo($arrayvalorres)
{
    // =H22/G22*100;
    $percentual = ($arrayvalorres['vl_venda'] * $arrayvalorres['PCT_MAXRESG']) / 100;
    return floor($percentual * 100) / 100;
}

function fnVerificasaldo_venda($arrayvalorres)
{
    // =H22/G22*100;
    $percentual = ($arrayvalorres['vl_venda'] * $arrayvalorres['PCT_MAXRESG']) / 100;
    return round($percentual, 2);
}

//function generateRandomString($length = 10) {
function generateRandomString($length = 1, $characters)
{
    //$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function fngeraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false)
{
    //$lmin = 'abcdefghijklmnopqrstuvwxyz';
    $lmai = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    $num = '123456789';
    //$simb = '@#$';
    $retorno = '';
    $caracteres = '';
    $caracteres .= $lmin;
    if ($maiusculas) $caracteres .= $lmai;
    if ($numeros) $caracteres .= $num;
    if ($simbolos) $caracteres .= $simb;
    $len = strlen($caracteres);
    for ($n = 1; $n <= $tamanho; $n++) {
        $rand = mt_rand(1, $len);
        $retorno .= $caracteres[$rand - 1];
    }
    return $retorno;
}


function sendMessage($array)
{



    $content = array(
        "en" => $array['msg']
    );

    $fields = array(
        'app_id' => "39a9aedc-8dd1-435f-8585-66a8d0e34528",
        'filters' => array(
            array("field" => "tag", "key" => "RD_userId", "relation" => "=", "value" => $array['cartao']),
            array("field" => "amount_spent", "relation" => "=", "value" => "0")
        ),
        'data' => array(
            "RD_userId" => $array['cartao'],
            "RD_userCompany" => $array['entidade'],
            "RD_userMail" => $array['email'],
            "RD_userName" => $array['nome'],
            "RD_userType" => $array['cod_tpcliente']
        ),
        'contents' => $content
    );

    $fields = json_encode($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic ZDNhMzk1YTUtZTE0ZC00MWRkLWI5MTktYmIyOGQzMTY5ZjBk'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
function fnatendente($conn, $NOM_USUARIO, $COD_MULTEMP, $COD_UNIVEND, $cod_externo)
{

    if (rtrim(trim($NOM_USUARIO)) != '') {

        $sqlbusca = "select count(*) as exist,COD_USUARIO,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST 
                       from usuarios where 
					   COD_EMPRESA=$COD_MULTEMP and 
					   COD_EXTERNO='" . $cod_externo . "' AND
	                   FIND_IN_SET('" . $COD_UNIVEND . "',COD_UNIVEND)
					   ";

        $result = mysqli_fetch_assoc(mysqli_query($conn, $sqlbusca));

        if ($result['exist'] == 0) {

            $sql = 'insert into usuarios (COD_EXTERNO,COD_EMPRESA,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST,DAT_CADASTR,LOG_ESTATUS)
                                        values
                                        (
                                        "' . $cod_externo . '",
                                        "' . $COD_MULTEMP . '",
                                        "Atendente:' . $NOM_USUARIO . '",
                                        "11",
                                        "' . $COD_MULTEMP . '",
                                        "' . $COD_UNIVEND . '",
                                        "11",
                                        "' . DATE('Y-m-d H:i:s') . '",
										"S"
                                        )';

            $arraP = mysqli_query($conn, $sql);
            $COD_VENDEDOR = mysqli_insert_id($conn);

            return $COD_VENDEDOR;
        } else {

            $COD_VENDEDOR = $result['COD_USUARIO'];

            return $COD_VENDEDOR;
        }
    } else {

        $COD_VENDEDOR = 0;
        return $COD_VENDEDOR;
    }
}
function fnAcentos($string)
{
    // matriz de entrada
    $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'Ã', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç', '�', '`', '?');

    // matriz de saída
    $by   = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C', '', '', '');

    // devolver a string
    return str_replace($what, $by, $string);
}
function FnDebitosWS($arraydebitos)
{
    //executar o ssql
    $sqlcomdebt = "SELECT                 pedido.TIP_LANCAMENTO 
                                            ,pedido.COD_VENDA
                                            ,pedido.COD_PRODUTO 
                                            ,emp.NOM_EMPRESA
                                            ,pedido.DAT_CADASTR 
                                            , pedido.COD_ORCAMENTO
                                            , canal.DES_CANALCOM
                                            , canal.COD_CANALCOM
                                            ,SUM(round(pedido.QTD_PRODUTO,0)) AS QTD_PRODUTO
                                            ,SUM(round(pedido.QTD_SALDO_ATUAL,0)) QTD_SALDO_ATUAL
                                            , pedido.VAL_UNITARIO
                                            , pedido.VAL_UNITARIO * pedido.QTD_PRODUTO AS VAL_TOTAL 
                                            , if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                            FROM pedido_marka pedido 
                            INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                            INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                            INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                            WHERE pedido.COD_ORCAMENTO > 0 AND 
                                   pedido.COD_EMPRESA =" . $arraydebitos['COD_EMPRESA'] . " AND
                                    PAG_CONFIRMACAO='S' and
                                    canal.COD_TPCOM=" . $arraydebitos['COD_CANALCOM'] . "
                                    AND pedido.QTD_SALDO_ATUAL > 0  AND 
	                            pedido.DAT_VALIDADE IS NOT NULL and
                                    pedido.TIP_LANCAMENTO ='C' 
                            	GROUP BY  pedido.TIP_LANCAMENTO	            
                           ORDER BY pedido.TIP_LANCAMENTO desc ";
    $rwarraysql = mysqli_query($arraydebitos['CONNADM'], $sqlcomdebt);
    if ($rwarraysql->num_rows <= 0) {
        $DebSaldo = '0';
    } else {
        while ($rssaldo = mysqli_fetch_assoc($rwarraysql)) {
            //if($rssaldo['TIP_LANCAMENTO']=='D'){$DebSaldo=  $rssaldo['QTD_PRODUTO'];}
            //if ($rssaldo['TIP_LANCAMENTO']=='C') {$CredSaldo= $rssaldo['QTD_PRODUTO'];}  
            $DebSaldo = $rssaldo['QTD_SALDO_ATUAL'];
        }
    }
    // $saldorestante=bcsub($CredSaldo, $DebSaldo);
    $saldorestante = $DebSaldo;
    $saldoDiferenca = abs(bcsub($arraydebitos['quantidadeEmailenvio'], $CredSaldo));

    if ($consulta) {
        return array(
            'saldorestante' => $saldorestante,
            'qtd_envio' => $arraydebitos['quantidadeEmailenvio'],
            'saldo_apos_envio' => ($saldorestante - $arraydebitos['quantidadeEmailenvio']),
            'tem_saldo' => ($saldorestante >= $arraydebitos['quantidadeEmailenvio'])
        );
        exit;
    }
    //================================
    if ($arraydebitos['PERMITENEGATIVO'] == 'S') {

        //inserir debito
        $sqlinDebito = "INSERT INTO pedido_marka (COD_ORCAMENTO, 
                                                    COD_PRODUTO, 
                                                    QTD_PRODUTO, 
                                                    VAL_UNITARIO, 
                                                    COD_EMPRESA, 
                                                    COD_UNIVEND, 
                                                    ID_SESSION_PAGSEGURO, 
                                                    PAG_CONFIRMACAO, 
                                                    TIP_LANCAMENTO, 
                                                    COD_CAMPANHA,
                                                    LOG_TESTE,
                                                    DAT_CADASTR)
                                                    VALUES ('" . date('ymdHis') . "', 
                                                            '" . $arraydebitos['COD_CANALCOM'] . "', 
                                                            '" . $arraydebitos['quantidadeEmailenvio'] . "', 
                                                            '0.000000', 
                                                            '" . $arraydebitos['COD_EMPRESA'] . "', 
                                                            '1', 
                                                            'DEBITO',
                                                             'S', 
                                                             'D',
                                                             '" . $arraydebitos['COD_CAMPANHA'] . "',
                                                             '" . $arraydebitos['LOG_TESTE'] . "',
                                                             '" . $arraydebitos['DAT_CADASTR'] . "');";
        mysqli_query($arraydebitos['CONNADM'], $sqlinDebito);

        $DebSaldo = $DebSaldo + $arraydebitos['quantidadeEmailenvio'];
        $msg = 'Permitindo negativos!';
        $cod_msg = '3';
        $QTDUPDATE = $arraydebitos['quantidadeEmailenvio'];
        $QTDUPDATE1 = $arraydebitos['quantidadeEmailenvio'];
        unset($sqlinDebito);
        //dabitos dentro da validade
        $sql = "SELECT           pedido.TIP_LANCAMENTO 
                                              ,pedido.COD_VENDA
                                              ,pedido.COD_PRODUTO 
                                              ,emp.NOM_EMPRESA
                                              ,pedido.DAT_CADASTR 
                                              ,pedido.DAT_VALIDADE
                                              ,pedido.COD_ORCAMENTO
                                              ,canal.DES_CANALCOM
                                              ,canal.COD_CANALCOM
                                              ,SUM(round(pedido.QTD_PRODUTO,0)) AS QTD_PRODUTO
                                              ,pedido.QTD_SALDO_ATUAL
                                              ,pedido.VAL_UNITARIO
                                              ,pedido.VAL_UNITARIO * pedido.QTD_PRODUTO AS VAL_TOTAL 
                                              ,if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                                                  FROM pedido_marka pedido 
                                                   INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                                                   INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                                                   INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                                                   WHERE pedido.COD_ORCAMENTO > 0 AND 
                                                          pedido.COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND
                                                          PAG_CONFIRMACAO IN ('S') AND
                                                          pedido.TIP_LANCAMENTO ='C' AND
                                                          pedido.QTD_SALDO_ATUAL > 0 AND
                                                          canal.COD_TPCOM=" . $arraydebitos['COD_CANALCOM'] . " AND
                                                          pedido.DAT_VALIDADE IS not null
                                                       GROUP BY pedido.COD_VENDA         
                                                  ORDER BY  pedido.DAT_VALIDADE ASC ";
        $rwsql = mysqli_query($arraydebitos['CONNADM'], $sql);
        //verificar se existe vencimento
        if ($rwsql->num_rows > 0) {
            while ($rssql = mysqli_fetch_assoc($rwsql)) {

                if ($QTDUPDATE1 >= $rssql['QTD_SALDO_ATUAL']) {
                    if ($QTDUPDATE1 == '1') {
                        $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'$rssql[QTD_SALDO_ATUAL]' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                        mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                    } else {
                        $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'$rssql[QTD_SALDO_ATUAL]' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                        mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                    }
                    $QTDUPDATE1 -= $rssql['QTD_SALDO_ATUAL'];
                } else {
                    $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-" . $QTDUPDATE1 . "' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                    mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                    $QTDUPDATE1 -= $rssql['QTD_SALDO_ATUAL'];
                }
                if ($QTDUPDATE1 <= '0') {
                    break;
                }
            }
        }
    } else {


        if ($saldorestante >= $arraydebitos['quantidadeEmailenvio']) {
            //$QTDUPDATE='0'; 
            // $msg='voce ainda possui saldo';
            //$cod_msg='4';
            if ($arraydebitos['CONFIRMACAO'] == 'S') {
                $alterac = 'Cobranca gerada e debitada';
                $cod_msg = '1';
                $QTDUPDATE = $arraydebitos['quantidadeEmailenvio'];
                $QTDUPDATE1 = $arraydebitos['quantidadeEmailenvio'];
                //inserir debito
                $sqlinDebito = "INSERT INTO pedido_marka (COD_ORCAMENTO, 
                                                       COD_PRODUTO, 
                                                       QTD_PRODUTO, 
                                                       VAL_UNITARIO, 
                                                       COD_EMPRESA, 
                                                       COD_UNIVEND, 
                                                       ID_SESSION_PAGSEGURO, 
                                                       PAG_CONFIRMACAO, 
                                                       TIP_LANCAMENTO, 
                                                       COD_CAMPANHA,
                                                       LOG_TESTE,
                                                       DAT_CADASTR)
                                                       VALUES ('" . date('ymdHis') . "', 
                                                               '" . $arraydebitos['COD_CANALCOM'] . "', 
                                                               '" . $arraydebitos['quantidadeEmailenvio'] . "', 
                                                               '0.000000', 
                                                               '" . $arraydebitos['COD_EMPRESA'] . "', 
                                                               '1', 
                                                               'DEBITO',
                                                                'S', 
                                                                'D',
                                                                '" . $arraydebitos['COD_CAMPANHA'] . "',
                                                                '" . $arraydebitos['LOG_TESTE'] . "',
                                                                '" . $arraydebitos['DAT_CADASTR'] . "');";
                mysqli_query($arraydebitos['CONNADM'], $sqlinDebito);
                // $DebSaldo=$DebSaldo+$arraydebitos['quantidadeEmailenvio']; 
                $saldoDiferenca = abs(bcsub($arraydebitos['quantidadeEmailenvio'], $CredSaldo));
                unset($sqlinDebito);
                //dabitos dentro da validade
                $sql = "SELECT           pedido.TIP_LANCAMENTO 
                                              ,pedido.COD_VENDA
                                              ,pedido.COD_PRODUTO 
                                              ,emp.NOM_EMPRESA
                                              ,pedido.DAT_CADASTR 
                                              ,pedido.DAT_VALIDADE
                                              ,pedido.COD_ORCAMENTO
                                              ,canal.DES_CANALCOM
                                              ,canal.COD_CANALCOM
                                              ,SUM(round(pedido.QTD_PRODUTO,0)) AS QTD_PRODUTO
                                              ,pedido.QTD_SALDO_ATUAL
                                              ,pedido.VAL_UNITARIO
                                              ,pedido.VAL_UNITARIO * pedido.QTD_PRODUTO AS VAL_TOTAL 
                                              ,if(pedido.PAG_CONFIRMACAO='S', 'Pagamento Confirmado', 'Aguardando Confirmação de Pagamento') AS DES_SITUACAO
                                                  FROM pedido_marka pedido 
                                                   INNER JOIN produto_marka prod ON prod.COD_PRODUTO = pedido.COD_PRODUTO 
                                                   INNER JOIN canal_comunicacao canal ON canal.COD_CANALCOM = prod.COD_CANALCOM 
                                                   INNER JOIN empresas emp ON emp.COD_EMPRESA = pedido.COD_EMPRESA
                                                   WHERE pedido.COD_ORCAMENTO > 0 AND 
                                                          pedido.COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND
                                                          PAG_CONFIRMACAO IN ('S') AND
                                                          pedido.TIP_LANCAMENTO ='C' AND
                                                          pedido.QTD_SALDO_ATUAL > 0 AND
                                                          canal.COD_TPCOM=" . $arraydebitos['COD_CANALCOM'] . " AND
                                                          pedido.DAT_VALIDADE IS not null
                                                       GROUP BY pedido.COD_VENDA         
                                                  ORDER BY  pedido.DAT_VALIDADE ASC ";
                $rwsql = mysqli_query($arraydebitos['CONNADM'], $sql);
                //verificar se existe vencimento
                if ($rwsql->num_rows > 0) {
                    while ($rssql = mysqli_fetch_assoc($rwsql)) {

                        if ($QTDUPDATE1 >= $rssql['QTD_SALDO_ATUAL']) {
                            if ($QTDUPDATE1 == '1') {
                                $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'$rssql[QTD_SALDO_ATUAL]' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                                mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                            } else {
                                $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'$rssql[QTD_SALDO_ATUAL]' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                                mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                            }
                            $QTDUPDATE1 -= $rssql['QTD_SALDO_ATUAL'];
                        } else {
                            $updatesaldo = "UPDATE pedido_marka SET QTD_SALDO_ATUAL=QTD_SALDO_ATUAL-'" . $QTDUPDATE1 . "' WHERE COD_EMPRESA ='" . $arraydebitos['COD_EMPRESA'] . "' AND COD_VENDA=$rssql[COD_VENDA]";
                            mysqli_query($arraydebitos['CONNADM'], $updatesaldo);
                            $QTDUPDATE1 -= $rssql['QTD_SALDO_ATUAL'];
                        }
                        if ($QTDUPDATE1 <= '0') {
                            break;
                        }
                    }
                }
            } else {
                $alterac = 'Não foram gerados debitos';
                $cod_msg = '2';
            }
        } else {
            $QTDUPDATE = '0';
            $msg = 'Nao Possui mais saldo';
            $cod_msg = '5';
        }
    }



    return array(
        'QTD_AFETADA' => $QTDUPDATE,
        'MSG' => $msg,
        'cod_msg' => $cod_msg,
        'MSG_ATERACAO' => $alterac,
        'cod_altera' => $cod_alterac,
        'DEBT' => $DebSaldo,
        'Cred' => $CredSaldo,
        'Diferenca' => $saldoDiferenca,
        'Saldorestante' => abs($saldorestante)
    );
}
function fnCompString($arrayWs, $cod_empresa, $cpf, $CONNADM, $conntmp)
{


    $CAMPOSSQL = "select controle_campos.COD_CAMPOOBG,INTEGRA_CAMPOOBG.KEY_CAMPOOBG,INTEGRA_CAMPOOBG.DES_CAMPOOBG from controle_campos 
                  inner join INTEGRA_CAMPOOBG on INTEGRA_CAMPOOBG.COD_CAMPOOBG=controle_campos.COD_CAMPOOBG  
                  where cod_empresa=$cod_empresa ";
    $CAMPOQUERY = mysqli_query($CONNADM, $CAMPOSSQL);
    if ($CAMPOQUERY->num_rows >= 1) {
        while ($ARRAYCAMPOQUERY = mysqli_fetch_assoc($CAMPOQUERY)) {
            $log_Webservice .= $ARRAYCAMPOQUERY[KEY_CAMPOOBG] . ',';
            $log_base .= ' UPPER(' . $ARRAYCAMPOQUERY['DES_CAMPOOBG'] . ') as ' . $ARRAYCAMPOQUERY[KEY_CAMPOOBG] . ',';
        }
        $arrayWs[senha] = fnEncode($arrayWs[senha]);
        $cleanArray = array_map('strtoupper', array_intersect_key(
            $arrayWs,  // the array with all keys
            array_flip(explode(',', rtrim($log_Webservice, ','))) // keys to be extracted
        ));
        ksort($cleanArray);
        foreach ($cleanArray as $keyws => $valuews) {
            // $valuews12.=preg_replace("/[^a-zA-Z0-9]/", '', $valuews).'----';
            $concatdadosws .= preg_replace("/[^a-zA-Z0-9]/", '', $valuews);
        }
        $concatdadosws = base64_encode($concatdadosws);

        $buscli = "SELECT
                  " . rtrim($log_base, ',') . "
                    FROM clientes

                     WHERE cod_empresa=$cod_empresa  and
                          case when num_cgcecpf='$cpf' then  1
                               when num_cartao='$cpf' then  2
                      ELSE 0 END  IN (1,2)";

        $sqldados = mysqli_query($conntmp, $buscli);
        $rsdados = mysqli_fetch_all($sqldados, MYSQLI_ASSOC);
        ksort($rsdados[0]);
        foreach ($rsdados[0] as $key => $value) {

            //  $value12.=preg_replace("/[^a-zA-Z0-9]/", '',$value).'----';
            $concatdados .= preg_replace("/[^a-zA-Z0-9]/", "", $value);
        }
        $concatdados = base64_encode($concatdados);

        if ($sqldados->num_rows >= 1) {
            if ($concatdados != $concatdadosws) {
                $insqllog = "insert INTO log_alter_clientes (	COD_CLIENTE, 
                                                                COD_EMPRESA, 
                                                                COD_ENTIDAD, 
                                                                NOM_CLIENTE, 
                                                                DES_APELIDO, 
                                                                DES_CONTATO, 
                                                                DES_SENHAUS, 
                                                                LOG_USUARIO, 
                                                                DES_EMAILUS, 
                                                                DAT_CADASTR, 
                                                                COD_USUCADA, 
                                                                COD_ALTERAC, 
                                                                DAT_ALTERAC, 
                                                                COD_EXCLUSA, 
                                                                DAT_EXCLUSA, 
                                                                NUM_CGCECPF, 
                                                                TIP_CLIENTE, 
                                                                LOG_ESTATUS, 
                                                                LOG_TROCAPROD,
                                                                NUM_RGPESSO, 
                                                                DAT_NASCIME, 
                                                                COD_ESTACIV, 
                                                                COD_SEXOPES, 
                                                                NUM_TENTATI, 
                                                                NUM_TELEFON, 
                                                                NUM_CELULAR, 
                                                                NUM_COMERCI, 
                                                                COD_EXTERNO, 
                                                                NUM_CARTAO,
                                                                DES_ENDEREC, 
                                                                NUM_ENDEREC, 
                                                                DES_COMPLEM, 
                                                                DES_BAIRROC, 
                                                                NUM_CEPOZOF, 
                                                                NOM_CIDADEC, 
                                                                COD_ESTADOF, 
                                                                COD_PROFISS, 
                                                                COD_UNIVEND, 
                                                                COD_UNIVEND_PREF,
                                                                LOG_FIDELIDADE, 
                                                                LOG_EMAIL,
                                                                LOG_SMS, 
                                                                LOG_TELEMARK, 
                                                                LOG_WHATSAPP, 
                                                                LOG_PUSH, 
                                                                LOG_FIDELIZADO, 
                                                                DES_COMENT, 
                                                                NOM_PAI, 
                                                                NOM_MAE, 
                                                                IDADE,
                                                                DIA,
                                                                MES, 
                                                                ANO, 
                                                                LOG_AVULSO, 
                                                                COD_MAQUINA, 
                                                                COD_VENDEDOR, 
                                                                DAT_ULTCOMPR, 
                                                                COD_MULTEMP,
                                                                KEY_EXTERNO, 
                                                                COD_TPCLIENTE, 
                                                                COD_ATENDENTE,
                                                                DAT_PRICOMPR,
                                                                LOG_FUNCIONA, 
                                                                LOG_ATIVCAD,
                                                                LOG_CADOK,
                                                                COD_CATEGORIA,
                                                                COD_CATEGORIA_U, 
                                                                LAT,
                                                                LNG, 
                                                                COD_INDICAD, 
                                                                DAT_INDICAD, 
                                                                ID_ASSOCIADO,
                                                                COD_FREQUENCIA, 
                                                                VAL_FREQUENCIA, 
                                                                COD_FREQUENCIA_U, 
                                                                VAL_FREQUENCIA_U, 
                                                                LOG_CADTOTEM,
                                                                COD_CADPESQ,
                                                                COD_UNIVEND_ANT, 
                                                                LOG_OFERTAS,
                                                                DES_TOKEN,
                                                                LOG_TERMO ) SELECT COD_CLIENTE, 
                                                                COD_EMPRESA, 
                                                                COD_ENTIDAD, 
                                                                NOM_CLIENTE, 
                                                                DES_APELIDO, 
                                                                DES_CONTATO, 
                                                                DES_SENHAUS, 
                                                                LOG_USUARIO, 
                                                                DES_EMAILUS, 
                                                                DAT_CADASTR, 
                                                                COD_USUCADA, 
                                                                COD_ALTERAC, 
                                                                DAT_ALTERAC, 
                                                                COD_EXCLUSA, 
                                                                DAT_EXCLUSA, 
                                                                NUM_CGCECPF, 
                                                                TIP_CLIENTE, 
                                                                LOG_ESTATUS, 
                                                                LOG_TROCAPROD,
                                                                NUM_RGPESSO, 
                                                                DAT_NASCIME, 
                                                                COD_ESTACIV, 
                                                                COD_SEXOPES, 
                                                                NUM_TENTATI, 
                                                                NUM_TELEFON, 
                                                                NUM_CELULAR, 
                                                                NUM_COMERCI, 
                                                                COD_EXTERNO, 
                                                                NUM_CARTAO,
                                                                DES_ENDEREC, 
                                                                NUM_ENDEREC, 
                                                                DES_COMPLEM, 
                                                                DES_BAIRROC, 
                                                                NUM_CEPOZOF, 
                                                                NOM_CIDADEC, 
                                                                COD_ESTADOF, 
                                                                COD_PROFISS, 
                                                                COD_UNIVEND, 
                                                                COD_UNIVEND_PREF,
                                                                LOG_FIDELIDADE, 
                                                                LOG_EMAIL,
                                                                LOG_SMS, 
                                                                LOG_TELEMARK, 
                                                                LOG_WHATSAPP, 
                                                                LOG_PUSH, 
                                                                LOG_FIDELIZADO, 
                                                                DES_COMENT, 
                                                                NOM_PAI, 
                                                                NOM_MAE, 
                                                                IDADE,
                                                                DIA,
                                                                MES, 
                                                                ANO, 
                                                                LOG_AVULSO, 
                                                                COD_MAQUINA, 
                                                                COD_VENDEDOR, 
                                                                DAT_ULTCOMPR, 
                                                                COD_MULTEMP,
                                                                KEY_EXTERNO, 
                                                                COD_TPCLIENTE, 
                                                                COD_ATENDENTE,
                                                                DAT_PRICOMPR,
                                                                LOG_FUNCIONA, 
                                                                LOG_ATIVCAD,
                                                                LOG_CADOK,
                                                                COD_CATEGORIA,
                                                                COD_CATEGORIA_U, 
                                                                LAT,
                                                                LNG, 
                                                                COD_INDICAD, 
                                                                DAT_INDICAD, 
                                                                ID_ASSOCIADO,
                                                                COD_FREQUENCIA, 
                                                                VAL_FREQUENCIA, 
                                                                COD_FREQUENCIA_U, 
                                                                VAL_FREQUENCIA_U, 
                                                                LOG_CADTOTEM,
                                                                COD_CADPESQ,
                                                                COD_UNIVEND_ANT, 
                                                                LOG_OFERTAS,
                                                                DES_TOKEN,
                                                                LOG_TERMO FROM clientes 
                                    WHERE cod_empresa=$cod_empresa  and
                                                 case when num_cgcecpf='$cpf' then  1
                                                      when num_cartao='$cpf' then  2
                                             ELSE 0 END  IN (1,2);";
                mysqli_query($conntmp, $insqllog);

                //ALTERAR AS QUANTIDADES PARA EFETUAR O BLIOQUIO
                $UPIN = "INSERT INTO CONTROLE_ALTERAC_CLI (NUM_CGCECPF, COD_EMPRESA, DAT_ALETRAC) 
                VALUES ($cpf, $cod_empresa, NOW())
                ON DUPLICATE KEY UPDATE QTD_ALTERAC = QTD_ALTERAC + 1;";
                mysqli_query($conntmp, $UPIN);
            }
        }
    }
    //  return [$insqllog];
    //  print_r($arrayWs);
}
function fnplacamercosul($placa)
{
    if (preg_match('/^[A-Z]{3}[0-9][A-Z][0-9]{2}$/', str_replace('-', '', $placa))) {
        // Remove o traço da placa
        $placa1 = str_replace('-', '', $placa);
    } else {
        $placa1 = $placa;
    }
    return $placa1;
}
function calcularIdade($dataNascimento)
{
    // Converter a data de nascimento para o formato DateTime
    $dataNascimento = DateTime::createFromFormat('d/m/Y', $dataNascimento);
    $dataAtual = new DateTime();
    $idade = $dataAtual->diff($dataNascimento)->y;
    return $idade;
}

function validarIdade($dataNascimento, $idademin = 0)
{
    $idade = calcularIdade($dataNascimento);
    return $idade >= $idademin;
}
