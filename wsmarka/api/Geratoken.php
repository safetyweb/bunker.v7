<?php 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
require_once './oderfunctions.php';
require_once '../func/function.php';
require_once '../../_system/Class_conn.php';

if($_SERVER[REQUEST_METHOD]!='POST')
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "O metodo para capturar deve ser POST",
                                     "coderro": "400"
                                    }
                                ]
                     }';   
    $erroinformation=json_decode(json_encode($erroinformation));                 
    echo $erroinformation;
    exit();
}  
 $passmarka= getallheaders();
if(!array_key_exists('authorizationCode', $passmarka))
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400"
                                     }
                                ]
                   }';    
    $erroinformation=json_decode(json_encode($erroinformation));
    echo $erroinformation;
    exit();  
}    

$autoriz=fndecode(base64_decode($passmarka[authorizationCode]));
$arraydadosaut=explode(';',$autoriz);

//validação do usuario
$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$arraydadosaut['0']."', '".fnEncode($arraydadosaut['1'])."','','','".$arraydadosaut['4']."','','')";
$buscauser=mysqli_query($connAdm->connAdm(),$sql);
if(empty($buscauser->num_rows)) 
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "Usuario ou senha invalido!",
                                     "coderro": "400"
                                     }
                                ]
                   }';
    $erroinformation=json_decode(json_encode($erroinformation));
    echo $erroinformation;
    exit();  
}   
$user=mysqli_fetch_assoc($buscauser);

 //VERIFICA SE A EMPRESA FOI DESABILITADA
if($user['LOG_ATIVO']=='N'){
    http_response_code(400);
    $erroinformation='{"errors": [
                            {
                             "message": "Oh não! A empresa foi desabilitada por algum motivo ;-[!",
                             "coderro": "400"
                             }
                        ]
           }';
    $erroinformation=json_decode(json_encode($erroinformation));
    echo $erroinformation;
    exit();  
}
         //VERIFICA SE O USUARIO FOI DESABILITADA
if($user['LOG_ESTATUS']=='N'){
    http_response_code(400);
    $erroinformation='{"errors": [
                            {
                             "message": "Oh não! Usuario foi desabilitado ;-[!",
                             "coderro": "400"
                             }
                        ]
           }';
    $erroinformation=json_decode(json_encode($erroinformation));
    echo $erroinformation;
    exit();  
}

//================fim da validação de senha
//abrindo a com temporaria
$conexaotmp= connTemp($arraydadosaut['4'], '');
//====fim da conexão com a empresa
if($arraydadosaut['4']=='80')
{
    $testecon=file_get_contents("php://input");
    $arquivo = './log_txt/'.$dadosLogin['idloja'].'_sms_arquivo.txt';
    if (file_exists($arquivo)) {
        // Obtém o conteúdo atual do arquivo
        $conteudoAtual = file_get_contents($arquivo);
        // Acrescenta o novo conteúdo na última linha
        $novoConteudo = $conteudoAtual . PHP_EOL . $testecon;
        // Escreve o novo conteúdo no arquivo
        file_put_contents($arquivo, $novoConteudo);         
    } else {
        // Cria o arquivo e escreve o conteúdo nele
        file_put_contents($arquivo, $testecon);
    }
        
}


if(!array_key_exists('4', $arraydadosaut))
{

    http_response_code(400);
    $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400"
                                     }
                                ]
                   }'; 
    $erroinformation=json_decode(json_encode($erroinformation));               
    echo $erroinformation;
    exit();  
}
$Capturajson=file_get_contents("php://input");
$arrayjson=json_decode($Capturajson,true);

//tipo de token para resgate ou cadastro
if($arrayjson['tipoGeracao']=='1' || $arrayjson['tipoGeracao']=='2'){
}else{
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "O campo tipoGeracao precisa ser preenchido com 1 para Cadastro 2 para resgate!",
                                     "coderro": "400"
                                     }
                                ]
                   }'; 
     $erroinformation=json_decode(json_encode($erroinformation));
     echo $erroinformation;
     exit();  

}
$celular=preg_replace("/[^0-9]/", "", $arrayjson['celular']);
$whatsapp=preg_replace("/[^0-9]/", "", $arrayjson['whatsapp']);

//$row[COD_CHAVECO]
if($user[COD_CHAVECO]=='1' || $user[COD_CHAVECO]=='2' || $user[COD_CHAVECO]=='5')
{
        if($arrayjson['cpf']!='')
        {
                $cpfconsulta=" and NUM_CGCECPF='".fnlimpaCPF($arrayjson['cpf'])."'";
        }else{
                http_response_code(400);
                $erroinformation='{"errors": [
                                    {
                                     "message": "Campo CPF Não Pode ser Vazio",
                                     "coderro": "400"
                                     }
                                ]
                   }'; 
                $erroinformation=json_decode(json_encode($erroinformation));
                echo $erroinformation;
                exit();
        }
}
if($user[COD_CHAVECO]=='3')
{
        if($celular!='')
        {
            $numcelular=" and NUM_CELULAR='".fnlimpaCPF($celular)."'";
        }elseif ($whatsapp!='') {
            $numcelular=" and NUM_CELULAR='".fnlimpaCPF($whatsapp)."'";  
        }else{
            http_response_code(400);
            $erroinformation='{"errors": [
                                {
                                 "message": "Campo Celular ou whatsapp Não Pode ser Vazio!",
                                 "coderro": "400"
                                 }
                            ]
               }'; 
            $erroinformation=json_decode(json_encode($erroinformation));
            echo $erroinformation;
            exit();
        }
}elseif(fnlimpaCPF($celular)!=''){
        $numcelularFUL=" and NUM_CELULAR='".fnlimpaCPF($celular)."'";
}elseif($whatsapp!=''){
       $numcelularFUL=" and NUM_CELULAR='".fnlimpaCPF($whatsapp)."'";
}

      
if($user[COD_CHAVECO]=='7')
{
    if($arrayjson['email']!='')
    {
        $selemail=" and DES_EMAIL='".$arrayjson['email']."'";
    }else{
        http_response_code(400);
        $erroinformation='{"errors": [
                            {
                             "message": "Campo E-mail Não Pode ser Vazio",
                             "coderro": "400"
                             }
                        ]
           }'; 
        $erroinformation=json_decode(json_encode($erroinformation));
        echo $erroinformation;
        exit();
    }	
}

