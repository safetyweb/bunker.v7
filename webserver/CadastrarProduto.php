<?php
//=================================================================== GetURLTktMania ====================================================================
//retorno dados

$server->wsdl->addComplexType(
    'CadastrarProdutoResult',
    'complexType',
    'struct',
    'sequence',
    '',
         array( 'msgerro' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'msgerro', 'type' => 'xsd:string'),
                'codigo' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'codigo', 'type' => 'xsd:string'),
                )
);

$server->wsdl->addComplexType(
    'Produto',
    'complexType',
    'struct',
    'sequence',
    '',
         array( 'nome' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'nome', 'type' => 'xsd:string'),
                'codigo' => array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'codigo', 'type' => 'xsd:string'),
                'grupo' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'grupo', 'type' => 'xsd:string'),
                'subgrupo' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'subgrupo', 'type' => 'xsd:string'),
                'marca' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'marca', 'type' => 'xsd:string'),
                'atributo1' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo1', 'type' => 'xsd:string'),
                'atributo2' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo2', 'type' => 'xsd:string'),
                'atributo3' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo3', 'type' => 'xsd:string'),
                'atributo4' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo4', 'type' => 'xsd:string'),
                'atributo5' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo5', 'type' => 'xsd:string'),
                'atributo6' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo6', 'type' => 'xsd:string'),
                'atributo7' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo7', 'type' => 'xsd:string'),
                'atributo8' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo8', 'type' => 'xsd:string'),
                'atributo9' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo9', 'type' => 'xsd:string'),
                'atributo10' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo10', 'type' => 'xsd:string'),
                'atributo11' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo11', 'type' => 'xsd:string'),
                'atributo12' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo12', 'type' => 'xsd:string'),
                'atributo13' =>array('minOccurs'=>'0', 'maxOccurs'=>'1', 'name' => 'atributo13', 'type' => 'xsd:string'),
                )
);



 $server->register('CadastrarProduto',
			array(
                              'Produto'=>'tns:Produto',
                              'dadosLogin'=>'tns:LoginInfo'
                              ),  //parameters
			array('CadastrarProdutoResult' => 'tns:CadastrarProdutoResult'),  //output
			 $ns,         						// namespace
                        "$ns/CadastrarProduto",     						// soapaction
                        'document',                         // style
                        'literal',                          // use
                        'EstornaVendaParcial'         		// documentation
                    );

function CadastrarProduto($CadastrarProduto,$dadosLogin) {
     include_once '../_system/Class_conn.php';
     include_once './func/function.php'; 
    // $msg=valida_campo_vazio($Estorno['id_vendapdv'],'id_vendapdv','string');
     //if(!empty($msg)){return array('return'=>array('msgerro' => $msg));}
     
    $sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$dadosLogin['login']."', '".fnEncode($dadosLogin['senha'])."','','','".$dadosLogin['idcliente']."','','')";
    $buscauser=mysqli_query($connAdm->connAdm(),$sql);
    $row = mysqli_fetch_assoc($buscauser);
    
    
    //verifica se a loja foi delabilitada
       $lojasql='SELECT LOG_ESTATUS FROM unidadevenda
                 WHERE COD_UNIVEND='.$dadosLogin['idloja'].' AND cod_empresa='.$dadosLogin['idcliente'];
        $lojars=mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(), $lojasql));
        if($lojars['LOG_ESTATUS']!='S')
        {
           return  array('CadastrarProdutoResult'=>array('msgerro'=>'LOJA DESABILITADA'));
           exit();   
        }  
    //conn user
    $connUser = new BD($row['IP'],$row['USUARIODB'],fnDecode($row['SENHADB']),$row['NOM_DATABASE']);
    
        if($row['LOG_ATIVO']!='S')
        {
                return array('CadastrarProdutoResult'=>array( 'msgerro'=> 'A empresa foi desabilitada!' ));   
                exit();
        } 
        if ($row['COD_EMPRESA'] != $dadosLogin['idcliente'])
            {
              return array('CadastrarProdutoResult'=>array( 'msgerro'=> 'Empresa não está igual ao cadastro!' ));   
                exit(); 
            }
