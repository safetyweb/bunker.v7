<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../../_system/func_nexux/func_transacional.php';

if($_SERVER[REQUEST_METHOD]!='POST')
{
        http_response_code(400);
        $erroinformation='{"errors": [
                                            {
                                             "msgerro": "Esse metodo so aceita POST",                                
                                             "coderro": "400"
                                            }
                                         ]
                              }';    
        echo $erroinformation; 
        exit();
}  

$temporeenvio='10';
/*function sms_twilo($base64,$dadosenvio,$username,$password)
{
    
    $url = 'https://api.twilio.com/2010-04-01/Accounts/'.$username.'/Messages.json';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);    
    foreach ($dadosenvio as  $value){
         $data = array(
                        'To' => $value['to'],
                        'From' => $value['from'],
                        'Body' => $value['mensagem'],
                        'StatusCallback' => 'http://externo.bunker.mk/twilo/twl?ID='.$value[Codigointerno]
                        );
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, $username . ':' . $password);
    
    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $dadostwiloret =json_decode($response,true);
        $dadostwiloret['COD_CLIENTE'] =$value['COD_CLIENTE'];
        $ret[] = $dadostwiloret;
       // $ret[]= json_decode($response,true);
    }
      curl_close($ch);
      return $ret;
}
 * 
 */
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
    $retorno .= $caracteres[$rand-1];
    }
    return $retorno;
}

include '../../_system/_functionsMain.php';
$dadosenvio = json_decode(file_get_contents("php://input"),true);