//===verificar se a campanha esta ativa
//envio sms
if($celular!='' || $whatsapp!='')
{
    
     //envio sms/whatsapp
     //verificar blk sms
    $celular1 = !empty($celular) ? $celular : $whatsapp;
    $sqlblk="SELECT 1 AS temnao FROM blacklist_sms WHERE cod_empresa='".$arraydadosaut['4']."' AND num_celular='$celular1'";
    $rwblk= mysqli_query($conexaotmp, $sqlblk);
    if($rwblk->num_rows > '0')
    {    
        http_response_code(400);
            $erroinformation='{"errors": [
                            {
                             "message": "Esse numero de celular é invalido ou foi bloqueado pelo gestor de sua empresa!",
                             "coderro": "400"
                             }
                        ]
           }'; 
        $erroinformation=json_decode(json_encode($erroinformation));
        echo $erroinformation;
        exit();
    }
    //contador de numeros 
    if(strlen($celular1) < 11)
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                            {
                             "message": "Numero invalido!",
                             "coderro": "400"
                             }
                        ]
           }'; 
        $erroinformation=json_decode(json_encode($erroinformation));
        echo $erroinformation;
        exit();
    }    
    //aqui vai ser para pegar a chave SMS para verificar qual campo vai ser preenchido para envio da comunicação
    if($arrayjson['tipoGeracao']=='1'){
        unset($campanhaSMS);
        $campanhaSMS="SELECT G.COD_EMPRESA,
                            G.COD_CAMPANHA,
                            G.LOG_STATUS,
                            G.LOG_PROCESS,
                            G.HOR_ESPECIF,
                            T.DES_TEMPLATE,
                            C.LOG_PROCESSA_SMS,
                            C.LOG_ATIVO,
                            C.LOG_CONTINU,
                            C.DAT_INI,
                            C.DAT_FIM,
                            C.DES_CAMPANHA	
                                FROM gatilho_sms G
                                INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
                                WHERE 
                                G.cod_empresa='".$arraydadosaut['4']."' 
                                AND G.TIP_GATILHO='tokenCad'
                                AND C.LOG_ATIVO='S'
                                AND C.LOG_PROCESSA_SMS='S';";
    }
    if($arrayjson['tipoGeracao']=='2'){
                unset($campanhaSMS);
                $campanhaSMS="SELECT G.COD_EMPRESA,
                                G.COD_CAMPANHA,
                                G.LOG_STATUS,
                                G.LOG_PROCESS,
                                G.HOR_ESPECIF,
                                T.DES_TEMPLATE,
                                C.LOG_PROCESSA_SMS,
                                C.LOG_ATIVO,
                                C.LOG_CONTINU,
                                C.DAT_INI,
                                C.DAT_FIM,
                                C.DES_CAMPANHA	
                                    FROM gatilho_sms G
                                    INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                    INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
                                    INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
                                    WHERE 
                                    G.cod_empresa='".$arraydadosaut['4']."' 
                                    AND G.TIP_GATILHO='tokenVen'
                                    AND C.LOG_ATIVO='S'
                                    AND C.LOG_PROCESSA_SMS='S';";
    }
    $rwcampanhaATIVA=mysqli_query($conexaotmp,$campanhaSMS);		
    if($rwcampanhaATIVA->num_rows <= '0')
    {    
        http_response_code(400);
        $erroinformation='{"errors": [
                            {
                             "message": "Não Tem campanha para esse tipo de envio configurada!",
                             "coderro": "400"
                             }
                        ]
           }'; 
        $erroinformation=json_decode(json_encode($erroinformation));
        echo $erroinformation;
        exit();
    }
}

