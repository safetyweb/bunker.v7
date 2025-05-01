<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include './oderfunctions.php';
include '../func/function.php';
include '../../_system/Class_conn.php';

 $passmarka= getallheaders();
 
if(!array_key_exists('authorizationCode', $passmarka))
{
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();  
}    

$autoriz=fndecode(base64_decode($passmarka[authorizationCode]));
$arraydadosaut=explode(';',$autoriz);

//validação do usuario
$admconn=$connAdm->connAdm();
$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('".$arraydadosaut['0']."', '".fnEncode($arraydadosaut['1'])."','','','".$arraydadosaut['4']."','','')";
$buscauser=mysqli_query($admconn,$sql);
if(empty($buscauser->num_rows)) 
{
    http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Usuario ou senha invalido!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();  
}   
$user=mysqli_fetch_assoc($buscauser);

//================fim da validação de senha
//abrindo a com temporaria
$conexaotmp= connTemp($arraydadosaut['4'], '');
//====fim da conexão com a empresa



if(!array_key_exists('4', $arraydadosaut))
{

   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();  
}
$codlista=fnantinject($_POST[codlista]);
$codUsuario=fnantinject($_POST[codUsuario]);

$target_dir = "ArquivosX/";
$target_file = $target_dir.basename($_FILES["FILE"]["name"]);
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

//Check Virus
$arquivo = array(
        'CAMINHO_TMP' => $_FILES["FILE"]["tmp_name"],
        'CONADM' =>''
);

$retorno = fnScan($arquivo);
if($retorno['RESULTADO'] == 0){
        
}else{
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Arquivo infectado por:"'.$retorno[MSG].',
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();  
}

// Check file size
if ($_FILES["FILE"]["size"] > 4000000) {
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Arquivo muito grande!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();
}

//array para compara e obritariedade 
//verificar na base de dados se a configuração ja existe

$confcampos="SELECT * FROM import_dinamico WHERE TIP_LISTA=$codlista AND cod_empresa=".$arraydadosaut['4'];
$rwconfcampos=mysqli_query($conexaotmp, $confcampos);


if($rwconfcampos->num_rows <= '0')
{
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Por favor selecione a configuração correta!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();  
}  
$rsconfcampos=mysqli_fetch_assoc($rwconfcampos);
/*
 Array
(
    [ID] => 1
    [COD_EMPRESA] => 264
    [TIP_LISTA] => 1
    [DES_TABLE] => CLIENTES
    [DES_LINHA] => 1
    [COD_USUCADA] => 
    [DAT_CADASTR] => 2022-02-17 19:31:48
    [CAMPOS_MARKA] => COD_EMPRESA,NUM_CARTAO,NOM_CLIENTE,NUM_CGCECPF,NUM_CELULAR
    [CAMPOS_LISTA] => NOME;CPF;CELULAR
)
 */
