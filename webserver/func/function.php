<?php
//ini_set('output_buffering',4092);
//ini_set('post_max_size', '512M');
//ini_set('max_execution_time', '30');
date_default_timezone_set('America/Sao_Paulo');
//ini_set('default_charset','UTF-8');
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


function fnAcentos($string)
{
    // matriz de entrada
    $what = array('ä', 'ã', 'à', 'á', 'â', 'ê', 'ë', 'è', 'é', 'ï', 'ì', 'í', 'ö', 'õ', 'ò', 'ó', 'ô', 'ü', 'ù', 'ú', 'û', 'À', 'Á', 'É', 'Í', 'Ó', 'Ú', 'ñ', 'Ñ', 'ç', 'Ç');

    // matriz de saída
    $by   = array('a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'A', 'A', 'E', 'I', 'O', 'U', 'n', 'n', 'c', 'C');

    // devolver a string
    return str_replace($what, $by, $string);
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
function fnValor($Num, $Dec)
{

    $valor = str_replace(".", "", $Num);
    $valor = str_replace(",", ".", $valor);
    $valor = number_format($valor, $Dec, ",", ".");
    //echo $valor; //retorna o valor formatado para apresentação em tela  
    return $valor;
}
function fnformatavalorretorno($Num, $dec)
{
    if (empty($Num) || is_null($Num)) {
        $Numero = 000;
    } else {
        $Numero = $Num;
    }

    $valor = bcmul($Numero, '100', $dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
    $valor = bcdiv($valor, '100', $dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
    $valor = number_format($valor, $dec, ",", ".");
    return  $valor;
}
/*
  $valor = str_replace(",", ".", $Numero); 
    $valor = bcmul($valor, '100', $dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
    $valor = bcdiv($valor, '100', $dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
   $valor=number_format ($valor,$dec,",",".");
 */
function fnValornovo($Num, $Dec)
{
    if (empty($Num) || is_null($Num)) {
        $Numero = 0;
    } else {
        $Numero = $Num;
    }

    $source = array(',', '.');
    $replace = array('', ',');
    $valor = str_replace($source, $replace, $Numero); //remove os pontos e substitui a virgula pelo ponto

    return $valor; //retorna o valor formatado para gravar no banco

}
/*
function fnFormatvalor($Num)
{
  if (empty($Num) || is_null($Num) ) {$Numero = 0;} else {$Numero = $Num;}		
  $valor = str_replace(".", "", $Numero);
  $valor = str_replace(",", ".", $Numero); 
  $valor=number_format ($valor,4,".",".");
  return $valor; //retorna o valor formatado para gravar no banco 
}  
 * 
 */
function fnFormatvalor($Num, $dec, $cod_empresa = "")
{
    if ($cod_empresa == '124') {
        $str = preg_replace("/[^0-9]/", ".", $Num);
        $n = explode(".", $str);
        if (count($n) <= 1) {
            $n[1] = "00";
        }
        $dec = array_pop($n);
        $num = implode("", $n);
        return $num . "." . $dec;
    } else {
        $Num = rtrim(trim($Num));
        $valor = str_replace(".", "", $Num);
        $valor = str_replace(",", ".", $valor);
        $valor = bcmul($valor, '100', $dec); //Multiplicação - Parâmetros[valor, multiplicador, casas decimais] 
        $valor = bcdiv($valor, '100', $dec); //Divisão - Parâmetros[valor, divisor, casas decimais] echo $valor; //Exibe "100.19" (String)
        //$valor=number_format ($valor,2,".","");
        return $valor; //retorna o valor formatado para gravar no banco 
    }
}
function fnlimpaCPF($valor)
{
    $valor = rtrim(trim($valor));
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}
function fnlimpatel($valor)
{
    $valor = trim($valor);
    $valor = str_replace("(", "", $valor);
    $valor = str_replace(")", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace(" ", "", $valor);
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

function fn_calValor($arrayiten, $dec)
{

    if (count($arrayiten['items']['vendaitem']['codigoproduto']) == 1) {
        $vltotal = fnFormatvalor($arrayiten['valortotal'], $dec);
        $quantidade = fnFormatvalor($arrayiten['items']['vendaitem']['quantidade'], $dec);
        $valor = fnFormatvalor($arrayiten['items']['vendaitem']['valor'], $dec);
        $vl = $valor * $quantidade;

        $diferenca = $vltotal - fnFormatvalor($vl, $dec);
        $diferenca = str_replace("-", " ", $diferenca);

        if ((fnFormatvalor($diferenca, $dec) <= '0.99')) {
            $retorno = 1;
            return $retorno;
        } else {

            $retorno = 0;
            return $retorno;
        }
    } else {
        $vltotal = fnFormatvalor($arrayiten['valortotal'], $dec);
        for ($i = 0; $i <= count($arrayiten['items']['vendaitem']) - 1; $i++) {
            $quantidade = fnFormatvalor($arrayiten['items']['vendaitem'][$i]['quantidade'], $dec);
            $valor = fnFormatvalor($arrayiten['items']['vendaitem'][$i]['valor'], $dec);
            $result = $valor * $quantidade;
            $vl += $result;
        }
        $diferenca = $vltotal - $vl;
        $diferenca = str_replace("-", " ", $diferenca);

        if ((fnFormatvalor($diferenca, $dec) <= '0.99')) {
            $retorno = 1;

            return $retorno;
        } else {
            $retorno = 0;
            return $retorno;
        }
    }
}
function timeincial()
{

    list($usec, $sec) = explode(' ', microtime());
    $script_start = (float) $sec + (float) $usec;
    return $script_start;
}
function timefinal($script_start, $conn, $nome_scriptsql, $comand_sql, $empresa, $ativo)
{
    list($usec, $sec) = explode(' ', microtime());
    $script_end = (float) $sec + (float) $usec;
    $elapsed_time = round($script_end - $script_start, 5);
    $elapsed_time1 = 'Elapsed time: ' . $elapsed_time . ' secs. Memory usage: ' . round(((memory_get_peak_usage(true) / 1024) / 1024), 2) . 'Mb';
    if ($ativo == 'S') {
        $insert = 'INSERT INTO log_rotinas (nome_scriptsql, 
                                          comand_sql,  
                                          tempo_execucao, 
                                          memoria_uso, 
                                           cod_empresa) VALUES 
                                          ("' . $nome_scriptsql . '", 
                                           "' . $comand_sql . '", 
                                           "Elapsed time:' . $elapsed_time . 'secs", 
                                           "Memory usage: ' . round(((memory_get_peak_usage(true) / 1024) / 1024), 2) . ' Mb", 
                                           ' . $empresa . ');';
        mysqli_query($conn, $insert);
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
        } elseif ($mem_usage < 1048576) {

            $logqueryinsert = 'insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario,EMPRESA) values ("' . round($mem_usage / 1024, 2) . " kilobytes" . '","' . $pagina . '","' . $datahora . '","' . $user . '","' . $empresa . '");';
            mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
        } else {
            $logqueryinsert = 'insert into log_men (MEN_INICIAL,PAGINA,DATA_HORA,Usuario,EMPRESA) values ("' . round($mem_usage / 1048576, 2) . " megabytes" . '","' . $pagina . '","' . $datahora . '","' . $user . '","' . $empresa . '");';
            mysqli_query($conn, $logqueryinsert) or die(mysqli_error());
        }
        $COD_log = mysqli_insert_id($conn);
        return $COD_log;
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
function fncompletadoc($cpfcnpj)
{
    $tipo = strtoupper($tipo);

    $retun = str_pad($cpfcnpj, 11, '0', STR_PAD_LEFT); // Resultado: 00009   
    return $retun;
}
//===========================
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
function FnQualidade_cad($arrayconn, $cod_empresa, $clientes, $cod_log, $dadosLogin, $cod_loja)
{
    $clientes = array_map('trim', $clientes);
    Grava_log_cad($arrayconn['connuser'], $cod_log, 'Inicio da qualidade de cadastro!');
    $cod = 0;
    $CAMPOSSQL = "select controle_campos.COD_CAMPOOBG,INTEGRA_CAMPOOBG.KEY_CAMPOOBG,INTEGRA_CAMPOOBG.DES_CAMPOOBG from controle_campos 
                  inner join INTEGRA_CAMPOOBG on INTEGRA_CAMPOOBG.COD_CAMPOOBG=controle_campos.COD_CAMPOOBG  
                  where cod_empresa=$cod_empresa";
    $CAMPOQUERY = mysqli_query($arrayconn['connadm'], $CAMPOSSQL);
    //$ARRAYCAMPOQUERY=mysqli_fetch_all($CAMPOQUERY, MYSQLI_ASSOC);
    while ($ARRAYCAMPOQUERY = mysqli_fetch_assoc($CAMPOQUERY)) {
        $KEY_CAMPOOBG[] = array(
            'KEY_CAMPOOBG' => $ARRAYCAMPOQUERY['KEY_CAMPOOBG'],
            'COD_CAMPOOBG' => $ARRAYCAMPOQUERY['COD_CAMPOOBG']
        );
        $KEY_CAMPONOME[] = array('KEY_CAMPOOBG' => $ARRAYCAMPOQUERY['KEY_CAMPOOBG']);
        $COD_CAMPOOBG[] = array('COD_CAMPOOBG' => $ARRAYCAMPOQUERY['COD_CAMPOOBG']);
        $log_base .= $ARRAYCAMPOQUERY['KEY_CAMPOOBG'] . ',';
    }
    $log_base = substr($log_base, 0, -1);
    Grava_log_cad($arrayconn['connuser'], $cod_log, "carregou campos obrigatorios! $log_base" . addslashes($KEY_CAMPONOME));

    $sel = "select controle_campos.cod_campoobg,INTEGRA_CAMPOOBG.key_campoobg,INTEGRA_CAMPOOBG.DES_CAMPOOBG from controle_campos 
                  inner join INTEGRA_CAMPOOBG on INTEGRA_CAMPOOBG.COD_CAMPOOBG=controle_campos.COD_CAMPOOBG  
                  where cod_empresa=$cod_empresa ";
    $selret = mysqli_query($arrayconn['connadm'], $sel);
    while ($campoOBG = mysqli_fetch_assoc($selret)) {
        Grava_log_cad($arrayconn['connuser'], $cod_log, 'verificando controle_campos!');

        $NOM_CAMPO[] = array('NOM_CAMPO' => $campoOBG['key_campoobg']);
        $arrayret = recursive_array_search($campoOBG['cod_campoobg'], array_filter($COD_CAMPOOBG));

        if (recursive_array_search($campoOBG['cod_campoobg'], array_filter($COD_CAMPOOBG)) !== false) {
            //só passar os campos que eu preciso checar aqui da ws.
            if (recursive_array_search($KEY_CAMPOOBG[$arrayret]['KEY_CAMPOOBG'], array_filter($NOM_CAMPO)) !== false) {
                foreach ($KEY_CAMPONOME[$arrayret] as $key) {
                    $campolimpo = str_replace('-', '', $clientes[$key]);
                    $campolimpo = ltrim(rtrim(trim($campolimpo)));
                    if ($campolimpo != "" || $campolimpo != '') {

                        if ($key != "") {
                            // $DES_CAMPOOBG[]=array('COD_CAMPOOBG'=>$campoOBG['DES_CAMPOOBG']); 
                            if (recursive_array_search($campoOBG['cod_campoobg'], array_filter($DES_CAMPOOBG)) !== false) {
                            }

                            $verificadados = "select * from historico_atualizacao 
                                                                            where cod_empresa=$cod_empresa 
                                                                             and cod_atualizado>=1
                                                                             and NUM_CGCECPF='" . fnlimpaCPF($clientes['cpf']) . "'
                                                                             and DADOS_ATUALIZADOS='" . $campolimpo . "';";
                            $rsverifica = mysqli_query($arrayconn['connuser'], $verificadados);
                            $dadosverifica = mysqli_fetch_assoc($rsverifica);

                            if ($dadosverifica['DADOS_ATUALIZADOS'] != $campolimpo) {
                                $selupdate = "select * from historico_atualizacao where cod_empresa=$cod_empresa 
                                                                                        and CAMPOS_ATUALIZ= '$key'
                                                                                        and NUM_CGCECPF='" . fnlimpaCPF($clientes['cpf']) . "'    
                                                                                        and COD_ATUALIZADO>=1";
                                $rsupdate = mysqli_query($arrayconn['connuser'], $selupdate);
                                $dadosupdate = mysqli_fetch_assoc($rsupdate);
                                if (!empty($dadosupdate)) {
                                    $update = "UPDATE historico_atualizacao SET COD_ATUALIZADO='0' WHERE  COD_ATUALIZ='" . $dadosupdate['COD_ATUALIZ'] . "'  and NUM_CGCECPF='" . fnlimpaCPF($clientes['cpf']) . "' and cod_empresa=$cod_empresa;";
                                    mysqli_query($arrayconn['connuser'], $update);
                                    Grava_log_cad($arrayconn['connuser'], $cod_log, 'Registro atualizado');
                                }
                                //verificar se o cliente ja tem dados.
                                $DES_CAMPOOBG1 = $campoOBG['DES_CAMPOOBG'];
                                $verifica = "select " . $DES_CAMPOOBG1 . " from clientes where cod_empresa=$cod_empresa and num_cgcecpf='" . fnlimpaCPF($clientes['cpf']) . "'";
                                $rowverifica = mysqli_query($arrayconn['connuser'], $verifica);
                                $returndados = mysqli_fetch_assoc($rowverifica);
                                if ($returndados[$DES_CAMPOOBG1] == '') {
                                    $idatualiza = 2;
                                } else {
                                    $idatualiza = 1;
                                }
                                //inserir dados de atualização
                                @$insert = "INSERT INTO historico_atualizacao (CAMPOS_ATUALIZ, 
                                                                                            COD_EMPRESA, 
                                                                                            NUM_CGCECPF, 
                                                                                            DADOS_ATUALIZADOS, 
                                                                                            ATENDENTE, 
                                                                                            VENDEDOR,
                                                                                            cod_univend,
                                                                                            COD_ATUALIZADO) 
                                                                                            VALUES 
                                                                                            ('" . $KEY_CAMPONOME[$arrayret]['KEY_CAMPOOBG'] . "', 
                                                                                             '$cod_empresa', 
                                                                                             '" . fnlimpaCPF($clientes['cpf']) . "', 
                                                                                             '" . $campolimpo . "', 
                                                                                             '" . $clientes['codatendente'] . "', 
                                                                                             '" . $dadosLogin['codvendedor'] . "',
                                                                                             '" . $cod_loja . "',
                                                                                             $idatualiza );";
                                $clear = mysqli_query($arrayconn['connuser'], $insert);
                                Grava_log_cad($arrayconn['connuser'], $cod_log, 'Registro Inserido!');
                            }
                        }
                    }
                }
            }
        }
    }


    // return $update;
}

//============================= 
function fn_consultaBase($conn, $CPF, $CNPJ, $cartao, $email, $telcelular, $empresa)
{
    if ($CPF != "") {
        if ($cartao != '') {
            $andcartao = 'or NUM_CARTAO=' . $cartao;
        }
        $sql = "SELECT count(COD_CLIENTE) as contador,clientes.* FROM clientes where COD_EMPRESA=$empresa and (NUM_CGCECPF=" . $CPF . " $andcartao" . ")";
        $rsCPF = mysqli_query($conn, $sql);
        $row1 = mysqli_fetch_assoc($rsCPF);


        // mysqli_free_result($rsCPF);
        $arraydadosBase = array();
        array_push($arraydadosBase, array(
            'contador' => $row1['contador'],
            'COD_CLIENTE' => $row1['COD_CLIENTE'],
            'cartao' => $row1['NUM_CARTAO'],
            'tipocliente' => $row1['TIP_CLIENTE'],
            'nome' => $row1['NOM_CLIENTE'],
            'cpf' => $row1['NUM_CGCECPF'],
            'cnpj' => $row1['NUM_CGCECPF'],
            'rg' => $row1['NUM_RGPESSO'],
            'sexo' => $row1['COD_SEXOPES'],
            'datanascimento' => $row1['DAT_NASCIME'],
            'estadocivil' => $row1['COD_ESTACIV'],
            'email' => $row1['DES_EMAILUS'],
            'dataalteracao' => $row1['DAT_ALTERAC'],
            'cartaotitular' => $row1['NUM_CARTAO'],
            'nomeportador' => $row1['NOM_CLIENTE'],
            'grupo' => '',
            'profissao' => $row1['COD_PROFISS'],
            'clientedesde' => $row1['DAT_CADASTR'],
            'endereco' => $row1['DES_ENDEREC'],
            'numero' => $row1['NUM_ENDEREC'],
            'complemento' => $row1['DES_COMPLEM'],
            'bairro' => $row1['DES_BAIRROC'],
            'cidade' => $row1['NOM_CIDADEC'],
            'estado' => $row1['COD_ESTADOF'],
            'cep' => $row1['NUM_CEPOZOF'],
            'telresidencial' => $row1['NUM_TELEFON'],
            'telcelular' => $row1['NUM_CELULAR'],
            'telcomercial' => '',
            'senha' => $row1['DES_SENHAUS'],
            'codatendente' => $row1['COD_ATENDENTE'],
            'codunivend' => $row1['COD_UNIVEND'],
            'saldo' => '',
            'saldoresgate' => '',
            'msgerro' => '',
            'msgcampanha' => '',
            'url' => '',
            'ativacampanha' => '',
            'dadosextras' => '',
            'funcionario' => $row1['LOG_FUNCIONA'],
            'LOG_ESTATUS' => $row1['LOG_ESTATUS']

        ));
        return $arraydadosBase;
    }
    if ($CNPJ != '') {
        $sql = "SELECT count(COD_CLIENTE) as contador,clientes.* FROM clientes where COD_EMPRESA=$empresa and NUM_CGCECPF='" . $CNPJ . "'";
        $rsCNPJ = mysqli_query($conn, $sql);
        $row1 = mysqli_fetch_assoc($rsCNPJ);
        $arraydadosBase = array();
        array_push($arraydadosBase, array(
            'contador' => $row1['contador'],
            'COD_CLIENTE' => $row1['COD_CLIENTE'],
            'cartao' => $row1['NUM_CARTAO'],
            'tipocliente' => $row1['TIP_CLIENTE'],
            'nome' => $row1['NOM_CLIENTE'],
            'cpf' => $row1['NUM_CGCECPF'],
            'cnpj' => $row1['NUM_CGCECPF'],
            'rg' => $row1['NUM_RGPESSO'],
            'sexo' => $row1['COD_SEXOPES'],
            'datanascimento' => $row1['DAT_NASCIME'],
            'estadocivil' => $row1['COD_ESTACIV'],
            'email' => $row1['DES_EMAILUS'],
            'dataalteracao' => $row1['DAT_ALTERAC'],
            'cartaotitular' => $row1['NUM_CARTAO'],
            'nomeportador' => $row1['NOM_CLIENTE'],
            'grupo' => '',
            'profissao' => $row1['COD_PROFISS'],
            'clientedesde' => $row1['DAT_CADASTR'],
            'endereco' => $row1['DES_ENDEREC'],
            'numero' => $row1['NUM_ENDEREC'],
            'complemento' => $row1['DES_COMPLEM'],
            'bairro' => $row1['DES_BAIRROC'],
            'cidade' => $row1['NOM_CIDADEC'],
            'estado' => $row1['COD_ESTADOF'],
            'cep' => $row1['NUM_CEPOZOF'],
            'telresidencial' => $row1['NUM_TELEFON'],
            'telcelular' => $row1['NUM_CELULAR'],
            'telcomercial' => '',
            'saldo' => '',
            'saldoresgate' => '',
            'msgerro' => '',
            'msgcampanha' => '',
            'url' => '',
            'ativacampanha' => '',
            'dadosextras' => '',
            'funcionario' => $row1['LOG_FUNCIONA'],
            'LOG_ESTATUS' => $row1['LOG_ESTATUS']

        ));
        return $arraydadosBase;
    }
    if ($cartao != '') {
        if ($CPF != '') {
            $cpf = 'or NUM_CGCECPF=' . $CPF;
        } else {
            $CPF = '';
        }
        $sql = "SELECT count(COD_CLIENTE) as contador,clientes.*  FROM clientes where COD_EMPRESA=$empresa and (NUM_CARTAO='" . $cartao . "' $cpf" . ")";
        $rscartao = mysqli_query($conn, $sql);
        $row1 = mysqli_fetch_assoc($rscartao);

        $arraydadosBase = array();
        array_push($arraydadosBase, array(
            'contador' => $row1['contador'],
            'COD_CLIENTE' => $row1['COD_CLIENTE'],
            'cartao' => $row1['NUM_CARTAO'],
            'tipocliente' => $row1['TIP_CLIENTE'],
            'nome' => $row1['NOM_CLIENTE'],
            'cpf' => $row1['NUM_CGCECPF'],
            'cnpj' => $row1['NUM_CGCECPF'],
            'rg' => $row1['NUM_RGPESSO'],
            'sexo' => $row1['COD_SEXOPES'],
            'datanascimento' => $row1['DAT_NASCIME'],
            'estadocivil' => $row1['COD_ESTACIV'],
            'email' => $row1['DES_EMAILUS'],
            'dataalteracao' => $row1['DAT_ALTERAC'],
            'cartaotitular' => $row1['NUM_CARTAO'],
            'nomeportador' => $row1['NOM_CLIENTE'],
            'grupo' => '',
            'profissao' => $row1['COD_PROFISS'],
            'clientedesde' => $row1['DAT_CADASTR'],
            'endereco' => $row1['DES_ENDEREC'],
            'numero' => $row1['NUM_ENDEREC'],
            'complemento' => $row1['DES_COMPLEM'],
            'bairro' => $row1['DES_BAIRROC'],
            'cidade' => $row1['NOM_CIDADEC'],
            'estado' => $row1['COD_ESTADOF'],
            'cep' => $row1['NUM_CEPOZOF'],
            'telresidencial' => $row1['NUM_TELEFON'],
            'telcelular' => $row1['NUM_CELULAR'],
            'telcomercial' => '',
            'saldo' => '',
            'saldoresgate' => '',
            'msgerro' => '',
            'msgcampanha' => '',
            'url' => '',
            'ativacampanha' => '',
            'dadosextras' => '',
            'funcionario' => $row1['LOG_FUNCIONA'],
            'LOG_ESTATUS' => $row1['LOG_ESTATUS']

        ));
        return $arraydadosBase;
    }
    if ($email != '') {
        $sql = "SELECT * FROM clientes where COD_EMPRESA=$empresa and DES_EMAILUS='" . $email . "'";
        $row1 = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        $arraydadosBase = array();
        array_push($arraydadosBase, array(

            'COD_CLIENTE' => $row1['COD_CLIENTE'],
            'cartao' => $row1['NUM_CARTAO'],
            'tipocliente' => $row1['TIP_CLIENTE'],
            'nome' => $row1['NOM_CLIENTE'],
            'cpf' => $row1['NUM_CGCECPF'],
            'cnpj' => $row1['NUM_CGCECPF'],
            'rg' => $row1['NUM_RGPESSO'],
            'sexo' => $row1['COD_SEXOPES'],
            'datanascimento' => $row1['DAT_NASCIME'],
            'estadocivil' => $row1['COD_ESTACIV'],
            'email' => $row1['DES_EMAILUS'],
            'dataalteracao' => $row1['DAT_ALTERAC'],
            'cartaotitular' => $row1['NUM_CARTAO'],
            'nomeportador' => $row1['NOM_CLIENTE'],
            'grupo' => '',
            'profissao' => $row1['COD_PROFISS'],
            'clientedesde' => $row1['DAT_CADASTR'],
            'endereco' => $row1['DES_ENDEREC'],
            'numero' => $row1['NUM_ENDEREC'],
            'complemento' => $row1['DES_COMPLEM'],
            'bairro' => $row1['DES_BAIRROC'],
            'cidade' => $row1['NOM_CIDADEC'],
            'estado' => $row1['COD_ESTADOF'],
            'cep' => $row1['NUM_CEPOZOF'],
            'telresidencial' => $row1['NUM_TELEFON'],
            'telcelular' => $row1['NUM_CELULAR'],
            'telcomercial' => '',
            'saldo' => '',
            'saldoresgate' => '',
            'msgerro' => '',
            'msgcampanha' => '',
            'url' => '',
            'ativacampanha' => '',
            'dadosextras' => '',
            'funcionario' => $row1['LOG_FUNCIONA'],
            'LOG_ESTATUS' => $row1['LOG_ESTATUS']

        ));
        return $arraydadosBase;
    }
    if ($telcelular != '') {
        $sql = "SELECT * FROM clientes where COD_EMPRESA=$empresa and NUM_CELULAR='" . $telcelular . "'";
        $row1 = mysqli_fetch_assoc(mysqli_query($conn, $sql));
        $arraydadosBase = array();
        array_push($arraydadosBase, array(

            'COD_CLIENTE' => $row1['COD_CLIENTE'],
            'cartao' => $row1['NUM_CARTAO'],
            'tipocliente' => $row1['TIP_CLIENTE'],
            'nome' => $row1['NOM_CLIENTE'],
            'cpf' => $row1['NUM_CGCECPF'],
            'cnpj' => $row1['NUM_CGCECPF'],
            'rg' => $row1['NUM_RGPESSO'],
            'sexo' => $row1['COD_SEXOPES'],
            'datanascimento' => $row1['DAT_NASCIME'],
            'estadocivil' => $row1['COD_ESTACIV'],
            'email' => $row1['DES_EMAILUS'],
            'dataalteracao' => $row1['DAT_ALTERAC'],
            'cartaotitular' => $row1['NUM_CARTAO'],
            'nomeportador' => $row1['NOM_CLIENTE'],
            'grupo' => '',
            'profissao' => $row1['COD_PROFISS'],
            'clientedesde' => $row1['DAT_CADASTR'],
            'endereco' => $row1['DES_ENDEREC'],
            'numero' => $row1['NUM_ENDEREC'],
            'complemento' => $row1['DES_COMPLEM'],
            'bairro' => $row1['DES_BAIRROC'],
            'cidade' => $row1['NOM_CIDADEC'],
            'estado' => $row1['COD_ESTADOF'],
            'cep' => $row1['NUM_CEPOZOF'],
            'telresidencial' => $row1['NUM_TELEFON'],
            'telcelular' => $row1['NUM_CELULAR'],
            'telcomercial' => '',
            'saldo' => '',
            'saldoresgate' => '',
            'msgerro' => '',
            'msgcampanha' => '',
            'url' => '',
            'ativacampanha' => '',
            'dadosextras' => '',
            'funcionario' => $row1['LOG_FUNCIONA']

        ));
        return $arraydadosBase;
    }
}

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
function fnDataSql($str)
{

    $data = str_replace("/", "-", $str);
    $strcount = date('Y-m-d', strtotime($data));
    return $strcount;
}
function fndate($str)
{
    $strcount = date('Y-m-d', strtotime($str));
    return $strcount;
}
function fnDataBR($str)
{

    $data = str_replace("-", "/", $str);
    $strcount = date('d/m/Y', strtotime($data));
    return $strcount;
}
function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
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



function Grava_log($conn, $id_log, $MSG, $xmlretorno = null)
{

    $msg1 = 'INSERT INTO msg_venda (ID,DATA_HORA,MSG,origem_retorno)values
                   (' . $id_log . ',"' . date("Y-m-d H:i:s") . '","' . $MSG . '","' . $xmlretorno . '")';

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try {
        mysqli_query($conn, $msg1);
    } catch (mysqli_sql_exception $e) {
        // echo $e;
        // return $e;    

    }
}
function Grava_log_consulta($conn, $id_log, $MSG)
{
    $msg1 = 'INSERT INTO msg_busca (ID,DATA_HORA,MSG)values
                   (' . $id_log . ',"' . date("Y-m-d H:i:s") . '","' . $MSG . '")';
    mysqli_query($conn, $msg1);
}
function Grava_log_Produto($conn, $id_log, $MSG)
{
    $msg1 = 'INSERT INTO msg_produto (ID,DATA_HORA,MSG)values
                   (' . $id_log . ',"' . date("Y-m-d H:i:s") . '","' . $MSG . '");';
    mysqli_query($conn, $msg1);
}
function Grava_log_cad($conn, $id_log, $MSG)
{
    $msg1 = 'INSERT INTO msg_cadastra (ID,DATA_HORA,MSG)values
                   (' . $id_log . ',"' . date("Y-m-d H:i:s") . '","' . $MSG . '")';
    mysqli_query($conn, $msg1);
}
function fngravaxmlatualiza($arraydados)
{
    $inserarray = 'INSERT INTO origemcadastro (DAT_CADASTR,
                                                    IP,
                                                    PORTA,
                                                    COD_USUARIO,
                                                    NOM_USUARIO,
                                                    COD_EMPRESA,
                                                    COD_UNIVEND,
                                                    ID_MAQUINA,
                                                    NUM_CGCECPF,
                                                    DES_VENDA)
                                                    values
                                                   ("' . $arraydados['DATA_HORA'] . '",
                                                    "' . $arraydados['IP'] . '",
                                                    "' . $arraydados['PORT'] . '",
                                                    "' . $arraydados['COD_USUARIO'] . '",
                                                    "' . $arraydados['LOGIN'] . '",
                                                    "' . $arraydados['COD_EMPRESA'] . '",
                                                    "' . $arraydados['IDLOJA'] . '",
                                                    "' . $arraydados['IDMAQUINA'] . '",
                                                    "' . $arraydados['CPF'] . '",
                                                    "' . addslashes($arraydados['XML']) . '"    
                                                   )';
    mysqli_query($arraydados['CONN'], $inserarray);
    //Pegar o id da venda para inserir as messagens no log
    $COD_log = mysqli_insert_id($arraydados['CONN']);
    //$COD_log='diogo';

    return  $COD_log;
}
function fngravaxmlbusca($arraydados)
{
    $inserarray = 'INSERT INTO origembusca (DAT_CADASTR,
                                                    IP,
                                                    PORTA,
                                                    COD_USUARIO,
                                                    NOM_USUARIO,
                                                    COD_EMPRESA,
                                                    COD_UNIVEND,
                                                    ID_MAQUINA,
                                                    NUM_CGCECPF,
                                                    DES_VENDA,
                                                    URL)
                                                    values
                                                   ("' . $arraydados['DATA_HORA'] . '",
                                                    "' . $arraydados['IP'] . '",
                                                    "' . $arraydados['PORT'] . '",
                                                    "' . $arraydados['COD_USUARIO'] . '",
                                                    "' . $arraydados['LOGIN'] . '",
                                                    "' . $arraydados['COD_EMPRESA'] . '",
                                                    "' . $arraydados['IDLOJA'] . '",
                                                    "' . $arraydados['IDMAQUINA'] . '",
                                                    "' . $arraydados['CPF'] . '",
                                                    "' . addslashes($arraydados['XML']) . '" ,
                                                    "' . addslashes($arraydados['URL']) . '"   
                                                   )';
    mysqli_query($arraydados['CONN'], $inserarray);
    //Pegar o id da venda para inserir as messagens no log
    $COD_log = mysqli_insert_id($arraydados['CONN']);
    //$COD_log='diogo';

    return  $COD_log;
}
//xml venda
function fngravaxmlvendas($arraydados)
{

    $inserarray = "INSERT INTO ORIGEMVENDA (DAT_CADASTR,
                                         IP,
                                         PORTA,
                                         COD_USUARIO,
                                         NOM_USUARIO,
                                         COD_EMPRESA,
                                         COD_UNIVEND,
                                         ID_MAQUINA,
                                         COD_PDV,
                                         NUM_CGCECPF,
                                         DES_VENDA,
                                         CUPOM)
                                        values
                                       ('" . $arraydados['DATA_HORA'] . "',
                                        '" . $arraydados['IP'] . "',
                                        '" . $arraydados['PORT'] . "',
                                        '" . $arraydados['COD_USUARIO'] . "',
                                        '" . $arraydados['LOGIN'] . "',
                                        '" . $arraydados['COD_EMPRESA'] . "',
                                        '" . $arraydados['IDLOJA'] . "',
                                        '" . $arraydados['IDMAQUINA'] . "',
                                        '" . $arraydados['PDV'] . "',    
                                        '" . $arraydados['CPF'] . "',
                                        '" . addslashes($arraydados['XML']) . "',
                                        '" . $arraydados['cupom'] . "'    
                                       )";
    mysqli_query($arraydados['CONN'], $inserarray);
    //Pegar o id da venda para inserir as messagens no log
    $COD_log = mysqli_insert_id($arraydados['CONN']);
    //$COD_log='diogo';
    return  $COD_log;
}
//==================================================================================
//inserir venda inteira na base de dados 
function fngravaxml($arraydados)
{
    $inserarray = 'INSERT INTO origembuscafidelizados (DAT_CADASTR,
                                                    IP,
                                                    PORTA,
                                                    COD_USUARIO,
                                                    NOM_USUARIO,
                                                    COD_EMPRESA,
                                                    COD_UNIVEND,
                                                    ID_MAQUINA,
                                                    NUM_CGCECPF,
                                                    DES_VENDA)
                                                    values
                                                   ("' . $arraydados['DATA_HORA'] . '",
                                                    "' . $arraydados['IP'] . '",
                                                    "' . $arraydados['PORT'] . '",
                                                    "' . $arraydados['COD_USUARIO'] . '",
                                                    "' . $arraydados['LOGIN'] . '",
                                                    "' . $arraydados['COD_EMPRESA'] . '",
                                                    "' . $arraydados['IDLOJA'] . '",
                                                    "' . $arraydados['IDMAQUINA'] . '",
                                                    "' . $arraydados['CPF'] . '",
                                                    "' . addslashes($arraydados['XML']) . '"    
                                                   )';
    mysqli_query($arraydados['CONN'], $inserarray);
    //Pegar o id da venda para inserir as messagens no log
    $COD_log = mysqli_insert_id($arraydados['CONN']);
    //$COD_log='diogo';

    return  $COD_log;
}
function Grava_log_fidelizados($conn, $id_log, $MSG)
{
    $msg1 = 'INSERT INTO msg_origembuscafidelizados (ID,DATA_HORA,MSG)values
                   (' . $id_log . ',"' . date("Y-m-d H:i:s") . '","' . $MSG . '")';
    mysqli_query($conn, $msg1);
}

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
        /*
        $regex = "/([A-Z]{1}[A-Z]+\s{0,2}$)+/";
        if (preg_match($regex, $campo)) {}
        else {
            $msg = 'O texto no campo  '.$nomecampo.' está menor que o esperado, por favor digitar o nome completo!';
            return $msg; 
        }
        */
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
// FUTURAMENTE VERIFICAR PERFIL DA LOJA COM O USUARIO.
function fnconsultaLoja($CONN1, $CONN2, $ID_LOJA, $ID_MAQUINA, $COD_EMPRESA)
{
    if ($ID_LOJA == '725' && $COD_EMPRESA == '42') {
        $ID_LOJA = '1000';
    } else {
        $ID_LOJA = $ID_LOJA;
    }
    //unidade de venda tem que existir
    $sql = "select count(COD_UNIVEND) as existe, COD_UNIVEND  from unidadevenda where COD_EMPRESA=$COD_EMPRESA AND COD_UNIVEND='" . $ID_LOJA . "'";
    $retIDLOJA = mysqli_fetch_assoc(mysqli_query($CONN1, $sql));
    if ($retIDLOJA['existe'] != 0) {
        $MSG = '1';
        //PROCURA POR MAQUINA 
        $sqlMAQUINA = "select count(*) as DES_MAQUINA, maquinas.COD_MAQUINA from maquinas where COD_EMPRESA=$COD_EMPRESA AND DES_MAQUINA='" . $ID_MAQUINA . "'";
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
            $id_return = mysqli_fetch_assoc(mysqli_query($CONN2, $ID_MAQUINA));
            $idmaquina = $id_return['COD_MAQUINA'];
        } else {
            //codigo de inserção
            $idmaquina = $retIDMAQUINA['COD_MAQUINA'];
        }
    }



    $arraydadosBase = array();
    array_push(
        $arraydadosBase,
        array(

            'msg' => $MSG,
            'COD_MAQUINA' => $idmaquina,
            'COD_UNIVEND' => $retIDLOJA['COD_UNIVEND']
        )
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
function fnVendedor($conn, $NOM_USUARIO, $COD_MULTEMP, $COD_UNIVEND, $COD_EXTERNO)
{

    if ($NOM_USUARIO != '') {
        $nome_user = " or NOM_USUARIO='$NOM_USUARIO'";
    } else {
        $nome_user = '';
    }

    $sqlbusca = "select count(*) as exist,COD_USUARIO,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST 
              from usuarios 
                        where 
                              cod_empresa=$COD_MULTEMP AND
			      FIND_IN_SET('" . $COD_UNIVEND . "',COD_UNIVEND) and 
                              (COD_MULTEMP='$COD_MULTEMP' and  COD_EXTERNO='$COD_EXTERNO')";


    $result = mysqli_fetch_assoc(mysqli_query($conn, $sqlbusca));

    if ($result['exist'] == 0) {

        $sql = 'insert into usuarios (dat_cadastr,COD_EMPRESA,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST,COD_EXTERNO,LOG_ESTATUS)
                                        values
                                        (
                                        "' . date('Y-m-d H:i:s') . '",
                                        "' . $COD_MULTEMP . '",
                                        "' . $NOM_USUARIO . '",
                                        "7",
                                        "' . $COD_MULTEMP . '",
                                        "' . $COD_UNIVEND . '",
                                        "7",
                                        "' . $COD_EXTERNO . '",
                                        "S"    
                                        );';

        $arraP = mysqli_query($conn, $sql);
        $COD_VENDEDOR = mysqli_insert_id($conn);

        return $COD_VENDEDOR;
    } else {
        $COD_VENDEDOR = $result['COD_USUARIO'];
        return $COD_VENDEDOR;
    }
}
//function vondedor
function fnatendente($conn, $NOM_USUARIO, $COD_MULTEMP, $COD_UNIVEND, $cod_externo)
{

    if (rtrim(trim($NOM_USUARIO)) != '') {

        $sqlbusca = "select count(*) as exist,COD_USUARIO,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST 
                       from usuarios where COD_EMPRESA=$COD_MULTEMP and COD_MULTEMP='$COD_MULTEMP' and cod_externo='$cod_externo'";

        $result = mysqli_fetch_assoc(mysqli_query($conn, $sqlbusca));
        //return $sqlbusca; 
        if ($result['exist'] == 0) {

            $sql = 'insert into usuarios (COD_EMPRESA,NOM_USUARIO,COD_TPUSUARIO,COD_MULTEMP,COD_UNIVEND,COD_DEFSIST,DAT_CADASTR,cod_externo,LOG_ESTATUS)
                                        values
                                        (
                                        "' . $COD_MULTEMP . '",
                                        "Atendente:' . $NOM_USUARIO . '",
                                        "11",
                                        "' . $COD_MULTEMP . '",
                                        "' . $COD_UNIVEND . '",
                                        "11",
                                        "' . DATE('Y-m-d H:i:s') . '",
                                        "' . $cod_externo . '",
                                        "S"    
                                        ) ';

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
//=========================


function fnDataFull($str)
{
    if (($timestamp = strtotime($str)) === false) {
        $date = '';
        return $date;
    } else {
        return date('d/m/Y H:i:s', $timestamp);
    }
}
function calc_idade($data_nasc)
{

    $data_nasc = explode("-", $data_nasc);

    $data = date("Y-m-d");

    $data = explode("-", $data);

    $anos = $data[0] - $data_nasc[0];

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
    $dec = $arrayDados['DECIMAL'];
    ////////////ofertas
    //=========================

    // flag da pergunta se vai ou nao exibir a lista
    //Select busca configuração TKT
    $selconfig = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =" . $arrayDados['cod_empresa'] . "   and LOG_ATIVO_TKT = 'S'";
    $conf = mysqli_query($arrayDados['connempresa'], $selconfig);
    $rwconfig = mysqli_fetch_assoc($conf);
    //select codigo blacklist
    $blacklist = "select * from 	blacklisttkt where cod_exclusa =0 and COD_BLKLIST='" . $rwconfig['COD_BLKLIST'] . "'";
    $confblacklist = mysqli_query($arrayDados['connempresa'], $blacklist);
    $rsblk = mysqli_fetch_assoc($confblacklist);

    $arraydia = explode(";", $rwconfig['NUM_HISTORICO_TKT']);
    $max_historico_tkt = $arraydia[1];
    $min_historico_tkt = $arraydia[0];
    $qtd_compras_tkt = $rwconfig['QTD_COMPRAS_TKT'];
    $cod_categorBlk = $rsblk['COD_CATEGOR'];
    $cod_empresa = $arrayDados['cod_empresa'];
    $cod_loja = $dadosLogin['idloja'];
    $regrapreco = $rwconfig['DES_PRATPRC'];
    $QTD_PRODUTOS_CAT = $rwconfig['QTD_PRODUTOS_CAT'];
    //$DES_VALIDADE=$rwconfig['DES_VALIDADE'] - 1;
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
    $cod_loja = $arrayDados['idloja'];

    if (mysqli_num_rows($conf) <= 0) {
        $xamls = addslashes("Não existe configuração no TICKET!");
        return array('msgerro' => $xamls);
    } else {
    }


    //busca personas do cliente - PERFIL
    $sqlPersonaCli = "SELECT  A.COD_PERSONA 
	FROM PERSONACLASSIFICA A
	WHERE A.COD_CLIENTE = " . $arrayDados['id_cliente'] . " ";
    $arrayQuery = mysqli_query(connTemp($cod_empresa, ''), $sqlPersonaCli) or die(mysqli_error());
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
        // $qrPersonaCli = mysqli_fetch_assoc($arrayQuery); 
        // $personaProduto= $qrPersonaCli['COD_PERSONA'];

    } else {
        //$personaProduto  = " ";	
        $personaProduto1  = "0";
    }


    //Select Habitos de compra
    /*   if($rsblk['COD_CATEGOR']!='')
       {
       $cod_categorBlkand= "AND C.COD_CATEGOR NOT IN ($cod_categorBlk)";  
       }    

     $sqlhabitos="SELECT  DISTINCT  C.DES_PRODUTO, C.COD_PRODUTO,C.COD_EXTERNO 
                   FROM VENDAS A,ITEMVENDA B, PRODUTOCLIENTE C
                   WHERE A.COD_CLIENTE = ".$arrayDados['id_cliente']." AND
                   A.COD_VENDA=B.COD_VENDA AND
                   B.COD_PRODUTO=C.COD_PRODUTO AND
                   C.COD_EMPRESA=$cod_empresa  AND
                   A.DAT_CADASTR >= ADDDATE( NOW(), INTERVAL - $max_historico_tkt DAY) AND
                   A.DAT_CADASTR <= ADDDATE( NOW(), INTERVAL - $min_historico_tkt DAY) 
                   $cod_categorBlkand
                   ORDER BY rand(DES_PRODUTO) LIMIT $qtd_compras_tkt";*/
    if ($cod_categorBlk != '') {
        $cod_categorBlkand = "AND C.COD_CATEGOR NOT IN ($cod_categorBlk)";
    }
    /* $sqlhabitos = "SELECT  DISTINCT  C.DES_PRODUTO, C.COD_PRODUTO,C.COD_EXTERNO 
                   FROM VENDAS A,ITEMVENDA B, PRODUTOCLIENTE C
                   WHERE A.COD_CLIENTE = " . $arrayDados['id_cliente'] . " AND
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
                   COUNT(B.COD_PRODUTO) AS quantidade_vendas
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
                   COUNT(B.COD_PRODUTO) > 0
               ORDER BY 
                   quantidade_vendas DESC
               LIMIT $qtd_compras_tkt;
               ";

    $habitosexec = mysqli_query($arrayDados['connempresa'], $sqlhabitos);
    /* if($arrayDados['id_cliente'] =='256268')
       {    
        $testesql="INSERT INTO log_teste (SQL_TESTE) VALUES ('".addslashes($sqlhabitos)."');";
        mysqli_query($arrayDados['connempresa'], $testesql);
       
       }*/
    if (!$habitosexec) {
        //   fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],$xamls);

        $habitos[] = array('msgerro' => 'Cliente que nao for cadastrado não gera habito de compra!');
    } else {
        //verifica se tem itens na lista de produtos
        if (mysqli_num_rows($habitosexec) == 0) {
            $msghab = 'Não há Habito de compras!';
            $habitos[] =  array('msgerro' => $msghab);
        }
        // exibi itens na lista de ws    
        while ($rwhabitos = mysqli_fetch_assoc($habitosexec)) {
            $cod_habito .= $rwhabitos['COD_PRODUTO'] . ',';
            $habitos[] = array(
                'codigoexterno' => $rwhabitos['COD_EXTERNO'],
                'codigointerno' => $rwhabitos['COD_PRODUTO'],
                'descricao' => $rwhabitos['DES_PRODUTO']
            );
        }
        //   fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],'HABITO DE COMPRAS OK');

    }

    //=========================================FIM DO HABITO DE COMPRAS

    //ofertasTicket 
    $sqltkt = "CALL SP_BUSCA_TKT('$cod_empresa', '$cod_loja','$personaProduto1','N','S',$QTD_PRODUTOS_CAT,$qtd_produtos_tkt, 'CAD');";

    $tktexec = mysqli_query($arrayDados['connempresa'], $sqltkt);
    if (!$tktexec) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($arrayDados['connempresa'], $sqltkt);
        } catch (mysqli_sql_exception $e) {
            $msgsql = $e;
        }
        $msg = "ofertasTicket : $msgsql";
        $xamls = addslashes($msg);
        $ofertasTicket[] = array('msgerro' => $xamls);
    } else {
        //verifica se tem itens na lista de produtos
        if (mysqli_num_rows($tktexec) == 0) {
            $msgtkt = 'Não há Produtos no ticket!';
            $ofertasTicket[] = array('msgerro' => $msgtkt);
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
                    'descricao' => $rwtkt['NOM_PRODTKT'],
                    'categoria' => $rwtkt['DES_CATEGOR'],
                    'preco' => $rwtkt['VAL_PRODTKT'],
                    'valorcomdesconto' => $rwtkt['VAL_PROMTKT'],
                    'desconto' => $rwtkt['ABV_DESCTKT'],
                    'descontopctgeral' => $rwtkt['PCT_DESCTKT'],
                    'imagem' => $IMG1,
                    'grupodesc' => $rwtkt['COD_DESCTKT']
                );
            }

            $ofertasTicket = array_orderby($ofertasTicket, 'num_ordenac', SORT_ASC);
            $ofertasTicket = array_filter($ofertasTicket);
            // fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],'OFERTASTICKET OK......');

        }
    }
    mysqli_free_result($tktexec);
    mysqli_next_result($arrayDados['connempresa']);
    //================================================FIM DAS OFERTAS DO TKT
    //ofertas destaque

    $sqldestaque = "CALL SP_BUSCA_TKT('$cod_empresa', '$cod_loja','$personaProduto1','S','N',$QTD_PRODUTOS_CAT,$qtd_ofertas_tkt, 'CAD');";

    /* if($arrayDados['id_cliente']=='99878')
		{    
			$sqllog="INSERT INTO teste (des_teste) VALUES ('". addslashes($sqldestaque)."')";
			mysqli_query($arrayDados['connempresa'], $sqllog);
		}*/

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
        $ofertapromocao[] = array('msgerro' => $xamls);
    } else {
        //verifica se tem itens na lista de produtos
        if (mysqli_num_rows($descexec) == 0) {
            $msgP = 'Não há produtos em promoção!';
            $ofertapromocao[] = array('msgerro' => $msgP);
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
                    'descricao' => $rwdesc['NOM_PRODTKT'],
                    'preco' => $rwdesc['VAL_PRODTKT'],
                    'valorcomdesconto' => $rwdesc['VAL_PROMTKT'],
                    'desconto' => $rwtkt['ABV_DESCTKT'],
                    'descontopctgeral' => $rwdesc['PCT_DESCTKT'],
                    'imagem' => $IMG2
                );
            }
            $ofertapromocao = array_filter($ofertapromocao);
        }
    }
    mysqli_free_result($descexec);
    mysqli_next_result($arrayDados['connempresa']);
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
				'" . $lojas[0]['COD_UNIVEND'] . "', 
				'" . $lojas[0]['COD_MAQUINA'] . "', 
				'" . $arrayDados['cod_user'] . "', 
				'" . $todosProdutos . "', 
				'CAD'    
				) ";

    $ROWsql = mysqli_query($arrayDados['connempresa'], $sql1);
    $arrayretorno = mysqli_fetch_assoc($ROWsql);
    mysqli_free_result($arrayretorno);
    mysqli_next_result($arrayDados['connempresa']);


    $ofertapromocao1 = addslashes(str_replace(array("\n", ""), array("", " "), serialize($ofertapromocao)));
    $ofertasTicket1 = addslashes(str_replace(array("\n", ""), array("", " "), serialize($ofertasTicket)));
    $habitos1 = addslashes(str_replace(array("\n", ""), array("", " "), serialize($habitos)));


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

            //depois da validade terminar busco de novo     
            $sql = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and COD_EMPRESA=" . $arrayDados['cod_empresa'] . " and LOG_EMISDIA='S' and DAT_VALIDADE >= '" . date('Y-m-d') . "' ORDER by COD_GERAL DESC limit 1;";
            $misdiatkt = mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'], $sql));
        }
        //===============================================================
        //retorno da array      
        if (date('Y-m-d') <= $misdiatkt['DAT_VALIDADE']) {
            if ($rwconfig['LOG_LISTAWS'] == 'S') {
                $acao2 = array(
                    'produtoHabito' => unserialize($misdiatkt['DES_HABITOS']),
                    'produtoTicket' => unserialize($misdiatkt['DES_TICKET']),
                    'produtoPromocao' => unserialize($misdiatkt['DES_PROMOCAO']),
                );
            }
        }
        //====================================================================
        //se a emissão nao for diaria      
    } else {
        $dtdevolucao = 'NULL,';
        $insert = "INSERT INTO TICKET_DADOS(COD_TICKET,DES_PROMOCAO,DES_TICKET,DES_HABITOS,COD_EMPRESA, COD_CLIENTE,COD_UNIVEND,DAT_VALIDADE,LOG_EMISDIA)VALUES(" . $arrayretorno['COD_TICKET'] . ",'" . $ofertapromocao1 . "','" . $ofertasTicket1 . "','" . $habitos1 . "', " . $arrayDados['cod_empresa'] . "," . $cod_client . "," . $cod_loja . ", $dtdevolucao'" . $LOG_EMISDIA . "' )";
        mysqli_query($arrayDados['connempresa'], rtrim(ltrim(trim($insert))));

        //$LOGDADOS="INSERT INTO db_host9.diogo_log (LOG, EMPRESA) VALUES ('".addslashes($insert)."', '125');";
        //mysqli_query($arrayDados['connempresa'],$LOGDADOS);  



        //depois da validade terminar busco de novo     
        $sql = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and  LOG_EMISDIA='N' and COD_EMPRESA=" . $arrayDados['cod_empresa'] . " ORDER by COD_GERAL DESC limit 1;";
        $misdiatkt = mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'], $sql));

        $acao2 = array(
            'produtoHabito' => unserialize($misdiatkt['DES_HABITOS']),
            'produtoTicket' => unserialize($misdiatkt['DES_TICKET']),
            'produtoPromocao' => unserialize($misdiatkt['DES_PROMOCAO']),
        );
    }
    $acao2 = array_filter($acao2);
    //===================================================== 


    //FIM DO IF DA FLAG ATIVA OU DESATIVA  

    //if($cod_empresa=='122')
    //{
    //    return $sqltkt;
    //}

    return $acao2;
}
//=====fim gera tkt
//gera tkt lista 
function fngeratktlista($arrayDados)
{
    $dec = $arrayDados['DECIMAL'];
    ////////////ofertas
    //=========================

    // flag da pergunta se vai ou nao exibir a lista
    //Select busca configuração TKT
    $selconfig = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =" . $arrayDados['cod_empresa'] . "   and LOG_ATIVO_TKT = 'S'";
    $conf = mysqli_query($arrayDados['connempresa'], $selconfig);


    $rwconfig = mysqli_fetch_assoc($conf);
    //select codigo blacklist
    $blacklist = "select * from 	blacklisttkt where cod_exclusa =0 and COD_BLKLIST='" . $rwconfig['COD_BLKLIST'] . "'";
    $confblacklist = mysqli_query($arrayDados['connempresa'], $blacklist);
    $rsblk = mysqli_fetch_assoc($confblacklist);

    $arraydia = explode(";", $rwconfig['NUM_HISTORICO_TKT']);
    $max_historico_tkt = $arraydia[1];
    $min_historico_tkt = $arraydia[0];
    $qtd_compras_tkt = $rwconfig['QTD_COMPRAS_TKT'];
    $cod_categorBlk = $rsblk['COD_CATEGOR'];
    $cod_empresa = $arrayDados['cod_empresa'];
    $cod_loja = $dadosLogin['idloja'];
    $regrapreco = $rwconfig['DES_PRATPRC'];
    $QTD_PRODUTOS_CAT = $rwconfig['QTD_PRODUTOS_CAT'];
    //$DES_VALIDADE=$rwconfig['DES_VALIDADE'] - 1;
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

    $cod_loja = $arrayDados['idloja'];
    if (!$conf || !$confblacklist) {
        //$xamls= addslashes("Não existe configuração no TICKET!");
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($arrayDados['connempresa'], $blacklist);
        } catch (mysqli_sql_exception $e) {
            $msgsql = $e;
        }
        $msg = "ofertasTicket : $msgsql";
        $xamls = addslashes($msg);
        return  array('msgerro' => $xamls);
    } else {
    }


    //busca personas do cliente - PERFIL
    $sqlPersonaCli = "SELECT  A.COD_PERSONA 
	FROM PERSONACLASSIFICA A
	WHERE A.COD_CLIENTE = " . $arrayDados['id_cliente'] . " ";
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
        // $qrPersonaCli = mysqli_fetch_assoc($arrayQuery); 
        // $personaProduto= $qrPersonaCli['COD_PERSONA'];

    } else {
        //$personaProduto  = " ";	
        $personaProduto1  = "0";
    }


    //Select Habitos de compra
    if ($rsblk['COD_CATEGOR'] != '') {
        $cod_categorBlkand = "AND C.COD_CATEGOR NOT IN ($cod_categorBlk)";
    }

    /*$sqlhabitos="SELECT  DISTINCT  C.DES_PRODUTO, C.COD_PRODUTO,C.COD_EXTERNO 
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
                   COUNT(B.COD_PRODUTO) AS quantidade_vendas
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
                   COUNT(B.COD_PRODUTO) > 0
               ORDER BY 
                   quantidade_vendas DESC
               LIMIT $qtd_compras_tkt;
               ";



    $habitosexec = mysqli_query($arrayDados['connempresa'], $sqlhabitos);

    if (!$habitosexec) {
        //   fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],$xamls);

        $habitos[] = array('msgerro' => 'Cliente que nao for cadastrado não gera habito de compra!');
    } else {
        //verifica se tem itens na lista de produtos
        if (mysqli_num_rows($habitosexec) == 0) {
            $msghab = 'Não há Habito de compras!';
            $habitos[] =  array('msgerro' => $msghab);
        }
        // exibi itens na lista de ws    
        while ($rwhabitos = mysqli_fetch_assoc($habitosexec)) {
            $cod_habito .= $rwhabitos['COD_PRODUTO'] . ',';
            $habitos[] = array(
                'codigoexterno' => $rwhabitos['COD_EXTERNO'],
                'codigointerno' => $rwhabitos['COD_PRODUTO'],
                'descricao' => $rwhabitos['DES_PRODUTO']
            );
        }
        //   fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],'HABITO DE COMPRAS OK');

    }

    //=========================================FIM DO HABITO DE COMPRAS

    //ofertasTicket 
    $QTD_PRODUTOS_CAT = empty($QTD_PRODUTOS_CAT) ? 'NULL' : $QTD_PRODUTOS_CAT;
    $qtd_produtos_tkt = empty($qtd_produtos_tkt) ? 'NULL' : $qtd_produtos_tkt;
    $sqltkt = "CALL SP_BUSCA_TKT('$cod_empresa', '$cod_loja','$personaProduto1','N','S',$QTD_PRODUTOS_CAT,$qtd_produtos_tkt, 'CAD');";
    $tktexec = mysqli_query($arrayDados['connempresa'], $sqltkt);
    /*if ($cod_empresa == '219') {
        print_r($sqltkt);
        $sqllog = "INSERT INTO teste (des_teste) VALUES ('" . addslashes($sqltkt) . "')";
        mysqli_query($arrayDados['connempresa'], $sqllog);

        exit;
    }*/

    if (!$tktexec) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($arrayDados['connempresa'], $sqltkt);
        } catch (mysqli_sql_exception $e) {
            $msgsql = $e;
        }
        $msg = "ofertasTicket : $msgsql";
        $xamls = addslashes($msg);
        $ofertasTicket[] = array(
            'num_ordenac' => null,
            'codigoexterno' => null,
            'codigointerno' => null,
            'descricao' => null,
            'categoria' => null,
            'ean' => null,
            'preco' => null,
            'valorcomdesconto' => null,
            'desconto' => '0.00',
            'imagem' => null,
            'msgerro' => $xamls
        );
    } else {
        //verifica se tem itens na lista de produtos
        if (mysqli_num_rows($tktexec) == 0) {
            $msgtkt = 'Não há Produtos no ticket!';
            // $ofertasTicket[] = array('msgerro' => $msgtkt);
            $ofertasTicket[] = array(
                'num_ordenac' => 'null',
                'codigoexterno' => null,
                'codigointerno' => null,
                'descricao' => null,
                'categoria' => null,
                'ean' => null,
                'preco' => null,
                'valorcomdesconto' => null,
                'desconto' => '0.00',
                'imagem' => null,
                'msgerro' => $msgtkt
            );
        } else {
            // exibi itens na lista de ws    
            while ($rwtkt = mysqli_fetch_assoc($tktexec)) {
                if ($rwtkt['DES_IMAGEM'] != "") {
                    $IMG = "https://img.bunker.mk/media/clientes/$cod_empresa/produtos/" . $rwtkt['DES_IMAGEM'] . "";
                }
                if ($rwtkt['EAN'] == '') {
                    $ean = 0;
                } else {
                    $ean = $rwtkt['EAN'];
                }

                $cod_tkt .= $rwtkt['COD_PRODUTO'] . ',';
                $ofertasTicket[] = array(
                    'num_ordenac' => $rwtkt['NUM_ORDENAC'],
                    'codigoexterno' => $rwtkt['COD_EXTERNO'],
                    'codigointerno' => $rwtkt['COD_PRODUTO'],
                    'descricao' => $rwtkt['NOM_PRODTKT'],
                    'categoria' => $rwtkt['DES_CATEGOR'],
                    'ean' => $ean,
                    'preco' => fnformatavalorretorno($rwtkt['VAL_PRODTKT'], $dec),
                    'valorcomdesconto' => fnformatavalorretorno($rwtkt['VAL_PROMTKT'], $dec),
                    'desconto' => '0.00',
                    'imagem' => $IMG
                );
            }

            $ofertasTicket = array_orderby($ofertasTicket, 'num_ordenac', SORT_ASC);
            // fngravalogMSG($arrayDados['connadm'],$arrayDados['login'],$arrayDados['cod_empresa'],$arrayDados['cpf'],$arrayDados['idloja'],$arrayDados['idmaquina'],$arrayDados['codvendedor'],$arrayDados['nomevendedor'],$arrayDados['pagina'],'OFERTASTICKET OK......');

        }
    }

    mysqli_free_result($tktexec);
    mysqli_next_result($arrayDados['connempresa']);
    //================================================FIM DAS OFERTAS DO TKT
    //ofertas destaque

    //$sqldestaque = "CALL SP_BUSCA_TKT('$cod_empresa', '$cod_loja','$personaProduto1','S','N',$QTD_PRODUTOS_CAT,$qtd_ofertas_tkt, 'CAD');";
    $QTD_PRODUTOS_CAT = empty($QTD_PRODUTOS_CAT) ? 'NULL' : $QTD_PRODUTOS_CAT;
    $qtd_ofertas_tkt = empty($qtd_ofertas_tkt) ? 'NULL' : $qtd_ofertas_tkt;

    $sqldestaque = "CALL SP_BUSCA_TKT('$cod_empresa', '$cod_loja','$personaProduto1','S','N',$QTD_PRODUTOS_CAT,$qtd_ofertas_tkt, 'CAD');";
    $descexec = mysqli_query($arrayDados['connempresa'], $sqldestaque);

    /* if ($cod_empresa == '219') {
        print_r($descexec);
        $sqllog = "INSERT INTO teste (des_teste) VALUES ('" . addslashes($sqldestaque) . "')";
        mysqli_query($arrayDados['connempresa'], $sqllog);

        exit;
    }*/

    if (!$descexec) {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        try {
            mysqli_query($arrayDados['connempresa'], $sqldestaque);
        } catch (mysqli_sql_exception $e) {
            $msgsql = $e;
        }
        $msg = "ofertas destaque: $msgsql";
        $xamls = addslashes($msg);
        $ofertapromocao[] = array('msgerro' => $xamls);
    } else {
        //verifica se tem itens na lista de produtos
        if (mysqli_num_rows($descexec) == 0) {
            $msgP = 'Não há produtos em promoção!';
            $ofertapromocao[] = array(
                'codigoexterno' => 'null',
                'codigointerno' => null,
                'descricao' => null,
                'ean' => null,
                'preco' => null,
                'valorcomdesconto' => null,
                'imagem' => null,
                'msgerro' => $msgP
            );
        } else {
            // exibi itens na lista de ws    
            while ($rwdesc = mysqli_fetch_assoc($descexec)) {
                if ($rwdesc['DES_IMAGEM'] != "") {
                    $IMG = "https://img.bunker.mk/media/clientes/$cod_empresa/produtos/" . $rwdesc['DES_IMAGEM'] . "";
                }
                if ($rwtkt['EAN'] == '') {
                    $ean = 0;
                } else {
                    $ean = $rwtkt['EAN'];
                }

                $cod_oferta .= $rwdesc['COD_PRODUTO'] . ',';
                $ofertapromocao[] = array(
                    'codigoexterno' => $rwdesc['COD_EXTERNO'],
                    'codigointerno' => $rwdesc['COD_PRODUTO'],
                    'descricao' => $rwdesc['NOM_PRODTKT'],
                    'ean' => $ean,
                    'preco' => fnformatavalorretorno($rwdesc['VAL_PRODTKT'], $dec),
                    'valorcomdesconto' => fnformatavalorretorno($rwdesc['VAL_PROMTKT'], $dec),
                    'imagem' => $IMG
                );
            }
        }
    }

    mysqli_free_result($descexec);
    mysqli_next_result($arrayDados['connempresa']);
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
				'" . $lojas[0]['COD_UNIVEND'] . "', 
				'" . $lojas[0]['COD_MAQUINA'] . "', 
				'" . $arrayDados['cod_user'] . "', 
				'" . $todosProdutos . "', 
				'CAD'    
				) ";

    $ROWsql = mysqli_query($arrayDados['connempresa'], $sql1);
    $arrayretorno = mysqli_fetch_assoc($ROWsql);
    mysqli_free_result($arrayretorno);
    mysqli_next_result($arrayDados['connempresa']);
    //alteração de registro para contabilizar em relatorios.

    $sqlupdatevisualizacao = "UPDATE ticket SET LOG_VISUALIZACAO='1',LOG_PRECODOIS=1 WHERE  COD_TICKET='" . $arrayretorno['COD_TICKET'] . "'";
    $rwupdatevisualizacao = mysqli_query($arrayDados['connempresa'], $sqlupdatevisualizacao);


    $ofertapromocao1 = addslashes(str_replace(array("\n", ""), array("", " "), serialize($ofertapromocao)));
    $ofertasTicket1 = addslashes(str_replace(array("\n", ""), array("", " "), serialize($ofertasTicket)));
    $habitos1 = addslashes(str_replace(array("\n", ""), array("", " "), serialize($habitos)));

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

            //depois da validade terminar busco de novo     
            $sql = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and COD_EMPRESA=" . $arrayDados['cod_empresa'] . " and LOG_EMISDIA='S' and DAT_VALIDADE >= '" . date('Y-m-d') . "' ORDER by COD_GERAL DESC limit 1;";
            $misdiatkt = mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'], $sql));
        }
        //===============================================================
        //retorno da array      
        if (date('Y-m-d') <= $misdiatkt['DAT_VALIDADE']) {
            if ($rwconfig['LOG_LISTAWS'] == 'S') {
                $acao2 = array(
                    'produtoHabito' => unserialize($misdiatkt['DES_HABITOS']),
                    'produtoTicket' => unserialize($misdiatkt['DES_TICKET']),
                    'produtoPromocao' => unserialize($misdiatkt['DES_PROMOCAO']),
                );
            }
        }
        //====================================================================
        //se a emissão nao for diaria     
    } else {
        $dtdevolucao = 'NULL,';
        $insert = "INSERT INTO TICKET_DADOS(COD_TICKET,DES_PROMOCAO,DES_TICKET,DES_HABITOS,COD_EMPRESA, COD_CLIENTE,COD_UNIVEND,DAT_VALIDADE,LOG_EMISDIA)VALUES(" . $arrayretorno['COD_TICKET'] . ",'" . $ofertapromocao1 . "','" . $ofertasTicket1 . "','" . $habitos1 . "', " . $arrayDados['cod_empresa'] . "," . $cod_client . "," . $cod_loja . ", $dtdevolucao'" . $LOG_EMISDIA . "' )";
        mysqli_query($arrayDados['connempresa'], rtrim(ltrim(trim($insert))));



        //depois da validade terminar busco de novo     
        $sql = "SELECT last_insert_id(COD_GERAL) as COD_GERAL,ticket_dados.* from ticket_dados where COD_CLIENTE=$cod_client and  LOG_EMISDIA='N' and COD_EMPRESA=" . $arrayDados['cod_empresa'] . " ORDER by COD_GERAL DESC limit 1;";
        $misdiatkt = mysqli_fetch_assoc(mysqli_query($arrayDados['connempresa'], $sql));

        $acao2 = array(
            'produtoHabito' => unserialize($misdiatkt['DES_HABITOS']),
            'produtoTicket' => unserialize($misdiatkt['DES_TICKET']),
            'produtoPromocao' => unserialize($misdiatkt['DES_PROMOCAO']),
        );
    }
    //===================================================== 


    //FIM DO IF DA FLAG ATIVA OU DESATIVA  

    return $acao2;
}
//=== fim lista   


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
function fnVerificasaldo($arrayvalorres)
{
    // =H22/G22*100;
    $percentual = ($arrayvalorres['vl_venda'] * $arrayvalorres['PCT_MAXRESG']) / 100;
    return $percentual;
}
function fnLimpaCampo($campo, $adicionaBarras = false)
{
    $campo = preg_replace("/(from|alter table|select|drop|insert|delete|update|where|drop table|show tables|#|\*|--|\\\\)/i", "", $campo);
    $campo = trim($campo); //limpa espaços vazio
    $campo = strip_tags($campo); //tira tags html e php
    if ($adicionaBarras || !get_magic_quotes_gpc())
        $campo = addslashes($campo);
    return $campo;
}
function fnLimpaSTRING($valor)
{
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    $valor = str_replace("(", "", $valor);
    $valor = str_replace(")", "", $valor);
    $valor = str_replace("NULL", "", $valor);
    $valor = str_replace("null", "", $valor);
    $valor = str_replace(" ", "", $valor);
    return trim($valor);
}
function fnMascaraCampo($str = "")
{
    $str = trim($str);

    $mask = preg_replace("/[0-9]/is", "0", str_ireplace(" ", "", $str));

    if ($mask == "000.000.000-00") {
        //CPF
        $chr = 6;
        $str = str_ireplace(" ", "", $str);
        $str = substr($str, 0, $chr) . preg_replace("/[0-9]/is", "*", substr($str, $chr, strlen($str)));
    } elseif ($mask == "00.000.000/0000-00") {
        //CNPJ
        $chr = 6;
        $str = str_ireplace(" ", "", $str);
        $str = substr($str, 0, $chr) . preg_replace("/[0-9]/is", "*", substr($str, $chr, strlen($str)));
    } elseif (
        $mask == "(00)0000-0000" || $mask == "(00)00000-0000" || $mask == "(00)0000-00000"
        || $mask == "0000-0000" || $mask == "00000-0000" || $mask == "0000-00000"
    ) {
        //TELEFONE
        $str = str_ireplace(" ", "", $str);
        $str = str_ireplace(")", ") ", $str);
        $e = explode("-", $str);
        $e[0] = preg_replace("/[0-9]/is", "*", @$e[0]);
        $str = implode("-", $e);
    } elseif ($mask == "00/00/0000") {
        //DATA BR
        $dt = explode("/", $str);
        $dt[1] = preg_replace("/[0-9]/is", "*", @$dt[1]);
        $dt[2] = preg_replace("/[0-9]/is", "*", @$dt[2]);
        $str = implode("/", $dt);
    } elseif ($mask == "0000-00-00") {
        //DATA EN
        $dt = explode("-", $str);
        $dt[0] = preg_replace("/[0-9]/is", "*", @$dt[0]);
        $str = implode("-", $dt);
    } elseif ($mask == "00000-000") {
        //CEP
        $e = explode("-", $str);
        $chr = 3;
        $e[0] = substr($str, 0, $chr) . preg_replace("/[0-9]/is", "*", substr($e[0], $chr, strlen($e[0])));
        $e[1] = preg_replace("/[0-9]/is", "*", $e[1]);
        $str = implode("-", $e);
    } elseif (filter_var(str_ireplace(" ", "", $str), FILTER_VALIDATE_EMAIL)) {
        //EMAIL
        $e = explode("@", $str);
        $chr = round(strlen($e[0]) / 2);
        $e[0] = substr($str, 0, $chr) . preg_replace("/[a-zA-Z0-9]/is", "*", substr($e[0], $chr, strlen($e[0])));
        $str = implode("@", $e);
    } elseif (is_numeric(str_ireplace(" ", "", $str))) {
        //NUMERO
        $str = str_ireplace(" ", "", $str);
        //$chr = round(strlen($str)/2);
        $chr = strlen($str) - 4;
        if ($chr <= 1) {
            $chr = round(strlen($str) / 2);
        }
        $part1 = substr($str, 0, $chr);
        $part2 = substr($str, $chr, strlen($str));

        $part1 = preg_replace("/[a-zA-Z0-9]/is", "*", $part1);
        $str = $part1 . $part2;
        //$str = substr($str,0,$chr).preg_replace("/[0-9]/is","*",substr($str,$chr,strlen($str)));

    } else {
        //GENÉRICO
        $e = explode(" ", $str);
        foreach ($e as $k => $v) {
            if (
                $k == 0 || strtolower($v) == "do" || strtolower($v) == "da" || strtolower($v) == "dos" || strtolower($v) == "das"
                || strtolower($v) == "de"
            ) {
                continue;
            }
            $e[$k] = substr($v, 0, 1) . ".";
        }
        $str = implode(" ", $e);
    }
    return $str;
}