$sqlverificatoken=  "SELECT 
                            case when TIP_TOKEN='".$arrayjson['tipoGeracao']."' $numcelularFUL  $numcelular $selemail  $cpfconsulta	then NUM_CELULAR ELSE null END NUM_CELULAR_COMPARAR,
                            case when TIP_TOKEN='".$arrayjson['tipoGeracao']."' $numcelularFUL  $numcelular $selemail  $cpfconsulta	then DAT_VALIDADE ELSE null END DATA_VALIDADE,
                            case when TIP_TOKEN='".$arrayjson['tipoGeracao']."' $numcelularFUL  $numcelular $selemail  $cpfconsulta	then QTD_REENVIO_CONTROLE ELSE 1 END QTD_REENVIO_CONTROLE,
                            case when TIP_TOKEN='".$arrayjson['tipoGeracao']."' $numcelularFUL  $numcelular $selemail  $cpfconsulta	then QTD_REENVIO ELSE 1 END QTD_REENVIO,
                            case when TIP_TOKEN='".$arrayjson['tipoGeracao']."' $numcelularFUL  $numcelular $selemail  $cpfconsulta   then COD_TOKEN ELSE NULL END COD_TOKEN,					 
                            LOG_USADO,
                            DES_TOKEN,
                            NUM_CELULAR,   
                            DAT_CADASTR									
                            FROM geratoken 
                        WHERE   COD_EMPRESA= '".$arraydadosaut['4']."' AND 
                                LOG_USADO=1 and
                                COD_EXCLUSA=0 and
                                TIP_TOKEN='".$arrayjson['tipoGeracao']."' 	
                                $numcelular
                                $selemail
                                $cpfconsulta
                               order by COD_TOKEN desc";

$rwlogtoken=mysqli_fetch_assoc(mysqli_query($conexaotmp,$sqlverificatoken));	
//trava o reenvio para o mesmo numero em 5 min 
//solicitação do maurice
$temporeenvio='5';
//limites de envio
$limitdeenvio=3;
if($rwlogtoken[QTD_REENVIO_CONTROLE] >= $limitdeenvio)
{
    http_response_code(400);
    $erroinformation='{"errors": [
                        {
                         "message": "limite de envio excedido!",
                         "coderro": "400"
                         }
                    ]
       }'; 
    $erroinformation=json_decode(json_encode($erroinformation));
    echo $erroinformation;
    exit();
}
//trava o reenvio para o mesmo numero em 5 min 
//solicitação do maurice
//$temporeenvio='5';

if($rwlogtoken[NUM_CELULAR_COMPARAR] == fnlimpaCPF($celular) && $celular!='')
{
    if($rwlogtoken[DATA_VALIDADE] > date('Y-m-d H:i:s')){
        http_response_code(400);
        $erroinformation='{"errors": [
                    {
                     "message": "Por favor aguarda '.$temporeenvio.' min para refazer o envio ou tente um novo numero!",
                     "coderro": "400"
                     }
                ]
        }'; 
        $erroinformation=json_decode(json_encode($erroinformation));
        echo $erroinformation;
        exit();    
    }	
}elseif($rwlogtoken[NUM_CELULAR_COMPARAR] == fnlimpaCPF($whatsapp) && $whatsapp!='' ){
    if($rwlogtoken[DATA_VALIDADE] > date('Y-m-d H:i:s')){
        http_response_code(400);
        $erroinformation='{"errors": [
                    {
                     "message": "Por favor aguarda '.$temporeenvio.' min para refazer o envio ou tente um novo numero!",
                     "coderro": "400"
                     }
                ]
        }'; 
        $erroinformation=json_decode(json_encode($erroinformation));
        echo $erroinformation;
      //  exit();    
    }	  
}