if(isset($row['LOG_USUARIO']) || isset($row['DES_SENHAUS']))
{

        //memoria
        $cod_men=fnmemoria($connUser->connUser(),'true',$dadosLogin['login'],'CadastrarProduto',$row['COD_EMPRESA']);
        

        $marca=addslashes(fnLimpaCampo($CadastrarProduto['marca']));
        $subgrupo=addslashes(fnLimpaCampo($CadastrarProduto['subgrupo']));
        $grupo=addslashes(fnLimpaCampo($CadastrarProduto['grupo']));
       
        //////////////////////////////////////////////////////////
     
          //inserir venda inteira na base de dados 
                     $dados_login= addslashes(str_replace(array("\n",""),array(""," "), var_export($dadosLogin,true)));
                     $arralogin = str_replace(" ","",$dados_login);
                     $xamls= addslashes(str_replace(array("\n",""),array(""," "), var_export($InserirVenda,true)));
                    //$trimmed_array=array_map('trim',$xamls);	  
                     $arraynormal = str_replace(" ","",$xamls);
                     $xmlteste=addslashes(file_get_contents("php://input"));
                     $saida = preg_replace('/\s+/',' ', $xmlteste);
                     $inserarray='INSERT INTO origemCadProduto (DAT_CADASTR,IP,PORTA,COD_USUARIO,NOM_USUARIO,COD_EMPRESA,COD_UNIVEND,ID_MAQUINA,COD_PDV,NUM_CGCECPF,DES_VENDA,DES_LOGIN)values
                                 ("'.date("Y-m-d H:i:s").'","'.$_SERVER['REMOTE_ADDR'].'","'.$_SERVER['REMOTE_PORT'].'",
                                  "'.$row['COD_USUARIO'].'","'.$dadosLogin['login'].'","'.$row['COD_EMPRESA'].'","'.$dadosLogin['idloja'].'","'.$dadosLogin['idmaquina'].'","'.$InserirVenda['id_vendapdv'].'",0,"'.$saida.'","'.$arralogin.'")';
                      $arraP=mysqli_query($connUser->connUser(),$inserarray);
                     if (!$arraP)
                        {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                            try {mysqli_query($connUser->connUser(),$inserarray);} 
                            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                            $msg="Error description SP_ALTERA_CLIENTES_WS: $msgsql";
                            $xamls= addslashes($msg);
                        $ID_LOG="SELECT last_insert_id(COD_ORIGEM) as ID_LOG from origemCadProduto ORDER by COD_ORIGEM DESC limit 1;";
                        $LOG = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_LOG));
                        Grava_log_Produto($connUser->connUser(),$LOG['ID_LOG'],$xamls);   
                        } else {
                            $ID_LOG="SELECT last_insert_id(COD_ORIGEM) as ID_LOG from origemCadProduto ORDER by COD_ORIGEM DESC limit 1;";
                            $LOG = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_LOG));
                              
                            
                        }
                        
     
        /////////////////////////////////////////////////////////
                    // checa se a categoria existe na base dados    
                     if($row['COD_EMPRESA']==362)
                     {
                        unset($grupo);
                        $grupo=addslashes($subgrupo); 
                     }
                    $p_COD_CATEGOR="select * from categoria where COD_EMPRESA='".$row['COD_EMPRESA']."' and  DES_CATEGOR='".addslashes($grupo)."'";
                    $arrcategor=mysqli_query($connUser->connUser(), $p_COD_CATEGOR);
                    if (!$arrcategor)
                    {
                        $msg="Error description categoria: " . mysqli_error($connUser->connUser());
                        $msg1=addslashes($msg);
                         Grava_log_Produto($connUser->connUser(),$LOG['ID_LOG'],$msg1);
                         return  array('CadastrarProdutoResult'=>array('msgerro'=> 'Erro ao inserir categoria'));
                    } else {
                            $returcodcategor=mysqli_fetch_assoc($arrcategor);
                            if($returcodcategor['COD_CATEGOR']=="")
                            {
                                //cadastra categoria na base de dados
                                $insert1='insert into categoria(COD_EXTERNO,COD_EMPRESA,DES_CATEGOR,COD_USUCADA,DAT_CADASTR)
                                                               VALUE("'.fnLimpaCampo($CadastrarProduto['codigo']).'",
                                                                     '.$row['COD_EMPRESA'].',
                                                                     "'.$grupo.'",
                                                                     '.$row['COD_USUARIO'].',
                                                                     "'.date('Y-m-d H:m:s').'");' ;
                                mysqli_query($connUser->connUser(),$insert1);                                
                                $ID_COD_CATEGOR="SELECT last_insert_id(COD_CATEGOR) as COD_CATEGOR from categoria ORDER by COD_CATEGOR DESC limit 1;";
                                $COD_CATEGOR = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_COD_CATEGOR));
                                $ID_CATEGOR=$COD_CATEGOR['COD_CATEGOR']; 
                            }
                            else
                            {
                                //$cod_categor=$returcodcategor['COD_EXTERNO'];
								
                                 $ID_CATEGOR=$returcodcategor['COD_CATEGOR'];
                            }    
                         
                         $msg='OK';
                    }
            //return  array('CadastrarProdutoResult'=>array('msgerro'=> $subgrupo));    
            if($subgrupo!='' && $row['COD_EMPRESA']!=362)
            {    
                     // checa se a SUBCATEGORIA existe na base dados    
                    $p_COD_SUBCATE="SELECT * FROM SUBCATEGORIA where  COD_EMPRESA='".$row['COD_EMPRESA']."' and DES_SUBCATE='".addslashes($subgrupo)."' and COD_CATEGOR='".$ID_CATEGOR."'";
                    $arrSUBCATE=mysqli_query($connUser->connUser(), $p_COD_SUBCATE);
                    if (!$arrSUBCATE)
                    {
                        $msg="Error description Subcategoria: " . mysqli_error($connUser->connUser());
                        $msg1=addslashes($msg);
                        Grava_log_Produto($connUser->connUser(),$LOG['ID_LOG'],$msg1);
                        return  array('CadastrarProdutoResult'=>array('msgerro'=> 'Erro ao inserir SUBCATEGORIA'));
                    } else {
                            $returSUBCATE=mysqli_fetch_assoc($arrSUBCATE);
                            if(!$returSUBCATE['DES_SUBCATE'])
                            {
                                //cadastra categoria na base de dados
                                
                                $insert2='insert into subcategoria(COD_CATEGOR,COD_SUBEXTE,COD_EMPRESA,DES_SUBCATE,COD_USUCADA,DAT_CADASTR)
                                                               VALUE('.fnLimpaCampo($ID_CATEGOR).',
                                                                     "'.fnLimpaCampo($CadastrarProduto['codigo']).'",
                                                                     '.$row['COD_EMPRESA'].',
                                                                     "'.addslashes($subgrupo).'",
                                                                     '.$row['COD_USUARIO'].',
                                                                     "'.date('Y-m-d H:m:s').'");' ; 
                                mysqli_query($connUser->connUser(),$insert2);                              
                                $ID_COD_SUBCATE="SELECT last_insert_id(COD_SUBCATE) as COD_SUBCATE from subcategoria ORDER by COD_SUBCATE DESC limit 1;";
                                $COD_SUBCATE = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_COD_SUBCATE));
                                $ID_SUBCATE=$COD_SUBCATE['COD_SUBCATE']; 
                                
                            }
                            else
                            {
                                $ID_SUBCATE=$returSUBCATE['COD_SUBCATE'];    
                            }    
                         
                         $msg='OK';
                    }
            }else
            {
             $ID_SUBCATE='0';  
            }    
                     // checa se a FORNECEDEO existe na base dados 
                     
                    $p_COD_FORNECEDOR="SELECT * FROM FORNECEDORMRKA where COD_EMPRESA='".$row['COD_EMPRESA']."' and NOM_FORNECEDOR='".$marca."'";
                    $arrFORNECEDOR=mysqli_query($connUser->connUser(), $p_COD_FORNECEDOR);
                    if (!$arrFORNECEDOR)
                    {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                            try {mysqli_query($connUser->connUser(),$p_COD_FORNECEDOR);} 
                            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                            $msg="Error description FORNECEDOR : $msgsql";
                            $msg1=addslashes($msg);
                           Grava_log_Produto($connUser->connUser(),$LOG['ID_LOG'],$msg1);
                            return  array('CadastrarProdutoResult'=>array('msgerro'=> 'Erro ao inserir FORNECEDORMRKA'));
                        
                     
                    } else {
                            $returFORNECEDOR=mysqli_fetch_assoc($arrFORNECEDOR);
                            if($returFORNECEDOR['COD_FORNECEDOR']=="")
                            {
                                //cadastra categoria na base de dados
                                
                                $insert3='insert into FORNECEDORMRKA(COD_EXTERNO,COD_EMPRESA,NOM_FORNECEDOR,COD_USUCADA,DAT_CADASTR)
                                                               VALUE("'.fnLimpaCampo($CadastrarProduto['codigo']).'",
                                                                     '.$row['COD_EMPRESA'].',
                                                                     "'.addslashes($marca).'",
                                                                     '.$row['COD_USUARIO'].',
                                                                     "'.date('Y-m-d H:m:s').'");' ; 
                                mysqli_query($connUser->connUser(),$insert3);                              
                                 $ID_COD_forn="SELECT last_insert_id(COD_FORNECEDOR) as COD_FORNECEDOR 
                                                    from FORNECEDORMRKA ORDER by COD_FORNECEDOR DESC limit 1;";
                                $COD_fornecedor = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$ID_COD_forn));
                               $cod_FORNECEDOR=$COD_fornecedor['COD_FORNECEDOR'];
                               
                            }
                            else
                            {
                                $cod_FORNECEDOR=$returFORNECEDOR['COD_FORNECEDOR'];  
									
                            }    
                         
                         $msg='OK';
                    }

						
              $sql='CALL SP_INSERE_PRODUTOCLIENTE_WS(
                    0,
                    "'.$CadastrarProduto['codigo'].'",
                    '.$row['COD_EMPRESA'].',
                    " ",
                    "'.addslashes($CadastrarProduto['nome']).'",
                    '.$ID_CATEGOR.',    
                    '.$ID_SUBCATE.',
                    '.$cod_FORNECEDOR.',
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo1'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo2'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo3'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo4'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo5'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo6'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo7'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo8'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo9'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo10'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo11'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo12'])).'",
                    "'.addslashes(fnLimpaCampo($CadastrarProduto['atributo13'])).'",
                    " ",
                    '.$row['COD_USUARIO'].',
                    "",    
                   "CAD"
                    );';
           
            
                    $rsPRODUTOCLIENTE=mysqli_query($connUser->connUser(), $sql);
                    if (!$rsPRODUTOCLIENTE)
                        {
                            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);   
                            try {mysqli_query($connUser->connUser(),$sql);} 
                            catch (mysqli_sql_exception $e) {$msgsql= $e;} 
                            $msg1="Error description SP_INSERE_PRODUTOCLIENTE_WS: $msgsql";
                            $xamls= addslashes($msg1);
                            Grava_log_Produto($connUser->connUser(),$LOG['ID_LOG'],$xamls);
                            $msg='Precisa ser enviado o GRUPO';
                            /* return  array('CadastrarProdutoResult'=>array(
                                                              'msgerro'=> $sql));*/
                        } else {
                            $rsRetorno= mysqli_fetch_assoc($rsPRODUTOCLIENTE);
                            $rt=$rsRetorno['COD_PRODUTO'];
                            $msg='OK'; 
                            Grava_log_Produto($connUser->connUser(),$LOG['ID_LOG'],$msg);
                            //alterar os atributos dos produtos
                            if($row['COD_EMPRESA']!=362)
                            { 
                                $sqlUPDATE="UPDATE produtocliente SET 
                                                  ATRIBUTO1='".addslashes(fnLimpaCampo($CadastrarProduto['atributo1']))."',
                                                  ATRIBUTO2='".addslashes(fnLimpaCampo($CadastrarProduto['atributo2']))."',
                                                  ATRIBUTO3='".addslashes(fnLimpaCampo($CadastrarProduto['atributo3']))."',
                                                  ATRIBUTO4='".addslashes(fnLimpaCampo($CadastrarProduto['atributo4']))."',
                                                  ATRIBUTO5='".addslashes(fnLimpaCampo($CadastrarProduto['atributo5']))."',
                                                  ATRIBUTO6='".addslashes(fnLimpaCampo($CadastrarProduto['atributo6']))."',
                                                  ATRIBUTO7='".addslashes(fnLimpaCampo($CadastrarProduto['atributo7']))."',
                                                  ATRIBUTO8='".addslashes(fnLimpaCampo($CadastrarProduto['atributo8']))."',
                                                  ATRIBUTO9='".addslashes(fnLimpaCampo($CadastrarProduto['atributo9']))."',
                                                  ATRIBUTO10='".addslashes(fnLimpaCampo($CadastrarProduto['atributo10']))."',
                                                  ATRIBUTO11='".addslashes(fnLimpaCampo($CadastrarProduto['atributo11']))."',
                                                  ATRIBUTO12='".addslashes(fnLimpaCampo($CadastrarProduto['atributo12']))."',
                                                  ATRIBUTO13='".addslashes(fnLimpaCampo($CadastrarProduto['atributo13']))."',
                                                  DAT_ALTERAC=now()    
                                           WHERE  COD_PRODUTO='".$rt."' and cod_empresa=".$row['COD_EMPRESA'];
                                mysqli_query($connUser->connUser(),$sqlUPDATE);
                            }
                        }
                      
                        // Grava_log_Produto($connUser->connUser(),$LOG['ID_LOG'],$msg);