$passmarka= getallheaders();
if(!array_key_exists('authorizationCode', $passmarka))
{
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "locationType": "body"
                                    },
                                ]
                      }';    
     echo $erroinformation;
     exit();  
}    
$autoriz=fndecode(base64_decode($passmarka[authorizationCode]));
//cod_empresa
$autorizlimpo=fnLimpaCampo($autoriz);
if(!$autorizlimpo)
{

   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "locationType": "body"
                                    },
                                ]
                      }';    
     echo $erroinformation;
     exit();  
}
//conn adm
$connadm=$connAdm->connAdm();
//abrindo conexão temporaria
$conn = connTemp($autorizlimpo, '');
// =========================limpa caractere do celular e cpf
$cpf=preg_replace("/[^0-9]/", "", $dadosenvio[Cpf]);
$Unidade= $dadosenvio['Unidade'];
//========verifica se o telefone esta preenchido
if(empty($dadosenvio[Token]))
    {    
    if(!empty($dadosenvio[Telefone]) || !empty($dadosenvio[Telefone]))
    {  
        $celular=preg_replace("/[^0-9]/", "", $dadosenvio[Telefone]);    
        // verifica se tenha conta de sms para enviar o token
            $sqlsenhasms = "SELECT * FROM senhas_parceiro apar
                            INNER JOIN parceiro_comunicacao par ON par.COD_PARCOMU=apar.COD_PARCOMU
                            WHERE par.COD_TPCOM='2' AND apar.COD_PARCOMU in ('17','22','23','24') AND apar.LOG_ATIVO='S'
                            AND apar.COD_EMPRESA = '$autorizlimpo'";
            $rssenhasms=mysqli_fetch_assoc(mysqli_query($connadm,$sqlsenhasms));
            if(empty($rssenhasms))
            {
                    http_response_code(400);
                    $erroinformation='{"errors": [
                                                    {
                                                     "message": "Voce não Tem permisão para enviar Token entre em contato com Marka e solicite uma senha de envio!",
                                                     "locationType": "body"
                                                    },
                                                ]
                                      }';    
                     echo $erroinformation;
                  exit();  
            }
           //carregar variavel com a senha
           $DES_AUTHKEY=$rssenhasms["DES_AUTHKEY"];

           //verificar se tem saldo para a comunicação de sms token
           //verificar sem tem saldo disponivel
            $sqlcomdebt="SELECT 
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
                                       pedido.COD_EMPRESA ='$autorizlimpo' AND
                                        PAG_CONFIRMACAO='S' and
                                        canal.COD_TPCOM=2
                                        AND pedido.QTD_SALDO_ATUAL > 0  AND 
                                        pedido.DAT_VALIDADE IS NOT NULL and
                                        pedido.TIP_LANCAMENTO ='C' 
                                    GROUP BY  pedido.TIP_LANCAMENTO	            
                               ORDER BY pedido.TIP_LANCAMENTO desc ";
            $rwarraysql=mysqli_query($connadm, $sqlcomdebt);
            if($rwarraysql->num_rows <= 0)
            {
                    http_response_code(400);
                    $erroinformation='{"errors": [
                                                    {
                                                     "message": "Saldo insuficiente",
                                                     "locationType": "body"
                                                    },
                                                ]
                                      }';    
                     echo $erroinformation;
                    exit();    
            }else{    
                while($rssaldo= mysqli_fetch_assoc($rwarraysql)) 
                { 
                     $DebSaldo = $rssaldo['QTD_SALDO_ATUAL'];
                } 
                if($DebSaldo <= '1')
                {
                    http_response_code(400);
                    $erroinformation='{"errors": [
                                                    {
                                                     "message": "Saldo insuficiente",
                                                     "locationType": "body"
                                                    },
                                                ]
                                      }';    
                     echo $erroinformation;
                  exit();   
                }
            } 
            
            $TIP_GATILHO='senhaApp';
            if($Unidade!='')
            {
             $TIP_GATILHO='unidadeApp';    
            }
           //verificar campanha de sms  se existe ou não
           $campanha="SELECT    G.COD_EMPRESA,
                                G.COD_CAMPANHA,
                                G.LOG_STATUS,
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
                                    G.cod_empresa='$autorizlimpo' 
                                    AND G.TIP_GATILHO='$TIP_GATILHO'
                                    AND C.LOG_ATIVO='S'
                                    AND C.LOG_PROCESSA_SMS='S';";
           $rsCampanha=mysqli_fetch_assoc(mysqli_query($conn,$campanha));
            if(empty($rsCampanha))
            {
                http_response_code(400);
                $erroinformation='{"errors": [
                                                {
                                                 "message": "Voce não possue campanha ativa para envio de token recuperação senha!",
                                                 "locationType": "body"
                                                },
                                            ]
                                  }';    
                echo $erroinformation;
                exit();  
            }
            if($Unidade!='')
            {    
                //validar a quantidade gerada por cliente
                $verificaqtd="SELECT DES_TOKEN FROM tokenapp 
                                                    WHERE LOG_USADO =1 AND 
                                                                    COD_EXCLUSA=0 AND 
                                                                    TIP_TOKEN=1 AND 	
                                                                    NUM_CGCECPF='$cpf' AND
                                                                    COD_EMPRESA=$autorizlimpo AND 
                                                                    NUM_CELULAR='$celular' order by COD_TOKEN DESC LIMIT 0,3";
                $limitenvio=mysqli_query($conn,$verificaqtd);
                if($limitenvio->num_rows >= 3)
                {
                     http_response_code(400);
                    $erroinformation='{"errors": [
                                                    {
                                                     "message": "Por favor aguarda '.$temporeenvio.' min para refazer o envio ou tente Outro metodo para restaurar a senha!",
                                                     "locationType": "body"
                                                    },
                                                ]
                                      }';    
                    echo $erroinformation;
                   exit();  
                }    
            }
            
            //verificar a validade do token ja gerado para  o mesmo cliene
           $SQLVTOKEN="SELECT * FROM tokenapp 
                                                WHERE LOG_USADO =1 AND 
                                                                COD_EXCLUSA=0 AND 
                                                                TIP_TOKEN=1 AND 	
                                                                NUM_CGCECPF='$cpf' AND
                                                                COD_EMPRESA=$autorizlimpo AND 
                                                                NUM_CELULAR='$celular' order by COD_TOKEN DESC LIMIT 1 ";
            $rsVTOKEN=mysqli_fetch_assoc(mysqli_query($conn,$SQLVTOKEN));
            if($Unidade==$rsVTOKEN['COD_UNIVEND'])
            {    
                if($rsVTOKEN[DAT_VALIDADE] > date('Y-m-d H:i:s')){

                    http_response_code(400);
                    $erroinformation='{"errors": [
                                                    {
                                                     "message": "Por favor aguarda '.$temporeenvio.' min para refazer o envio ou tente Outro metodo para restaurar a senha!",
                                                     "locationType": "body"
                                                    },
                                                ]
                                      }';    
                    echo $erroinformation;
                   exit();  
                }
            }
            //capturar os tipos de senhas/tokens gerados
            $sqlempres="SELECT TIP_SENHA,MIN_SENHA FROM empresas WHERE cod_empresa=$autorizlimpo";
            $rsempres=mysqli_fetch_assoc(mysqli_query($conn,$sqlempres));
            if($rsempres[TIP_SENHA]=='1'){$TIP_TOKEN=true;}else{$TIP_TOKEN=false;}  
             if($autorizlimpo=='19'){$TIP_TOKEN=false;}
            if($rsempres[MIN_SENHA]==' '){$QTD_CHARTKN='6';}else{$QTD_CHARTKN=$rsempres[MIN_SENHA];}
            // inicio geração de token 
            //verificar se o token ja foi utilizado e gerar um novo
            do {

               $senha = fngeraSenha($QTD_CHARTKN, $TIP_TOKEN, true, true);
               $sqlTokenvl = "SELECT 1 FROM tokenapp WHERE 
                                                         COD_EXCLUSA=0 
                                                        and COD_EMPRESA = '$autorizlimpo' 
                                                        AND NUM_CGCECPF='$cpf'     
                                                        AND DES_TOKEN = '$senha'";
                $arrayTokenvl = mysqli_query($conn,$sqlTokenvl);
            } while ($arrayTokenvl->num_rows > 0);
            //consultando cliente para caputura alguma informações para a geração de token

            $buscacli="SELECT COD_CLIENTE,NOM_CLIENTE,COD_UNIVEND,COD_USUCADA FROM clientes WHERE cod_empresa=$autorizlimpo AND num_cgcecpf='$cpf' ";
            $rsbuscacli=mysqli_fetch_assoc(mysqli_query($conn,$buscacli));
            if($rsbuscacli[COD_USUCADA]==''){$rsbuscacli[COD_USUCADA]='0';} 
             //inserindo registro na base de token/senhas
            $cod_univend=$rsbuscacli[COD_UNIVEND];
            if($Unidade!='')
            {
                $cod_univend= $Unidade;
            }    
            $sqlinsert1="INSERT INTO tokenapp (COD_CLIENTE,
                                                NOM_CLIENTE,
                                                COD_UNIVEND,
                                                COD_USUCADA,
                                                IP,
                                                COD_EMPRESA, 
                                                DAT_CADASTR, 
                                                DES_TOKEN, 
                                                NUM_CGCECPF, 
                                                NUM_CELULAR, 
                                                TIP_TOKEN,
                                                DAT_VALIDADE) 
                                                VALUES 
                                                (
                                                $rsbuscacli[COD_CLIENTE],
                                                '".$rsbuscacli[NOM_CLIENTE]."',
                                                $cod_univend,
                                                $rsbuscacli[COD_USUCADA],    
                                                '".$_SERVER['REMOTE_ADDR']."',
                                                '".$autorizlimpo."', 
                                                '".date('Y-m-d H:i:s')."', 
                                                '".$senha."', 
                                                '".$cpf."', 
                                                '".$celular."', 
                                                '1',
                                                NOW() + INTERVAL ".$temporeenvio." MINUTE )";

            $regera=mysqli_query($conn,$sqlinsert1);
            if(!$regera)
            {
                http_response_code(400);
                $erroinformation='{"errors": [
                                                {
                                                 "message": "Por favor verifique o nome ou numero do envio.",
                                                 "locationType": '.$sqlinsert1.'
                                                },
                                            ]
                                  }';    
                echo $erroinformation;
                exit();  
            }					
            $COD_TOKEN= mysqli_insert_id($conn);
            //composição da mensagem
            //alterar o variavel peolo texto
            $TEXTOENVIO=str_replace('<#TOKEN>', $senha, $rsCampanha[DES_TEMPLATE]);
            $NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($rsbuscacli[NOM_CLIENTE]))));                                 
            $TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $TEXTOENVIO);
            //inicio do envio para sms
           /* $nom_camp_msg=$rsCampanha[COD_CAMPANHA].'||'.$rsCampanha[COD_EMPRESA].'||'.$rsbuscacli[COD_CLIENTE];
            $nom_camp_envio=$rsCampanha[DES_CAMPANHA].'||'.$rsCampanha[COD_CAMPANHA].'||'.$rsCampanha[COD_EMPRESA];
            */
            /* $CLIE_SMS_L[]=array("from"=>$rssenhasms['DES_CLIEXT'],
                                "to" =>'+55'.$celular, 
                                "mensagem"=>$TEXTOENVIO,                   
                                "DataAgendamento"=> "data",
                                "Codigointerno"=> base64_encode($nom_camp_msg),
                                "COD_CLIENTE"=>$rsbuscacli[COD_CLIENTE]
                               );  
            $base64= base64_encode($rssenhasms[DES_USUARIO].':'.$rssenhasms[DES_AUTHKEY]);
            $responsetwilo=sms_twilo($base64,$CLIE_SMS_L,$rssenhasms[DES_USUARIO],$rssenhasms[DES_AUTHKEY]);
            */
            
            if($rssenhasms['COD_PARCOMU']==22)
            {    
                $rssenhasms['COD_LISTAEXT']='basic '. base64_encode($rssenhasms[DES_USUARIO].':'.$rssenhasms[DES_AUTHKEY]);
            }
            //nova função de encio 
            $array=array('PROVEDOR'=>$rssenhasms['COD_PARCOMU'],
                            'URL'=>$rssenhasms['URL_API'],
                            'METHOD'=>'POST',
                            'Authorization'=>$rssenhasms['COD_LISTAEXT'],
                            'Usuario'=>$rssenhasms['DES_CLIEXT'],
                            'COD_EMPRESA'=>$rsCampanha['COD_EMPRESA'],    
                            'SEND'=>ARRAY(
                                            ARRAY(
                                                'Body'=>$TEXTOENVIO,
                                                'From'=>$rssenhasms['DES_CLIEXT'],
                                                'To'=>'+55'.$celular,
                                                'Codigointerno'=>0,
                                                'COD_CLIENTE'=>$rsbuscacli[COD_CLIENTE]
                                                )       
                                        )
                    );
          
            $responsetwilo=fnenviosms($array);
            
            $CHAVE_GERAL=$responsetwilo[0]['account_sid'];
            $CHAVE_CLIENTE=$responsetwilo[0]['sid'];
            $msgenvio=$responsetwilo[0][status];    
            
            //se o http_cod for 200 debitar e seguir com o processo
             $cod_erro_nexux='200';
             
            
            
            
                    //==========envio de debitos				
                    $arraydebitos=array('quantidadeEmailenvio'=>'1',
                                        'COD_EMPRESA'=>$autorizlimpo,
                                        'PERMITENEGATIVO'=>'N',
                                        'COD_CANALCOM'=>'2',
                                        'CONFIRMACAO'=>'S',
                                        'COD_CAMPANHA'=>$rsCampanha['COD_CAMPANHA'],    
                                        'LOG_TESTE'=> 'N',
                                        'DAT_CADASTR'=> date('Y-m-d H:i:s'),
                                        'CONNADM'=>$connAdm->connAdm()
                                        ); 
                    $retornoDeb=FnDebitos($arraydebitos);
                    //gravar na base de retorno para inserir na cantabilidade de relatorios.
                    $sqlInsertRel= "INSERT INTO SMS_LISTA_RET(
                                                                COD_EMPRESA,
                                                                COD_CAMPANHA,                                                                               
                                                                NOM_CLIENTE,
                                                                COD_CLIENTE,
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
                                                        ('".$autorizlimpo."',
                                                         '".$rsCampanha['COD_CAMPANHA']."',       
                                                         '".$rsbuscacli[NOM_CLIENTE]."',
                                                            $rsbuscacli[COD_CLIENTE],   
                                                         '".$cod_univend."',
                                                         '".$celular."',
                                                         'S',
                                                         '".date('Ymd')."',
                                                         '".$TEXTOENVIO."',
                                                         '".$CHAVE_GERAL."',
                                                         '".$CHAVE_CLIENTE."',
                                                         '".$msgenvio."'   ,
                                                         '".$rssenhasms['COD_PARCOMU']."'        
                                                        ) ; ";
                    mysqli_query($conn, $sqlInsertRel);   
           
    echo json_encode($responsetwilo);  
    
    }elseif(!empty($dadosenvio[Email]) || !empty($dadosenvio[Email])) {
        
         // inicio do envio por EMAIL
         
            //verificar a validade do token ja gerado para  o mesmo cliene
           $SQLVTOKEN="SELECT * FROM tokenapp 
                                    WHERE LOG_USADO =1 AND 
                                    COD_EXCLUSA=0 AND 
                                    TIP_TOKEN=2 AND 	
                                    NUM_CGCECPF='$cpf' AND
                                    COD_EMPRESA=$autorizlimpo AND 
                                    DES_EMAIL='$dadosenvio[Email]' order by COD_TOKEN DESC LIMIT 1 ";
            $rsVTOKEN=mysqli_fetch_assoc(mysqli_query($conn,$SQLVTOKEN));
            if($rsVTOKEN[DAT_VALIDADE] > date('Y-m-d H:i:s')){
                if($Unidade==$rsVTOKEN['COD_UNIVEND'])
                {    
                    http_response_code(400);
                    $erroinformation='{"errors": [
                                                    {
                                                     "message": "Por favor aguarda '.$temporeenvio.' min para refazer o envio ou tente Outro metodo para restaurar a senha!",
                                                     "locationType": "body"
                                                    },
                                                ]
                                      }';    
                    echo $erroinformation;
                    exit();  
                }   
            }

            //capturar os tipos de senhas/tokens gerados
            $sqlempres="SELECT TIP_SENHA,MIN_SENHA FROM empresas WHERE cod_empresa=$autorizlimpo";
            $rsempres=mysqli_fetch_assoc(mysqli_query($conn,$sqlempres));
            if($rsempres[TIP_SENHA]=='1'){$TIP_TOKEN=true;}else{$TIP_TOKEN=false;}  
            if($rsempres[MIN_SENHA]==' '){$QTD_CHARTKN='6';}else{$QTD_CHARTKN=$rsempres[MIN_SENHA];}
            // inicio geração de token 
            //verificar se o token ja foi utilizado e gerar um novo
            do {

               $senha = fngeraSenha($QTD_CHARTKN, $TIP_TOKEN, true, true);
               $sqlTokenvl = "SELECT 1 FROM tokenapp WHERE 
                                                         COD_EXCLUSA=0 
                                                        and COD_EMPRESA = '$autorizlimpo' 
                                                        AND DES_TOKEN = '$senha'";
                $arrayTokenvl = mysqli_query($conn,$sqlTokenvl);
            } while ($arrayTokenvl->num_rows > 0);
            //consultando cliente para caputura alguma informações para a geração de token

            $buscacli="SELECT COD_CLIENTE,NOM_CLIENTE,COD_UNIVEND,COD_USUCADA FROM clientes WHERE cod_empresa=$autorizlimpo AND num_cgcecpf='$cpf' ";
            $rsbuscacli=mysqli_fetch_assoc(mysqli_query($conn,$buscacli));
            if($rsbuscacli[COD_USUCADA]==''){$rsbuscacli[COD_USUCADA]='0';} 
             //inserindo registro na base de token/senhas
            $cod_univend=$rsbuscacli[COD_UNIVEND];
            if($Unidade!='')
            {
                $cod_univend= $Unidade;
            } 
            $sqlinsert1="INSERT INTO tokenapp (COD_CLIENTE,
                                                NOM_CLIENTE,
                                                COD_UNIVEND,
                                                COD_USUCADA,
                                                IP,
                                                COD_EMPRESA, 
                                                DAT_CADASTR, 
                                                DES_TOKEN, 
                                                NUM_CGCECPF, 
                                                DES_EMAIL, 
                                                TIP_TOKEN,
                                                DAT_VALIDADE) 
                                                VALUES 
                                                (
                                                $rsbuscacli[COD_CLIENTE],
                                                '".$rsbuscacli[NOM_CLIENTE]."',
                                                $cod_univend,
                                                $rsbuscacli[COD_USUCADA],    
                                                '".$_SERVER['REMOTE_ADDR']."',
                                                '".$autorizlimpo."', 
                                                '".date('Y-m-d H:i:s')."', 
                                                '".$senha."', 
                                                '".$cpf."', 
                                                '".$dadosenvio[Email]."', 
                                                '2',
                                                NOW() + INTERVAL ".$temporeenvio." MINUTE )";

            $regera=mysqli_query($conn,$sqlinsert1);
            if(!$regera)
            {
                http_response_code(400);
                $erroinformation='{"errors": [
                                                {
                                                 "message": "Por favor verifique o nome ou E-mail do envio.",
                                                 "locationType": '.$sqlinsert1.'
                                                },
                                            ]
                                  }';    
                echo $erroinformation;
                exit();  
            }					
            $COD_TOKEN= mysqli_insert_id($conn);
            //composição da mensagem
            //alterar o variavel peolo texto
            $rsCampanha[DES_TEMPLATE]='Rede Duque: use <#TOKEN> para redefinir sua senha. Nao informe esse codigo a ninguem.';
            $TEXTOENVIO=str_replace('<#TOKEN>', $senha, $rsCampanha[DES_TEMPLATE]);
            $NOM_CLIENTE=explode(" ", ucfirst(strtolower(fnAcentos($rsbuscacli[NOM_CLIENTE]))));                                 
            $TEXTOENVIO=str_replace('<#NOME>', $NOM_CLIENTE[0], $TEXTOENVIO);
            //inicio do envio para sms
            $nom_camp_msg=$rsCampanha[COD_CAMPANHA].'||'.$rsCampanha[COD_EMPRESA].'||'.$rsbuscacli[COD_CLIENTE];
            $nom_camp_envio=$rsCampanha[DES_CAMPANHA].'||'.$rsCampanha[COD_CAMPANHA].'||'.$rsCampanha[COD_EMPRESA];
         
            // include '../../_system/PHPMailer/class.phpmailer.php';
            include '../../externo/email/envio_sac.php';
            $emailDestino = array('email1'=>$dadosenvio[Email]);
            fnsacmail(  $emailDestino,
                         "Suporte Marka",
                         "<html>$TEXTOENVIO</html>",
                         "Token de authenticação F2A",
                         "Token de authenticação F2A",
                         $connAdm->connAdm(),
                         connTemp($autorizlimpo, ''),
                         $autorizlimpo);
                
            }   
    
}else{    
    //========VALIDAÇÂO de TOKEN===============================================================
    if($Unidade!='')
    {
        $COD_UNIVEND='COD_UNIVEND='.$Unidade.' AND';
    } 
    $sqlvalida="SELECT * FROM tokenapp WHERE
                                            cod_empresa=$autorizlimpo AND
                                            DES_TOKEN='$dadosenvio[Token]' AND 
                                            cod_exclusa=0 AND 
                                            $COD_UNIVEND
                                            LOG_USADO=1";
     $rwtoken=mysqli_query($conn, $sqlvalida); 
     if($rwtoken->num_rows<=0)
     {
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "message": "Token digitado não encontrado!",
                                         "locationType": "body"
                                        },
                                    ]
                          }';    
        echo $erroinformation;
        exit();
     }  
     
     $rstoken=mysqli_fetch_assoc($rwtoken);
     echo json_encode($rstoken);
}    