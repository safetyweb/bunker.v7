<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include './oderfunctions.php';
include '../func/function.php';
include '../../_system/Class_conn.php';
function convertToUTF8($str)
{
    $encoding = mb_detect_encoding($str, mb_detect_order(), true);

    if ($encoding !== "UTF-8") {
        return mb_convert_encoding($str, "UTF-8", $encoding);
    }

    return $str;
}
// Função para converter valor científico em string para float
function convertCientificToFloat($valorNotacaoCientifica)
{
    // Substituir a vírgula pelo ponto para o formato decimal
    $valorNotacaoCientifica = str_replace(',', '.', $valorNotacaoCientifica);

    // Converter para float
    $valorFloat = (float) $valorNotacaoCientifica;

    // Exibir o valor convertido
    return $valorFloat; // Saída: 790000000000000
}

$passmarka = getallheaders();
if (!array_key_exists('authorizationCode', $passmarka)) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400",
                                     }
                                ]
                   }';
    echo $erroinformation;
    exit();
}

$autoriz = fndecode(base64_decode($passmarka['authorizationCode']));
$arraydadosaut = explode(';', $autoriz);

/*$teste= Array
                (
                   'ws.rca1',
                   '@rca1',
                   '97413',
                   'webhook',
                   '264'
                );
*/
//validação do usuario
$admconn = $connAdm->connAdm();
$sql = "CALL SP_VERIFICA_ACESSO_WEBSERVICE('" . $arraydadosaut['0'] . "', '" . fnEncode($arraydadosaut['1']) . "','','','" . $arraydadosaut['4'] . "','','')";
$buscauser = mysqli_query($admconn, $sql);
if (empty($buscauser->num_rows)) {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Usuario ou senha invalido!",
                                     "coderro": "400",
                                     }
                                ]
                   }';
    echo $erroinformation;
    exit();
}
$user = mysqli_fetch_assoc($buscauser);

//================fim da validação de senha
//abrindo a com temporaria
$conexaotmp = connTemp($arraydadosaut['4'], '');
//====fim da conexão com a empresa
$limpabase = "DELETE FROM import_produtos WHERE   COD_EMPRESA='$arraydadosaut[4]';";
mysqli_query($conexaotmp, $limpabase);


if (!array_key_exists('4', $arraydadosaut)) {

    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Informe uma chave de acesso valida!",
                                     "coderro": "400",
                                     }
                                ]
                   }';
    echo $erroinformation;
    exit();
}

$target_dir = "ArquivosX/";
$target_file = $target_dir . basename($_FILES["FILE"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

//Check Virus
$arquivo = array(
    'CAMINHO_TMP' => $_FILES["FILE"]["tmp_name"],
    'CONADM' => ''
);

$retorno = fnScan($arquivo);
if ($retorno['RESULTADO'] == 0) {
} else {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Arquivo infectado por:"' . $retorno['MSG'] . ',
                                     "coderro": "400",
                                     }
                                ]
                   }';
    echo $erroinformation;
    exit();
}

// Check file size
if ($_FILES["FILE"]["size"] > 400000000) {
    http_response_code(400);
    $erroinformation = '{"errors": [
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
//para não ser obigatorio só remover a lina da estrutura
$COMPARE = array(
    'COD_EXTERNO',
    'EAN',
    'PBM',
    'NOM_PRODUTO',
    'VALORDECUSTO',
    'PRECO',
    'Categoria',
    'COD_EXT_CAT',
    'Subcategoria',
    'COD_EXT_SUB',
    'Fornecedor',
    'COD_EXT_FORN',
    'LOG_ATIVO'
);


// Allow certain file formats
if ($imageFileType != "csv" && $imageFileType != "txt") {

    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "formatos permitidos : TXT e CSV",
                                     "coderro": "400",
                                     }
                                ]
                   }';
    echo $erroinformation;
    exit();
}
$patterns = array();
$patterns[0] = '/[[:^print:]]/';
$patterns[1] = '/\s+/';
$replacements = array();
$replacements[2] = '';
$replacements[1] = '';