//defult quantidade de senha gerada
if($user[QTD_CHARTKN]==' '){$QTD_CHARTKN='6';}else{$QTD_CHARTKN=$user[QTD_CHARTKN];}
if($rwlogtoken['LOG_USADO']=='')
{	

    if($user[TIP_TOKEN]=='1'){$TIP_TOKEN=true;}else{$TIP_TOKEN=false;}
    
   //gerando token

    //inserir registro
    //se o token ja existir gerar um novo
    //verificar se o token ja foi utilizado e gerar um novo
    do {

        $senha = fngeraSenha($QTD_CHARTKN, $TIP_TOKEN, true, true);
        $sqlTokenvl = "SELECT 1 FROM geratoken WHERE 
                                                  COD_EXCLUSA=0 
                                                and COD_EMPRESA = '".$arraydadosaut['4']."' 
                                                AND DES_TOKEN = '$senha' $cpfconsulta";
        $arrayTokenvl = mysqli_query($conexaotmp,$sqlTokenvl);
        $existeTknvl = mysqli_num_rows($arrayTokenvl);

    } while ($existeTknvl > 0);

    $sqlinsert1="INSERT INTO geratoken (COD_EMPRESA, 
                                        DAT_CADASTR, 
                                        DES_TOKEN, 
                                        NOM_CLIENTE, 
                                        NUM_CGCECPF, 
                                        NUM_CELULAR, 
                                        DES_EMAIL,
                                        TIP_TOKEN,
                                        COD_UNIVEND,
                                        COD_USUCADA,
                                        DAT_VALIDADE) 
                                        VALUES 
                                        ('".$arraydadosaut['4']."', 
                                        '".date('Y-m-d H:i:s')."', 
                                        '".$senha."', 
                                        '".fnAcentos($arrayjson['nome'])."', 
                                        '".fnlimpaCPF($arrayjson['cpf'])."', 
                                       '".($celular != '' ? fnlimpaCPF($celular) : fnlimpaCPF($whatsapp)) ."', 
                                        '".$arrayjson['email']."',
                                        '".$arrayjson['tipoGeracao']."',
                                        '".$arraydadosaut['2']."',
                                        '".$user[COD_USUARIO]."',
                                        NOW() + INTERVAL ".$temporeenvio." MINUTE )";
    $regera=mysqli_query($conexaotmp,$sqlinsert1);
    if(!$regera)
    {
        http_response_code(400);
        $erroinformation='{"errors": [
                    {
                     "message": "Por favor verifique o nome ou numero do envio.",
                     "coderro": "400"
                     }
                ]
        }'; 
        $erroinformation=json_decode(json_encode($erroinformation));
        echo $erroinformation;
        exit(); 
    }					
       $COD_TOKEN= mysqli_insert_id($conexaotmp);
}else{

    //token atual
    $senha=$rwlogtoken['DES_TOKEN'];
    $COD_TOKEN=$rwlogtoken['COD_TOKEN'];	
    //inserir novo reenvio caso exista numero de telefone difetentes
    if($rwlogtoken[NUM_CELULAR_COMPARAR] != fnlimpaCPF($celular))
    {  
        $sqlinsert="INSERT INTO geratoken (COD_EMPRESA, 
                                            DAT_CADASTR, 
                                            DES_TOKEN, 
                                            NOM_CLIENTE, 
                                            NUM_CGCECPF, 
                                            NUM_CELULAR, 
                                            DES_EMAIL,
                                            TIP_TOKEN,
                                            COD_UNIVEND,
                                            COD_USUCADA,
                                            DAT_VALIDADE) 
                                            VALUES 
                                            ('".$arraydadosaut['4']."', 
                                            '".date('Y-m-d H:i:s')."', 
                                            '".$senha."', 
                                            '".fnAcentos($arrayjson['nome'])."', 
                                           '".fnlimpaCPF($arrayjson['cpf'])."', 
                                            '".($celular != '' ? fnlimpaCPF($celular) : fnlimpaCPF($whatsapp)) ."',
                                            '".$arrayjson['email']."',
                                            '".$arrayjson['tipoGeracao']."',
                                            '".$arraydadosaut['2']."',
                                            '".$user[COD_USUARIO]."',
                                            NOW() + INTERVAL ".$temporeenvio." MINUTE)";
        $regera=mysqli_query($conexaotmp,$sqlinsert);       
        if(!$regera)
        {
            http_response_code(400);
            $erroinformation='{"errors": [
                        {
                         "message": "Por favor verifique o nome ou numero do envio.",
                         "coderro": "400"
                         }
                    ]
            }'; 
            $erroinformation=json_decode(json_encode($erroinformation));
            echo $erroinformation;
            exit(); 
        }	
        $COD_TOKEN= mysqli_insert_id($conexaotmp);
    }
    //fim da gravação do reenvio
}

//=================================================================
//capturando dominio inicial
$sqldominio="SELECT DES_DOMINIO,COD_DOMINIO from site_extrato WHERE cod_empresa='".$arraydadosaut['4']."'";
$rsdominio=mysqli_fetch_assoc(mysqli_query($conexaotmp,$sqldominio));
$DES_DOMINIO=$rsdominio['DES_DOMINIO'];
$COD_DOMINIO=$rsdominio['COD_DOMINIO'];