// Allow certain file formats
if($imageFileType != "csv" && $imageFileType != "txt") {
 
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "formatos permitidos : TXT e CSV",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();
}
/*
$patterns = array();
$patterns[0] = '/[[:^print:]]/';
$patterns[1] = '/\s+/';
$replacements = array();
$replacements[2] = '';
$replacements[1] = '';

function sanitizeStringlimpa($string) {

    // matriz de entrada
    $what = array( 'VALORDECUSTO(SEMR$)','PREO(SEMR$)' );

    // matriz de saída
    $by   = array( 'VALORDECUSTO','PREO' );

    // devolver a string
    return str_replace($what, $by, $string);
}

  if (move_uploaded_file($_FILES["FILE"]["tmp_name"], $target_file)) {
 //  echo "The file ". htmlspecialchars( basename( $_FILES["FILE"]["name"])). " has been uploaded.";
    
        $arquivo = Array();
        $campo = Array();
        $count  = 1;
        if (($file = fopen($target_file, "r")) !== FALSE) {    
            while (($linha = fgetcsv($file, '24000', ";",'"',"\r\n")) !== FALSE) {

                if  ($count == 1)  {
                    $campolimpo = preg_replace($patterns, $replacements, $linha);
                    $campo= sanitizeStringlimpa($campolimpo);
                    $arrayDiferenca = array_diff($COMPARE, $campo);
               
                    if(!empty($arrayDiferenca))
                    {    
                        //verifica campos Obrigatorios

                         foreach ($arrayDiferenca as $key => $dadosenviados) {
                             $camposobj.=$dadosenviados.',';
                         }
                         http_response_code(400);
                        $erroinformation='{"errors": [
                                                         {
                                                          "message": "Esses Campos São Obrigatorios:"'.rtrim($camposobj,",").',
                                                          "coderro": "400",
                                                          }
                                                     ]
                                        }';    
                          echo $erroinformation;
                          exit();
                     } 
                     
                }else{
                  
                     $arquivo[errors]=array(array(
                                                     "message"=> "Arquivo ".htmlspecialchars( basename( $_FILES["FILE"]["name"]))." está sendo processado!",
                                                       "coderro"=> "200"
                                                        
                                           ));
                     $arquivo[Produtos][] =  array_combine($campo, $linha);
                     $PRODUTOS[] =  array_combine($campo, $linha);
                     
                      if  ($count <=4)  {
                          //exibir as primeiras linhas para conferencia
                         echo json_encode($arquivo,JSON_PRETTY_PRINT); 
                      }
                }
                $count++;
            }
        } 
    
     //===========*****************************=======================
    $total1 = count(array_keys($PRODUTOS)); //total items in array 
    $limit1 =1000; //per page    
    if($total1<$limit1)
    {
     $limit1=$total1;    
    }    
    $totalPages1 = ceil($total1/$limit1); //calculate total pages
    $COUNTPAGA1='0';
    for ($i1 = 1; $totalPages1 ; $i1++) {
        foreach ($PRODUTOS as $key1 => $value1) {
           
           if($sobraarray=='1' || $total1=='1' )
            {
                $insert="INSERT INTO import_produtos (      COD_USUCADA,
                                                            COD_EXTERNO,
                                                            COD_EMPRESA,
                                                            EAN, 
                                                            DES_PRODUTO, 
                                                            DES_CATEGOR, 
                                                            COD_EXTCAT, 
                                                            DES_SUBCATE, 
                                                            COD_SUBEXTE, 
                                                            NOM_FORNECEDOR, 
                                                            COD_EXTFORN,
                                                            VAL_CUSTO,
                                                            VAL_PRECO,
                                                            LOG_PBM,
															LOG_ATIVO) VALUES
                                                       ('$user[COD_USUARIO]',
                                                       '$value1[COD_EXTERNO]', 
                                                       '$arraydadosaut[4]', 
                                                       '$value1[EAN]',
                                                       '$value1[NOM_PRODUTO]',
                                                       '$value1[Categoria]',
                                                       '$value1[COD_EXT_CAT]', 
                                                       '$value1[Subcategoria]',
                                                       '$value1[COD_EXT_SUB]',
                                                       '$value1[Fornecedor]',
                                                       '$value1[COD_EXT_FORN]', 
                                                       '".fnFormatvalor($value1[VALORDECUSTO],2)."',     
                                                       '".fnFormatvalor($value1[PREO],2)."', 
                                                       '$value1[PBM]',
													   '$value1[LOG_ATIVO]');";
               $testeerro=mysqli_query($conexaotmp,$insert);  
            } else{   
                
                if($COUNTPAGA1 <= $limit1)
                {    
                        $CLIENTENEXUX1.="('$user[COD_USUARIO]',
                                        '$value1[COD_EXTERNO]', 
                                       '$arraydadosaut[4]', 
                                       '$value1[EAN]',
                                       '$value1[NOM_PRODUTO]',
                                       '$value1[Categoria]',
                                       '$value1[COD_EXT_CAT]', 
                                       '$value1[Subcategoria]',
                                       '$value1[COD_EXT_SUB]',
                                       '$value1[Fornecedor]',
                                       '$value1[COD_EXT_FORN]', 
                                       '".fnFormatvalor($value1[VALORDECUSTO],2)."',     
                                       '".fnFormatvalor($value1[PREO],2)."', 
                                       '$value1[PBM]',
									   '$value1[LOG_ATIVO]'),";
                    unset($PRODUTOS[$key1]); 
                    $COUNTPAGA1++; 
                } 
                 if($limit1==$COUNTPAGA1){ 
                    $CLIENTENEXUX1= rtrim($CLIENTENEXUX1,',');
                    $insert="INSERT INTO import_produtos (
                                                                COD_USUCADA,
                                                                COD_EXTERNO,
                                                                COD_EMPRESA,
                                                                EAN, 
                                                                DES_PRODUTO, 
                                                                DES_CATEGOR, 
                                                                COD_EXTCAT, 
                                                                DES_SUBCATE, 
                                                                COD_SUBEXTE, 
                                                                NOM_FORNECEDOR, 
                                                                COD_EXTFORN,
                                                                VAL_CUSTO,
                                                                VAL_PRECO,
                                                                LOG_PBM,
																LOG_ATIVO) VALUES $CLIENTENEXUX1";
                    $testeerro=mysqli_query($conexaotmp, $insert);
                    $sobraarray = count(array_keys($PRODUTOS)); //total items in array
                    unset($insert);
                    unset($CLIENTENEXUX1);
                    if(!$testeerro){
                        http_response_code(400);
                        $erroinformation='{"errors": [
                                                        {
                                                         "message": "Problema ao inserir temporaria!",
                                                         "coderro": "400",
                                                         }
                                                    ]
                                       }';    
                         echo $erroinformation;
                         exit();
                    }    
                    break;    
                }
            }    
                continue;
        }

        $COUNTPAGA1='0';
        IF($totalPages1 <= $i1)
        {
            break;      
        }    
    }
    unset($sqlbounceARRAY); 
     //++++++++++++++++===============================================   
     //inserindo categoria
        $cat="INSERT INTO categoria (DES_CATEGOR,COD_EXTERNO,COD_EMPRESA,COD_USUCADA,DAT_CADASTR) 
               SELECT DES_CATEGOR,COD_EXTCAT,COD_EMPRESA,COD_USUCADA,DAT_CADASTR FROM import_produtos IMP where  IMP.COD_EMPRESA='$arraydadosaut[4]'
               AND ROW(IMP.DES_CATEGOR,IMP.COD_EXTCAT,IMP.COD_EMPRESA) NOT in (
                                                                            SELECT DES_CATEGOR,COD_EXTERNO,COD_EMPRESA  from categoria  
                                                                            where  COD_EMPRESA='$arraydadosaut[4]'
                                                                               AND case  
                                                                                         when DES_CATEGOR=IMP.DES_CATEGOR then 1
                                                                                         when COD_EXTERNO =IMP.COD_EXTCAT then 1
                                                                                      ELSE 0 END IN  (1)
                                                                                    ) 
                                                                                     GROUP BY IMP.COD_EXTCAT,IMP.DES_CATEGOR";    
        $rwcat= mysqli_query($conexaotmp, $cat);  
            if(!$rwcat){
                http_response_code(400);
                $erroinformation='{"errors": [
                                                {
                                                 "message": "Problema ao inserir categoria!",
                                                 "coderro": "400",
                                                 }
                                            ]
                               }';    
                 echo $erroinformation;
                 exit();
            }
   //+++++++++++++++++++++++++++CAD SUB CATEGORIA
   $SUBCAT="INSERT INTO subcategoria (DES_SUBCATE,COD_SUBEXTE,COD_EMPRESA,COD_USUCADA,DAT_CADASTR,COD_CATEGOR) 																 
            SELECT 
                             IMP.DES_SUBCATE,
                             IMP.COD_SUBEXTE,
                             IMP.COD_EMPRESA,
                             IMP.COD_USUCADA,
                             IMP.DAT_CADASTR,
                             CAT.COD_CATEGOR
              FROM import_produtos IMP 
              LEFT JOIN categoria CAT ON CAT.COD_EXTERNO=IMP.COD_EXTCAT
              where  IMP.COD_EMPRESA='$arraydadosaut[4]'
              AND ROW(IMP.DES_SUBCATE,IMP.COD_SUBEXTE,IMP.COD_EMPRESA) NOT in (
                                                                                SELECT DES_SUBCATE,COD_SUBEXTE,COD_EMPRESA  from SUBCATEGORIA  
                                                                                where  COD_EMPRESA='$arraydadosaut[4]'
                                                                                   AND case  
                                                                                           when DES_SUBCATE=IMP.DES_SUBCATE then 1
                                                                                           when COD_SUBEXTE =IMP.COD_SUBEXTE then 1
                                                                                          ELSE 0 END IN  (1)
                                                                               ) 
            GROUP BY IMP.COD_SUBEXTE,IMP.DES_SUBCATE"; 
    $rwSUBcat= mysqli_query($conexaotmp, $SUBCAT);  
    if(!$rwSUBcat){
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "message": "Problema ao inserir Sub-categoria!",
                                         "coderro": "400",
                                         }
                                    ]
                       }';    
         echo $erroinformation;
         exit();
    }
    //+++++++++++++++++++++++       
    //CAD FORNECEDOR
    $FRON=" INSERT INTO FORNECEDORMRKA (NOM_FORNECEDOR,COD_EXTERNO,COD_EMPRESA,COD_USUCADA,DAT_CADASTR) 	
            SELECT 
                             IMP.NOM_FORNECEDOR,
                             IMP.COD_EXTFORN,
                             IMP.COD_EMPRESA,
                             IMP.COD_USUCADA,
                             IMP.DAT_CADASTR
              FROM import_produtos IMP 
              where  IMP.COD_EMPRESA='$arraydadosaut[4]'
              AND ROW(IMP.NOM_FORNECEDOR,IMP.COD_EXTFORN,IMP.COD_EMPRESA) NOT in (
                                                                                    SELECT NOM_FORNECEDOR,COD_EXTERNO,COD_EMPRESA  from FORNECEDORMRKA  
                                                                                    where  COD_EMPRESA='$arraydadosaut[4]'
                                                                                       AND case  
                                                                                               when NOM_FORNECEDOR=IMP.NOM_FORNECEDOR then 1
                                                                                               when COD_EXTERNO =IMP.COD_EXTFORN then 1
                                                                                              ELSE 0 END IN  (1)
                                                                                 ) 
            GROUP BY IMP.COD_EXTFORN,IMP.NOM_FORNECEDOR";
    $rwforn= mysqli_query($conexaotmp, $FRON);  
    if(!$rwforn){
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "message": "Problema ao inserir Fornecedor!",
                                         "coderro": "400",
                                         }
                                    ]
                       }';    
         echo $erroinformation;
         exit();
    }
    //+++++++++++++++++++++++
    //INSERT PRODUTO
    $PROD="INSERT INTO PRODUTOCLIENTE (DES_PRODUTO,COD_EXTERNO,COD_EMPRESA,COD_USUCADA,DAT_CADASTR,COD_CATEGOR,COD_SUBCATE,COD_FORNECEDOR,EAN,LOG_PRODPBM,VAL_CUSTO,VAL_PRECO,LOG_ATIVO) 	
            SELECT 
                                    TMPPROD.DES_PRODUTO,
                                    TMPPROD.COD_EXTERNO,
                                    TMPPROD.COD_EMPRESA,
                                    TMPPROD.COD_USUCADA,
                                    TMPPROD.DAT_CADASTR,
                                    TMPPROD.COD_CATEGOR,
                                    TMPPROD.COD_SUBCATE,
                                    FORN.COD_FORNECEDOR,
                                    TMPPROD.EAN,
                                    TMPPROD.LOG_PBM,
                                    TMPPROD.VAL_CUSTO,
                                    TMPPROD.VAL_PRECO,
									TMPPROD.LOG_ATIVO

             FROM (
                                                    SELECT DISTINCT 
                                                                     IMP.DES_PRODUTO,
                                                                     IMP.COD_EXTERNO,
                                                                     IMP.COD_EMPRESA,
                                                                     IMP.COD_USUCADA,
                                                                     IMP.DAT_CADASTR,
                                                                     CAT.COD_CATEGOR,
                                                                     SUB.COD_SUBCATE,
                                                           '' COD_FORNECEDOR,
                                                           IMP.COD_EXTFORN,
                                                                     IMP.EAN,
                                                                     IMP.LOG_PBM,
                                                                     IMP.VAL_CUSTO,
                                                                     IMP.VAL_PRECO,
																	 IMP.LOG_ATIVO

                                                      FROM import_produtos IMP 
                                                      LEFT JOIN categoria CAT ON CAT.COD_EXTERNO=IMP.COD_EXTCAT AND CAT.COD_EMPRESA=IMP.COD_EMPRESA
                                                      LEFT JOIN subcategoria SUB ON SUB.COD_SUBEXTE=IMP.COD_SUBEXTE AND SUB.COD_EMPRESA=IMP.COD_EMPRESA

                                                      where  IMP.COD_EMPRESA='$arraydadosaut[4]'
                                                      AND ROW(IMP.DES_PRODUTO,IMP.COD_EXTERNO,IMP.COD_EMPRESA) NOT in (
                                                                                                                        SELECT DISTINCT  DES_PRODUTO,COD_EXTERNO,COD_EMPRESA  from PRODUTOCLIENTE  
                                                                                                                                                                                        where  COD_EMPRESA='$arraydadosaut[4]'
                                                                                                                                                                                           AND case  
                                                                                                                                                                                                   when DES_PRODUTO=IMP.DES_PRODUTO then 1
                                                                                                                                                                                                   when COD_EXTERNO =IMP.COD_EXTERNO then 1
                                                                                                                                                                                                  ELSE 0 END IN  (1)
                                                                                                                                                                                                                                    ) 
                                                    GROUP BY IMP.COD_EXTERNO,IMP.DES_PRODUTO
                                                    )TMPPROD
                      LEFT JOIN FORNECEDORMRKA FORN ON FORN.COD_EXTERNO=TMPPROD.COD_EXTFORN AND FORN.COD_EMPRESA=TMPPROD.COD_EMPRESA";
     $rwPROD= mysqli_query($conexaotmp, $PROD);  
    if(!$rwPROD){
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "message": "Problema ao inserir PRODUTO!",
                                         "coderro": "400",
                                         }
                                    ]
                       }';    
         echo $erroinformation;
         exit();
    } 
    //==============
    //limpar a base de import
    $limpabase="DELETE FROM import_produtos WHERE   COD_EMPRESA='$arraydadosaut[4]';";
    $rwlimpa= mysqli_query($conexaotmp, $limpabase);  
    if(!$rwlimpa){
        http_response_code(400);
        $erroinformation='{"errors": [
                                        {
                                         "message": "Problema ao limpa base de dados!",
                                         "coderro": "400",
                                         }
                                    ]
                       }';    
         echo $erroinformation;
         exit();
    } 
} else {
   http_response_code(400);
   $erroinformation='{"errors": [
                                    {
                                     "message": "Tente novamente mais tarde!",
                                     "coderro": "400",
                                     }
                                ]
                   }';    
     echo $erroinformation;
     exit();
  }*/
?>