//grupo atualizacao  
    if($row['COD_EMPRESA']!=362)
    {    
        $atualizadadosprod="SELECT COUNT(1) qtd FROM produtocliente 
                                                    WHERE 
                                                              cod_empresa='".$row['COD_EMPRESA']."' 
                                                      AND cod_externo='".$CadastrarProduto['codigo']."' 
                                                      AND COD_CATEGOR='".$ID_CATEGOR."'";
        $wsprodatualiza = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$atualizadadosprod));
       if($wsprodatualiza['qtd']<='0')
       {
            $updateprod="UPDATE produtocliente SET COD_CATEGOR='".$ID_CATEGOR."' 
                                               WHERE  COD_EMPRESA='".$row['COD_EMPRESA']."'  
                                                     and cod_externo='".$CadastrarProduto['codigo']."';";
            mysqli_query($connUser->connUser(),$updateprod);
        }

        //atualizacao de subcategor
        $atualizadadosprodsub="SELECT COUNT(1) qtd FROM produtocliente 
                                                            WHERE 
                                                                 cod_empresa='".$row['COD_EMPRESA']."' 
                                                                 AND COD_SUBCATE='".$ID_SUBCATE."'
                                                                 and cod_externo='".$CadastrarProduto['codigo']."';";
        $wsprodatualizasub = mysqli_fetch_assoc(mysqli_query($connUser->connUser(),$atualizadadosprodsub));

        if($wsprodatualizasub['qtd']<='0')
        {
            $updateprodsub="UPDATE produtocliente SET COD_SUBCATE='".$ID_SUBCATE."' 
                                WHERE COD_EMPRESA='".$row['COD_EMPRESA']."'  
                               and cod_externo='".$CadastrarProduto['codigo']."';";
            mysqli_query($connUser->connUser(),$updateprodsub);
        }
    }

                     
                 //memoria log
                 fnmemoriafinal($connUser->connUser(),$cod_men);
                 mysqli_close($connAdm->connAdm());   
                 mysqli_close($connUser->connUser());  
                 return  array('CadastrarProdutoResult'=>array(
                                                               'msgerro'=> $msg,
                                                               'codigo'=>$rt,
                            ));

                 
}else {  return  array('CadastrarProdutoResult'=>array( 'msgerro'=>'Erro no usuario ou senha!')); }
        
        
       
}     