//envio sms
if($celular!='')
{
    //aqui vai ser para pegar a chave SMS para verificar qual campo vai ser preenchido para envio da comunicação
    if($arrayjson['tipoGeracao']=='1'){
        unset($campanhaSMS);
        $campanhaSMS="SELECT 	G.COD_EMPRESA,
                                G.COD_CAMPANHA,
                                G.LOG_STATUS,
                                G.LOG_PROCESS,
                                G.HOR_ESPECIF,
                                T.DES_TEMPLATE,
                                C.LOG_ATIVO,
                                C.DAT_INI,
                                C.DAT_FIM,
                                C.DES_CAMPANHA,
                                C.LOG_PROCESSA_SMS
                                FROM gatilho_sms G
                                INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
                                WHERE G.cod_empresa='".$arraydadosaut['4']."' AND G.TIP_GATILHO='tokenCad';";
    }
    if($arrayjson['tipoGeracao']=='2'){
        unset($campanhaSMS);
        $campanhaSMS="SELECT 	G.COD_EMPRESA,
                                G.COD_CAMPANHA,
                                G.LOG_STATUS,
                                G.LOG_PROCESS,
                                G.HOR_ESPECIF,
                                T.DES_TEMPLATE,
                                C.LOG_ATIVO,
                                C.DAT_INI,
                                C.DAT_FIM,
                                C.DES_CAMPANHA,
                                C.LOG_PROCESSA_SMS,
                                FROM gatilho_sms G
                                INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN mensagem_sms M ON M.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN template_sms T ON T.COD_TEMPLATE=M.COD_TEMPLATE_SMS
                                WHERE G.cod_empresa='".$arraydadosaut['4']."' AND G.TIP_GATILHO='tokenVen';";
    }
    $rwcampanhaSMS=mysqli_query($conexaotmp,$campanhaSMS);			
    $rscampanhaSMS=mysqli_fetch_assoc($rwcampanhaSMS);
    if($rscampanhaSMS[LOG_ATIVO] != "" )
    {
        $naoCampanha='1';
        if($rscampanhaSMS[LOG_ATIVO]=='S')
        {			
            if($celular=='')
            {
                http_response_code(400);
                 $erroinformation='{"errors": [
                                                 {
                                                 "message": "Campo Celular precisam ser preenchidos!",
                                                 "coderro": "400"
                                                 }
                                               ]
                 }'; 
                 $erroinformation=json_decode(json_encode($erroinformation));
                 echo $erroinformation;
                 exit(); 
            }
        }
        //===================================================================		
        //foi mudado dia 26/05/2021 codigo 1 passou a ser 2 
        //foi mudado dia 28/05/2021 voltou a ser o primrito movimento. 

        //alterar o variavel peolo texto
         $TEXTOENVIO=str_replace('<#TOKEN>', $senha, $rscampanhaSMS[DES_TEMPLATE]);
        // $TEXTOENVIO=str_replace('<#LINKTOKEN>', 'http://'.$DES_DOMINIO.'.mais.cash/ativacao.do?id='.$COD_TOKEN, $TEXTOENVIO);
        if($COD_DOMINIO=='1')
        {	 
           $TEXTOENVIO=str_replace('<#LINKTOKEN>', 'https://'.$DES_DOMINIO.'.mais.cash/ativacao.do', $TEXTOENVIO);
        }
        if($COD_DOMINIO=='2')
        {	 
           $TEXTOENVIO=str_replace('<#LINKTOKEN>', 'https://'.$DES_DOMINIO.'.fidelidade.mk/ativacao.do', $TEXTOENVIO);
        }				 
        $NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($arrayjson['nome']))));                                 
        $TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $TEXTOENVIO);
        //===================================================
         include '../../_system/func_nexux/func_transacional.php';
        //senha para autenticar o envio
        $sqlsenhasms = "SELECT * FROM senhas_parceiro apar
                         INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                         WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU in ('17','19','22',23,24) AND apar.LOG_ATIVO='S'
                         AND apar.COD_EMPRESA = '".$arraydadosaut['4']."'";
        $rssenhasms=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlsenhasms));
        $id_campanha1=date('His');
        //verificar sem tem saldo disponivel
        $sqlcomdebt= "SELECT 
                        pedido.TIP_LANCAMENTO 
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
                           pedido.COD_EMPRESA =".$arraydadosaut['4']." AND
                            PAG_CONFIRMACAO='S' and
                            canal.COD_TPCOM=2
                            AND pedido.QTD_SALDO_ATUAL > 0  AND 
                            pedido.DAT_VALIDADE IS NOT NULL and
                            pedido.TIP_LANCAMENTO ='C' 
                        GROUP BY  pedido.TIP_LANCAMENTO	            
                   ORDER BY pedido.TIP_LANCAMENTO desc";
        $rwarraysql=mysqli_query($connAdm->connAdm(), $sqlcomdebt);
        if($rwarraysql->num_rows <= 0)
        {
           $saldorestante = '0';  
        }else{    
            while($rssaldo= mysqli_fetch_assoc($rwarraysql)) 
            { 
                 $saldorestante = $rssaldo['QTD_SALDO_ATUAL'];
            } 
        }
       //===========
       //novo envio de funcão

    $nom_camp_msg=$rscampanhaSMS[COD_CAMPANHA].'||'.$rscampanhaSMS[COD_EMPRESA].'||0';
    $nom_camp_envio=$rscampanhaSMS[DES_CAMPANHA].'||'.$rscampanhaSMS[COD_CAMPANHA].'||'.$rscampanhaSMS[COD_EMPRESA];
    if($rssenhasms['COD_PARCOMU']=='17')
    {
        $CLIE_SMS_L[]=array("numero"=>$celular,
                            "mensagem"=>$TEXTOENVIO,                   
                            "DataAgendamento"=>''.date('Y-m-d H:i:s').'',
                            "Codigo_cliente"=>"$nom_camp_msg"
                             );

         $testefast=EnvioSms_fast($rssenhasms["DES_AUTHKEY"],$nom_camp_envio,json_encode($CLIE_SMS_L));
         $cod_erro_nexux=$testefast[Resultado][CodigoResultado];
         if($cod_erro_nexux=='0')
         {
             $CHAVE_GERAL=$testefast[Resultado][Chave];
             $CHAVE_CLIENTE=$testefast[Mensagens][0][UniqueID];

         } 
         $msgenvio=$testefast[Resultado][Mensagem];
    }else{
       /* $CLIE_SMS_L[]=array("from"=>$rssenhasms['DES_CLIEXT'],
                            "to" =>'+55'.$celular, 
                            "mensagem"=>$TEXTOENVIO,                   
                            "DataAgendamento"=> date('Y-m-d H:i:s'),
                            "Codigointerno"=> base64_encode($nom_camp_msg)
                           );  
        $base64= base64_encode($rssenhasms[DES_USUARIO].':'.$rssenhasms[DES_AUTHKEY]);
        $responsetwilo=sms_twilo($base64,$CLIE_SMS_L,$rssenhasms[DES_USUARIO],$rssenhasms[DES_AUTHKEY]);
        $cod_erro_nexux='0';
        if($cod_erro_nexux=='0')
        {
            $CHAVE_GERAL=$responsetwilo[0]['account_sid'];
            $CHAVE_CLIENTE=$responsetwilo[0]['sid'];

        }   
         $msgenvio=$responsetwilo[0][status];    

        $codinternoParcomu=22;  */
        if($rscampanhaSMS['LOG_PROCESSA_SMS']=='S')
        { 

           // if($cpf=='01734200014')
           // {
                if($rssenhasms['COD_PARCOMU']==22)
                {    
                    $rssenhasms['COD_LISTA']='basic '. base64_encode($rssenhasms[DES_USUARIO].':'.$rssenhasms[DES_AUTHKEY]);
                }
                //nova função de encio 
                $array=array('PROVEDOR'=>$rssenhasms['COD_PARCOMU'],
                                'URL'=>$rssenhasms['URL_API'],
                                'METHOD'=>'POST',
                                'Authorization'=>$rssenhasms['COD_LISTA'],
                                'Usuario'=>$rssenhasms['DES_CLIEXT'],
                                'COD_EMPRESA'=>$dadosLogin['idcliente'],    
                                'SEND'=>ARRAY(
                                                ARRAY(
                                                    'Body'=>$TEXTOENVIO,
                                                    'From'=>$rssenhasms['DES_CLIEXT'],
                                                    'To'=>'+55'.$celular,
                                                    'Codigointerno'=>0,
                                                    'COD_CLIENTE'=>0
                                                    )       
                                            )
                        );
                $responsetwilo=fnenviosms($array);
         //  }
            /*else{

            $CLIE_SMS_L[]=array("from"=>$rssenhasms['DES_CLIEXT'],
                                "to" =>'+55'.$celular, 
                                "mensagem"=>$TEXTOENVIO,                   
                                "DataAgendamento"=> date('Y-m-d H:i:s'),
                                "Codigointerno"=> base64_encode($nom_camp_msg)
                               );  
            $base64= base64_encode($rssenhasms[DES_USUARIO].':'.$rssenhasms[DES_AUTHKEY]);

            $responsetwilo=sms_twilo($base64,$CLIE_SMS_L,$rssenhasms[DES_USUARIO],$rssenhasms[DES_AUTHKEY]);
            }   */                                           
            $cod_erro_nexux='0';
            if($cod_erro_nexux=='0')
            {
                $CHAVE_GERAL=$responsetwilo[0]['account_sid'];
                $CHAVE_CLIENTE=$responsetwilo[0]['sid'];

            }   
             $msgenvio=$responsetwilo[0][status];    

           $codinternoParcomu=22;
        } 

    }
    $jsonputo=json_encode($testefast);
    //$enviosmsmsg[infomacoes][0]=='SMS enviado' || 
    if($cod_erro_nexux=='0')
    {	
        //==========envio de debitos				
        $arraydebitos=array('quantidadeEmailenvio'=>'1',
                            'COD_EMPRESA'=>$arraydadosaut['4'],
                            'PERMITENEGATIVO'=>'N',
                            'COD_CANALCOM'=>'2',
                            'CONFIRMACAO'=>'S',
                            'COD_CAMPANHA'=>$rscampanhaSMS['COD_CAMPANHA'],    
                            'LOG_TESTE'=> 'N',
                            'DAT_CADASTR'=> date('Y-m-d H:i:s'),
                            'CONNADM'=>$connAdm->connAdm()
                            ); 
        $retornoDeb=FnDebitosWS($arraydebitos);
    }
    $sqlinsertlog="INSERT INTO rel_geratoken (TOKEN, COD_EMPRESA, TIP_ENVIO,COD_GERATOKEN,DES_MSG,DES_MSG_ENVIADA,DES_JSON,CHAVE_GERAL,CHAVE_CLIENTE) 
                VALUES 
                ('".$senha."', '".$arraydadosaut['4']."', '1',$COD_TOKEN,'".addslashes($msgenvio)."','".addslashes($TEXTOENVIO)."','".addslashes($jsonputo)."','".$CHAVE_GERAL."','".$CHAVE_CLIENTE."');";
    mysqli_query($conexaotmp,$sqlinsertlog);	
    unset($enviosmsmsg);
    
    }else{
        $naoCampanha='2';
    }
}
//envio whatsapp
if($whatsapp!='')
{
    
    //aqui vai ser para pegar a chave SMS para verificar qual campo vai ser preenchido para envio da comunicação
    if($arrayjson['tipoGeracao']=='1'){
        unset($campanhaSMS);
        $campanhaSMS="SELECT 	G.COD_EMPRESA,
                                G.COD_CAMPANHA,
                                G.LOG_STATUS,
                                G.LOG_PROCESS,
                                G.HOR_ESPECIF,
                                T.DES_TEMPLATE,
                                C.LOG_ATIVO,
                                C.DAT_INI,
                                C.DAT_FIM,
                                C.DES_CAMPANHA	
                                FROM gatilho_whatsapp G
                                INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN mensagem_whatsapp M ON M.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN template_whatsapp T ON T.COD_TEMPLATE=M.COD_TEMPLATE_whatsapp
                                WHERE G.cod_empresa='".$arraydadosaut['4']."' AND G.TIP_GATILHO='tokenCad';";
    }
    if($arrayjson['tipoGeracao']=='2'){
        unset($campanhaSMS);
        $campanhaSMS="SELECT 	G.COD_EMPRESA,
                                G.COD_CAMPANHA,
                                G.LOG_STATUS,
                                G.LOG_PROCESS,
                                G.HOR_ESPECIF,
                                T.DES_TEMPLATE,
                                C.LOG_ATIVO,
                                C.DAT_INI,
                                C.DAT_FIM,
                                C.DES_CAMPANHA
                                FROM gatilho_whatsapp G
                                INNER JOIN campanha C ON C.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN mensagem_whatsapp M ON M.COD_CAMPANHA=G.COD_CAMPANHA
                                INNER JOIN template_whatsapp T ON T.COD_TEMPLATE=M.COD_TEMPLATE_whatsapp
                                WHERE G.cod_empresa='".$arraydadosaut['4']."' AND G.TIP_GATILHO='tokenVen';";
    }
    $rwcampanhaSMS=mysqli_query($conexaotmp,$campanhaSMS);			
    $rscampanhaSMS=mysqli_fetch_assoc($rwcampanhaSMS);
    if($rscampanhaSMS[LOG_ATIVO] != "" )
    {
        $naoCampanha='1';
        if($rscampanhaSMS[LOG_ATIVO]=='S')
        {			
            if($whatsapp=='')
            {
                http_response_code(400);
                $erroinformation='{"errors": [
                                                 {
                                                 "message": "Campo Celular precisam ser preenchidos!",
                                                 "coderro": "400"
                                                 }
                                               ]
                 }'; 
                 $erroinformation=json_decode(json_encode($erroinformation));
                 echo $erroinformation;
                 exit(); 
            }
        }
        //===================================================================		
        //foi mudado dia 26/05/2021 codigo 1 passou a ser 2 
        //foi mudado dia 28/05/2021 voltou a ser o primrito movimento. 

        //alterar o variavel peolo texto
         $TEXTOENVIO=str_replace('<#TOKEN>', $senha, $rscampanhaSMS[DES_TEMPLATE]);
        // $TEXTOENVIO=str_replace('<#LINKTOKEN>', 'http://'.$DES_DOMINIO.'.mais.cash/ativacao.do?id='.$COD_TOKEN, $TEXTOENVIO);
        if($COD_DOMINIO=='1')
        {	 
           $TEXTOENVIO=str_replace('<#LINKTOKEN>', 'https://'.$DES_DOMINIO.'.mais.cash/ativacao.do', $TEXTOENVIO);
        }
        if($COD_DOMINIO=='2')
        {	 
           $TEXTOENVIO=str_replace('<#LINKTOKEN>', 'https://'.$DES_DOMINIO.'.fidelidade.mk/ativacao.do', $TEXTOENVIO);
        }				 
        $NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($arrayjson['nome']))));                                 
        $TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $TEXTOENVIO);
        //===================================================
        //include_once '../../_system/whatsapp/wsp.php';
        include_once '../../_system/whatsapp/wstAdorai.php';
        //senha para autenticar o envio
        
            // AND COD_UNIVEND
         //   print_r($arraydadosaut);
        if($arraydadosaut[2]!=0)
        {
           $unidade= 'AND COD_UNIVEND='.$arraydadosaut[2];
        }    
            
        $sqlsenhasms = "
                        SELECT SENHAS_WHATSAPP.*
                        from SENHAS_WHATSAPP
                        WHERE COD_EMPRESA = '".$arraydadosaut['4']."'$unidade
                        LIMIT 1";
        $rssenhasms=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),$sqlsenhasms));
        if(!isset($rssenhasms))
        {
            http_response_code(400);
                $erroinformation='{"errors": [
                                                 {
                                                 "message": "chave invalida!",
                                                 "coderro": "400"
                                                 }
                                               ]
                 }'; 
                 $erroinformation=json_decode(json_encode($erroinformation));
                 echo $erroinformation;
                 exit(); 
        }    
            
        $id_campanha1=date('His');
        //verificar sem tem saldo disponivel
        $sqlcomdebt= "SELECT 
                        pedido.TIP_LANCAMENTO 
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
                           pedido.COD_EMPRESA =".$arraydadosaut['4']." AND
                            PAG_CONFIRMACAO='S' and
                            canal.COD_TPCOM=3
                            AND pedido.QTD_SALDO_ATUAL > 0  AND 
                            pedido.DAT_VALIDADE IS NOT NULL and
                            pedido.TIP_LANCAMENTO ='C' 
                        GROUP BY  pedido.TIP_LANCAMENTO	            
                   ORDER BY pedido.TIP_LANCAMENTO desc";
        $rwarraysql=mysqli_query($connAdm->connAdm(), $sqlcomdebt);
        if($rwarraysql->num_rows <= 0)
        {
           $saldorestante = '0';  
        }else{    
            while($rssaldo= mysqli_fetch_assoc($rwarraysql)) 
            { 
                 $saldorestante = $rssaldo['QTD_SALDO_ATUAL'];
            } 
        }
       //===========
       //novo envio de funcão

    $nom_camp_msg=$rscampanhaSMS[COD_CAMPANHA].'||'.$rscampanhaSMS[COD_EMPRESA].'||0';
    $nom_camp_envio=$rscampanhaSMS[DES_CAMPANHA].'||'.$rscampanhaSMS[COD_CAMPANHA].'||'.$rscampanhaSMS[COD_EMPRESA];
    /*$CLIE_WHATSAPP_L[]=array("type"=> "text",
                            "message"=> "$TEXTOENVIO",                   
                            "token"=> "$rssenhasms[DES_TOKEN]",               
                            "session"=> "$session",               
                            "number"=> $whatsapp
	                            );*/
    //$retorno = FnEnvioMULT($session,$des_token,$CLIE_WHATSAPP_L);
                                    
    $msgsbtr=  str_replace(["\r\n", "\r", "\n"], '\n', $TEXTOENVIO);  
    
    $retorno=FnsendText($rssenhasms['NOM_SESSAO'], $rssenhasms['DES_AUTHKEY'], '+55'.$whatsapp, $msgsbtr, 3);

    $CHAVE_GERAL='0';
    $CHAVE_CLIENTE='0';
    $jsonputo=json_encode($retorno);
    //==========envio de debitos				
    $arraydebitos=array('quantidadeEmailenvio'=>'1',
                        'COD_EMPRESA'=>$arraydadosaut['4'],
                        'PERMITENEGATIVO'=>'N',
                        'COD_CANALCOM'=>'20',
                        'CONFIRMACAO'=>'S',
                        'COD_CAMPANHA'=>$rscampanhaSMS['COD_CAMPANHA'],    
                        'LOG_TESTE'=> 'N',
                        'DAT_CADASTR'=> date('Y-m-d H:i:s'),
                        'CONNADM'=>$connAdm->connAdm()
                        ); 
    $retornoDeb=FnDebitosWS($arraydebitos);
    $sqlinsertlog="INSERT INTO rel_geratoken (TOKEN, COD_EMPRESA, TIP_ENVIO,COD_GERATOKEN,DES_MSG,DES_MSG_ENVIADA,DES_JSON,CHAVE_GERAL,CHAVE_CLIENTE) 
                VALUES 
                ('".$senha."', '".$arraydadosaut['4']."', '1',$COD_TOKEN,'OK','".addslashes($TEXTOENVIO)."','".addslashes($jsonputo)."','CHAVE_GERAL','CHAVE_CLIENTE');";
    mysqli_query($conexaotmp,$sqlinsertlog);	
    unset($enviosmsmsg);
    
    }else{
        $naoCampanha='2';
    }
}
if($naoCampanha=='2')
{
    http_response_code(400);
    $erroinformation='{"errors": [
                                     {
                                     "message": "Não Tem campanha para esse tipo de envio configurada!",
                                     "coderro": "400"
                                     }
                                   ]
     }'; 
     $erroinformation=json_decode(json_encode($erroinformation));
     echo $erroinformation;
     exit(); 
}
//alterando o limite de envio
if($rwlogtoken[NUM_CELULAR_COMPARAR]==fnlimpaCPF($celular))
{
    $qtdreenvio=$rwlogtoken[QTD_REENVIO]+1;
    $qtdreenvioCONTROLE=$rwlogtoken[QTD_REENVIO_CONTROLE]+1;
    
}elseif($rwlogtoken[NUM_CELULAR_COMPARAR]==fnlimpaCPF($whatsapp)){
    
    $qtdreenvio=$rwlogtoken[QTD_REENVIO]+1;
    $qtdreenvioCONTROLE=$rwlogtoken[QTD_REENVIO_CONTROLE]+1;
    
}else{
        $qtdreenvio=1;
        $qtdreenvioCONTROLE=1;
}
$sqlalterlimit="UPDATE geratoken SET DAT_VALIDADE=NOW() + INTERVAL ".$temporeenvio." MINUTE, QTD_REENVIO='".$qtdreenvio."',QTD_REENVIO_CONTROLE='".$qtdreenvioCONTROLE."' 
                 WHERE  COD_TOKEN='".$COD_TOKEN."' and COD_EMPRESA='".$arraydadosaut['4']."';";