function sanitizeStringlimpa($string)
{

    // matriz de entrada
    $what = array('VALORDECUSTO(SEMR$)', 'PREO(SEMR$)');

    // matriz de saída
    $by   = array('VALORDECUSTO', 'PREO');

    // devolver a string
    return str_replace($what, $by, $string);
}

if (move_uploaded_file($_FILES["FILE"]["tmp_name"], $target_file)) {
    //  echo "The file ". htmlspecialchars( basename( $_FILES["FILE"]["name"])). " has been uploaded.";

    $arquivo = array();
    $campo = array();
    $count  = 1;
    $countref  = 1;

    if (($file = fopen($target_file, "r")) !== FALSE) {

        while (($linha = fgetcsv($file, '24000', ";", '"', "\r\n")) !== FALSE) {

            if ($count == 1) {
                $linha['13'] = 'COD_USUARIO';
                $linha['14'] = 'COD_EMPRESA';
                $campolimpo = preg_replace($patterns, $replacements, $linha);
                $campo = sanitizeStringlimpa($campolimpo);
                $arrayDiferenca = array_diff($COMPARE, $campo);

                if (!empty($arrayDiferenca)) {
                    //verifica campos Obrigatorios

                    foreach ($arrayDiferenca as $key => $dadosenviados) {
                        $camposobj .= $dadosenviados . ',';
                    }
                    http_response_code(400);
                    $erroinformation = '{"errors": [
                                                         {
                                                          "message": "Esses Campos São Obrigatorios:"' . rtrim($camposobj, ",") . ',
                                                          "coderro": "400",
                                                          }
                                                     ]
                                        }';
                    echo $erroinformation;
                    exit();
                }
                $arquivo['errors'] = array(array(
                    "message" => "Arquivo " . htmlspecialchars(basename($_FILES["FILE"]["name"])) . " está sendo processado!",
                    "coderro" => "200"

                ));
            } else {

                $linha['13'] = $user['COD_USUARIO'];
                $linha['14'] = $arraydadosaut[4];


                $PRODUTOS[] =  array_combine($campo, array_map("convertToUTF8", $linha));
                if ($count < 6) {
                    //exibir as primeiras linhas para conferencia
                    $arquivo['Produtos'][] =  array_combine($campo, array_map("convertToUTF8", $linha));
                    //echo  json_encode($arquivo,JSON_PRETTY_PRINT); 

                }
            }
            $count++;
        }
    }


    //===========*****************************=======================
    /*$break = 0;
    $limit = 1000;
    $totalprod_lista = count(array_keys($PRODUTOS));
    $numPaginas = ceil($totalprod_lista / $limit);
    $contadorLInhainsert = 1;

    //verifica se existe lote para empresa

    $sqlInsLot = "INSERT INTO LOTE_IMPORTPROD(
                                                    COD_EMPRESA,
                                                    QTD_PROD,
                                                    COD_USUCADA,
                                                    DAT_CADASTR
                                                    )VALUES(
                                                    " . $arraydadosaut['4'] . ",
                                                    $totalprod_lista,
                                                    9999,
                                                    NOW()
                                                )";
    // mysqli_query($conexaotmp, $sqlInsLot);
    if (mysqli_query($conexaotmp, $sqlInsLot)) {
        $cod_lote = mysqli_insert_id($conexaotmp);
    } else {
        echo "Error: " . mysqli_error($conexaotmp);
    }

    foreach ($PRODUTOS as $key1 => $value1) {

        if ($value1['LOG_ATIVO'] == 'S') {
            $value1['LOG_ATIVO'] = '0';
        } else {
            $value1['LOG_ATIVO'] = '1';
        }

        foreach ($value1 as $k => $v) {

            if (!isset($v) || $v === '') {

                http_response_code(400);
                $erroinformation = '{"errors": [
                                                                {
                                                                 "message": "E necessario preencher o campo ' . $k . '",
                                                                 "coderro": "400",
                                                                 }
                                                            ]
                                               }';
                echo $erroinformation;
                exit();
            }
        }
        if ($break <= $numPaginas) {
            // Use implode para combinar os valores
            unset($escaped_values);
            $escaped_values = array_map(function ($value) {
                return !empty($value) ? "'" . addslashes($value) . "'" : "0";
            }, $value1);

            // Use implode para combinar os valores
            $values = implode(', ', $escaped_values);

            // Usar implode para combinar os valores

            if ($contadorLInhainsert == 1) {

                $sqlinser = 'INSERT INTO import_produtos (COD_EXTERNO,
                                                        EAN,
                                                        LOG_PBM,
                                                        DES_PRODUTO,
                                                        VAL_CUSTO,
                                                        VAL_PRECO,
                                                        DES_CATEGOR, 
                                                        COD_EXTCAT, 
                                                        DES_SUBCATE, 
                                                        COD_SUBEXTE, 
                                                        NOM_FORNECEDOR, 
                                                        COD_EXTFORN,
                                                        LOG_ATIVO,
                                                        COD_USUCADA,
                                                        COD_EMPRESA
                                                        ) VALUES';
            }
            $valinsert .= '(' . $values . '),' . PHP_EOL;
            if ($contadorLInhainsert >= $limit) {
                $teste = $sqlinser . ' ' . rtrim(trim($valinsert), ',') . ';';
                $testeerro = mysqli_query($conexaotmp, $teste);
                if (!$testeerro) {
                    http_response_code(400);
                    $erroinformation = '{"errors": [
                                                    {
                                                     "message": "Problema ao inserir temporaria!",
                                                     "coderro": "400",
                                                     }
                                                ]
                                   }';
                    echo $erroinformation;
                    exit();
                }
                $contadorLInhainsert = 1;
                unset($valinsert);
                $break++;
            } elseif ($break + 1 >= $numPaginas) {
                $teste = $sqlinser . ' ' . rtrim(trim($valinsert), ',') . ';';
                $testeerro = mysqli_query($conexaotmp, $teste);
                if (!$testeerro) {
                    http_response_code(400);
                    $erroinformation = '{"errors": [
                                                    {
                                                     "message": "Problema ao inserir temporaria!",
                                                     "coderro": "400",
                                                     }
                                                ]
                                   }';
                    echo $erroinformation;
                    exit();
                }
                $contadorLInhainsert = 1;
                unset($valinsert);
            }
            $contadorLInhainsert++;
        } else {
            break; // Saia do loop se não houver mais registros
        }
    }/*
    mysqli_free_result($resultado);

    unset($sqlbounceARRAY);
    //===============================update ANTES DE INSERIR 
    /*$ATUALIZAPROD = "UPDATE produtocliente P
                        INNER JOIN import_produtos  imp  ON imp.COD_EXTERNO=P.COD_EXTERNO
                        Left JOIN categoria c ON c.COD_EXTERNO=imp.COD_EXTCAT AND c.COD_EMPRESA=imp.COD_EMPRESA 
                        LEFT JOIN subcategoria SUB ON SUB.COD_SUBEXTE=imp.COD_SUBEXTE AND SUB.COD_EMPRESA=imp.COD_EMPRESA
                        LEFT JOIN fornecedormrka F ON F.COD_EXTERNO=imp.COD_EXTFORN AND imp.COD_EMPRESA=F.COD_EMPRESA AND F.COD_FORNECEDOR > 0
                         SET
                                P.COD_CATEGOR=c.COD_CATEGOR,
                                P.COD_SUBCATE=SUB.COD_SUBCATE,
                                P.COD_FORNECEDOR=F.COD_FORNECEDOR, 
                                P.DES_PRODUTO=imp.DES_PRODUTO,
                                P.VAL_CUSTO=imp.VAL_CUSTO,
                                P.VAL_PRECO=imp.VAL_PRECO,
                                P.LOG_PONTUAR=imp.LOG_ATIVO,
                                P.EAN=imp.EAN
                        where  P.COD_EMPRESA=$arraydadosaut[4]";

    $ATUALIZAPROD = "UPDATE produtocliente P
                                            INNER JOIN import_produtos imp ON CAST(imp.COD_EXTERNO AS CHAR) = CAST(P.COD_EXTERNO AS CHAR)
                                            LEFT JOIN categoria c ON c.COD_EXTERNO = CAST(imp.COD_EXTCAT AS CHAR) AND c.COD_EMPRESA = imp.COD_EMPRESA
                                            LEFT JOIN subcategoria SUB ON CAST(SUB.COD_SUBEXTE AS CHAR) = CAST(imp.COD_SUBEXTE AS CHAR) AND SUB.COD_EMPRESA = imp.COD_EMPRESA
                                            LEFT JOIN fornecedormrka F ON CAST(F.COD_EXTERNO AS CHAR) = CAST(imp.COD_EXTFORN AS CHAR) AND imp.COD_EMPRESA = F.COD_EMPRESA AND F.COD_FORNECEDOR > 0
                                            SET
                                                P.COD_CATEGOR = c.COD_CATEGOR,
                                                P.COD_SUBCATE = SUB.COD_SUBCATE,
                                                P.COD_FORNECEDOR = F.COD_FORNECEDOR, 
                                                P.DES_PRODUTO = imp.DES_PRODUTO,
                                                P.VAL_CUSTO = imp.VAL_CUSTO,
                                                P.VAL_PRECO = imp.VAL_PRECO,
                                                P.LOG_PONTUAR = imp.LOG_ATIVO,
                                                P.EAN = imp.EAN
                                            WHERE  P.COD_EMPRESA=$arraydadosaut[4]";

    $RWATUALIZAPROD = mysqli_query($conexaotmp, $ATUALIZAPROD);
    if (!$RWATUALIZAPROD) {
        http_response_code(400);
        $erroinformation = '{"errors": [
                                                {
                                                 "message": "Problema na atualização do produto!",
                                                 "coderro": "400",
                                                 }
                                            ]
                               }';
        echo $erroinformation;
        exit();
    }*/
    // Insere registro de lote em LOTE_IMPORTPROD
    $totalprod_lista = count($PRODUTOS);
    $sqlInsLot = "INSERT INTO LOTE_IMPORTPROD (COD_EMPRESA, QTD_PROD, COD_USUCADA, DAT_CADASTR) VALUES ("
        . intval($arraydadosaut[4]) . ", "
        . intval($totalprod_lista) . ", 9999, NOW())";

    if (mysqli_query($conexaotmp, $sqlInsLot)) {
        $cod_lote = mysqli_insert_id($conexaotmp);
    } else {
        echo "Error: " . mysqli_error($conexaotmp);
        exit();
    }

    // Parâmetros para a inserção em lote
    $limit   = 1000;       // Quantidade de registros por lote
    $batch   = [];         // Array para armazenar os registros de cada lote
    $counter = 0;          // Contador de registros

    // Query base para inserir na tabela import_produtos
    $sqlBase = "INSERT INTO import_produtos (
    COD_EXTERNO,
    EAN,
    LOG_PBM,
    DES_PRODUTO,
    VAL_CUSTO,
    VAL_PRECO,
    DES_CATEGOR, 
    COD_EXTCAT, 
    DES_SUBCATE, 
    COD_SUBEXTE, 
    NOM_FORNECEDOR, 
    COD_EXTFORN,
    LOG_ATIVO,
    COD_USUCADA,
    COD_EMPRESA
) VALUES ";

    // Percorre cada produto para construir os registros
    foreach ($PRODUTOS as $produto) {

        // Validação: nenhum campo pode estar vazio
        foreach ($produto as $campo => $valor) {
            if (!isset($valor) || $valor === '') {
                http_response_code(400);
                echo json_encode([
                    "errors" => [
                        [
                            "message"  => "É necessário preencher o campo {$campo}",
                            "coderro"  => "400"
                        ]
                    ]
                ]);
                exit();
            }
        }

        // Ajusta o campo LOG_ATIVO: se for 'S' define como '0', senão '1'
        $produto['LOG_ATIVO'] = ($produto['LOG_ATIVO'] === 'S') ? '0' : '1';

        // Escapa os valores: se não estiver vazio, adiciona aspas e usa addslashes; caso contrário, insere 0
        $escapedValues = [];
        foreach ($produto as $value) {
            $escapedValues[] = (!empty($value)) ? "'" . addslashes($value) . "'" : "0";
        }

        // Adiciona o registro formatado no array do lote
        $batch[] = "(" . implode(", ", $escapedValues) . ")";
        $counter++;

        // Quando o lote atingir o limite, monta e executa a query de inserção
        if ($counter % $limit === 0) {
            $query = $sqlBase . implode(",\n", $batch) . ";";
            if (!mysqli_query($conexaotmp, $query)) {
                http_response_code(400);
                echo json_encode([
                    "errors" => [
                        [
                            "message"  => "Problema ao inserir produtos temporariamente! Query: " . $query,
                            "coderro"  => "400"
                        ]
                    ]
                ]);
                exit();
            }
            // Reinicia o lote
            $batch = [];
        }
    }

    // Insere os registros que sobraram (menos que o limite)
    if (!empty($batch)) {
        $query = $sqlBase . implode(",\n", $batch) . ";";
        if (!mysqli_query($conexaotmp, $query)) {
            http_response_code(400);
            echo json_encode([
                "errors" => [
                    [
                        "message"  => "Problema ao inserir os registros finais! Query: " . $query,
                        "coderro"  => "400"
                    ]
                ]
            ]);
            exit();
        }
    }


    $limit = 1000; // Definir o número de registros por lote
    $offset = 0; // Iniciar o offset em 0
    $rowsAffected = 0; // Inicializar o contador de registros afetados

    do {
        // Selecionar 1000 registros de cada vez a partir de 'import_produtos'
        $sqlSelect = "
            SELECT imp.EAN 
            FROM import_produtos imp
            WHERE imp.COD_EMPRESA = $arraydadosaut[4] and imp.EAN NOT IN ('0','')
            LIMIT $limit OFFSET $offset
        ";

        $resultSelect = mysqli_query($conexaotmp, $sqlSelect);

        // Verificar se houve erro na consulta de seleção
        if (!$resultSelect) {
            // echo "Erro ao selecionar EANs: " . mysqli_error($conexaotmp);
            break;
        }

        // Verificar quantas linhas foram retornadas
        $numRows = $resultSelect->num_rows;

        $eans = [];

        // Coletar os códigos EAN para usar no UPDATE
        while ($row = mysqli_fetch_assoc($resultSelect)) {
            $eans[] = "'" . $row['EAN'] . "'";
        }

        // Verificar se existem EANs para atualizar
        if ($numRows > 0 && count($eans) > 0) {
            $eanList = implode(",", $eans); // Transformar a lista de EANs em uma string separada por vírgulas

            // Executar o UPDATE usando os EANs selecionados
            $sqlUpdate = "
                UPDATE produtocliente P
                   INNER JOIN import_produtos imp ON imp.COD_EXTERNO  = P.COD_EXTERNO AND imp.ean=P.ean AND P.COD_EMPRESA=imp.COD_EMPRESA
                -- INNER JOIN import_produtos imp ON CAST(imp.COD_EXTERNO AS CHAR) = CAST(P.COD_EXTERNO AS CHAR)
                LEFT JOIN categoria c ON c.COD_EXTERNO = CAST(imp.COD_EXTCAT AS CHAR) AND c.COD_EMPRESA = imp.COD_EMPRESA
                LEFT JOIN subcategoria SUB ON CAST(SUB.COD_SUBEXTE AS CHAR) = CAST(imp.COD_SUBEXTE AS CHAR) AND SUB.COD_EMPRESA = imp.COD_EMPRESA
                LEFT JOIN fornecedormrka F ON CAST(F.COD_EXTERNO AS CHAR) = CAST(imp.COD_EXTFORN AS CHAR) AND imp.COD_EMPRESA = F.COD_EMPRESA AND F.COD_FORNECEDOR > 0
                SET
                    P.COD_CATEGOR = c.COD_CATEGOR,
                    P.COD_SUBCATE = SUB.COD_SUBCATE,
                    P.COD_FORNECEDOR = F.COD_FORNECEDOR, 
                    P.DES_PRODUTO = imp.DES_PRODUTO,
                    P.VAL_CUSTO = imp.VAL_CUSTO,
                    P.VAL_PRECO = imp.VAL_PRECO,
                    P.LOG_PONTUAR = imp.LOG_ATIVO,
                    P.EAN = imp.EAN
                WHERE P.COD_EMPRESA = $arraydadosaut[4] AND P.EAN NOT IN ('0','')
                AND P.EAN IN ($eanList)
            ";

            // Executar o UPDATE
            if (mysqli_query($conexaotmp, $sqlUpdate)) {
                $rowsAffected = mysqli_affected_rows($conexaotmp);
                // echo "Registros atualizados nesta iteração: " . $rowsAffected . "\n";
            } else {
                http_response_code(400);
                $erroinformation = '{"errors": [
                    {
                        "message": "Problema na atualização do produto!"' . mysqli_error($conexaotmp) . ',"coderro": "400"
                    }
                ]}';
                echo $erroinformation;
                break; // Interromper o loop em caso de erro
            }

            // Incrementar o offset para pegar o próximo lote
            $offset += $limit;
        } else {
            // Se não houver mais registros para processar, interromper o loop
            break;
        }
    } while ($numRows > 0); // Continua até que não haja mais registros no SELECT

    //++++++++++++++++===============================================   
    //inserindo categoria
    $cat = "REPLACE INTO categoria (COD_CATEGOR,COD_EXTERNO,DES_CATEGOR,COD_EMPRESA,COD_USUCADA,DAT_CADASTR) 
                SELECT c.COD_CATEGOR,IMP.COD_EXTCAT,IMP.DES_CATEGOR,IMP.COD_EMPRESA,IMP.COD_USUCADA,IMP.DAT_CADASTR FROM import_produtos IMP 
                left JOIN categoria c ON c.COD_EXTERNO=IMP.COD_EXTCAT AND c.COD_EMPRESA=IMP.COD_EMPRESA
                where  IMP.COD_EMPRESA=$arraydadosaut[4]      
               GROUP BY IMP.COD_EXTCAT,IMP.DES_CATEGOR";

    $rwcat = mysqli_query($conexaotmp, $cat);
    if (!$rwcat) {
        http_response_code(400);
        $erroinformation = '{"errors": [
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
    $SUBCAT = "REPLACE INTO subcategoria (COD_SUBCATE,COD_SUBEXTE,DES_SUBCATE,COD_EMPRESA,COD_USUCADA,DAT_CADASTR,COD_CATEGOR) 																 
            SELECT 
                    SUB.COD_SUBCATE,	
                    IMP.COD_SUBEXTE,
                    IMP.DES_SUBCATE,
                    IMP.COD_EMPRESA,
                    IMP.COD_USUCADA,
                    IMP.DAT_CADASTR,
                    CAT.COD_CATEGOR
            FROM import_produtos IMP 
            LEFT JOIN categoria CAT ON CAT.COD_EXTERNO=IMP.COD_EXTCAT
            LEFT JOIN subcategoria SUB ON SUB.COD_SUBEXTE=IMP.COD_SUBEXTE AND SUB.COD_EMPRESA=IMP.COD_EMPRESA
            where  IMP.COD_EMPRESA='$arraydadosaut[4]' 
            GROUP BY IMP.COD_SUBEXTE,IMP.DES_SUBCATE";

    $rwSUBcat = mysqli_query($conexaotmp, $SUBCAT);
    if (!$rwSUBcat) {
        http_response_code(400);
        $erroinformation = '{"errors": [
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
    $FRON = " REPLACE INTO FORNECEDORMRKA ( COD_FORNECEDOR,COD_EXTERNO,NOM_FORNECEDOR,COD_EMPRESA,COD_USUCADA,DAT_CADASTR) 	
            SELECT 
                   F.COD_FORNECEDOR,
                   IMP.COD_EXTFORN,
                   IMP.NOM_FORNECEDOR,
                   IMP.COD_EMPRESA,
                   IMP.COD_USUCADA,
                   IMP.DAT_CADASTR
            FROM import_produtos IMP 
            LEFT JOIN fornecedormrka F ON F.COD_EXTERNO=IMP.COD_EXTFORN AND IMP.COD_EMPRESA=F.COD_EMPRESA
            where  IMP.COD_EMPRESA=$arraydadosaut[4] and  IMP.COD_EXTFORN > 0              
            GROUP BY IMP.COD_EXTFORN,IMP.NOM_FORNECEDOR";
    $rwforn = mysqli_query($conexaotmp, $FRON);
    if (!$rwforn) {
        http_response_code(400);
        $erroinformation = '{"errors": [
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
    $PROD = "insert INTO PRODUTOCLIENTE (DES_PRODUTO,COD_EXTERNO,COD_EMPRESA,COD_USUCADA,DAT_CADASTR,COD_CATEGOR,COD_SUBCATE,COD_FORNECEDOR,EAN,LOG_PRODPBM,VAL_CUSTO,VAL_PRECO,LOG_PONTUAR,COD_LOTE) 	
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
				                    TMPPROD.LOG_ATIVO,
                                    COD_LOTE

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
								     IMP.LOG_ATIVO,
                                                                     $cod_lote as COD_LOTE  

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
                     -- LEFT JOIN FORNECEDORMRKA FORN ON FORN.COD_EXTERNO=TMPPROD.COD_EXTFORN AND FORN.COD_EMPRESA=TMPPROD.COD_EMPRESA
                     LEFT JOIN (
                                SELECT
                                  distinct
                                              COD_EXTERNO,
                                    COD_EMPRESA,
                                    COD_FORNECEDOR

                                FROM fornecedormrka WHERE cod_empresa=$arraydadosaut[4] GROUP BY COD_EXTERNO 
                            ) FORN ON FORN.COD_EXTERNO = TMPPROD.COD_EXTFORN AND FORN.COD_EMPRESA = TMPPROD.COD_EMPRESA;";
    $rwPROD = mysqli_query($conexaotmp, $PROD);
    if (!$rwPROD) {
        http_response_code(400);
        $erroinformation = '{"errors": [
                                        {
                                         "message": "Problema ao inserir PRODUTO!",
                                         "coderro": "400"
                                         }
                                    ]
                       }';
        echo $erroinformation;
        echo "Error: " . mysqli_error($conexaotmp);
        exit();
    }
    //==============
    //limpar a base de import
    /*$limpabase="DELETE FROM import_produtos WHERE   COD_EMPRESA='$arraydadosaut[4]';";
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
    }*/
} else {
    http_response_code(400);
    $erroinformation = '{"errors": [
                                    {
                                     "message": "Tente novamente mais tarde!",
                                     "coderro": "400",
                                     }
                                ]
                   }';
    echo $erroinformation;
    exit();
}
echo  json_encode($arquivo, JSON_PRETTY_PRINT);