mysqli_query($conexaotmp,$sqlalterlimit);
//gravando registro para pegar  o retorno do sms
if($arrayjson['tipoGeracao']=='1')
{
    $sqlInsertRel= "INSERT INTO SMS_LISTA_RET(
                                                COD_EMPRESA,
                                                COD_CAMPANHA,                                                                               
                                                NOM_CLIENTE,
                                                COD_UNIVEND,
                                                NUM_CELULAR,                                                                               
                                                STATUS_ENVIO,
                                                ID_DISPARO,
                                                DES_MSG_ENVIADA	,
                                                CHAVE_GERAL,
                                                CHAVE_CLIENTE,
                                                DES_STATUS,
                                                idContatosMailing                                                                               
                                                )values
                                        ('".$arraydadosaut['4']."',
                                         '".$rscampanhaSMS[COD_CAMPANHA]."',       
                                         '".$NOM_CLIENTE[0]."',
                                         '".$arraydadosaut['2']."',
                                        '".($celular != '' ? fnlimpaCPF($celular) : fnlimpaCPF($whatsapp)) ."',
                                         'S',
                                         '".date('Ymd')."',
                                         '".$TEXTOENVIO."',
                                         '".$CHAVE_GERAL."',
                                         '".$CHAVE_CLIENTE."',
                                         '".$msgenvio."' ,
                                         '".$rssenhasms['COD_PARCOMU']."'    
                                        ) ; ";
     mysqli_query($conexaotmp, $sqlInsertRel);
}
http_response_code(200);
$erroinformation1['errors']=array("message"=> "OK","coderro"=> "39");
$erroinformation=json_encode($erroinformation1,true);
echo $erroinformation;
exit(); 
/*
 Array
(
    [tipoGeracao] => 1
    [nome] => diogo
    [cpf] => 01734200014
    [celular] => 48996243831
    [email] => diogo@hotmail.com
    [whatsapp] => 48996243831
)

 */