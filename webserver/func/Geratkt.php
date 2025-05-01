<?php
function fngeratkt($arrayDados)
{

    ////////////ofertas
    //=========================

    // flag da pergunta se vai ou nao exibir a lista
    //Select busca configuração TKT
    $selconfig = "SELECT * FROM CONFIGURACAO_TICKET where COD_EMPRESA =" . $arrayDados['cod_empresa'] . "   and LOG_ATIVO_TKT = 'S'";
    $conf = mysqli_query($arrayDados['connempresa'], $selconfig);
    $rwconfig = mysqli_fetch_assoc($conf);
    //select codigo blacklist
    $blacklist = "select * from 	blacklisttkt where COD_BLKLIST=" . $rwconfig['COD_BLKLIST'];
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
    ///
    $LOG_EMISDIA = $rwconfig['LOG_EMISDIA'];
    $cod_template_tkt = $rwconfig['COD_TEMPLATE_TKT'];
    ////
    $qtd_ofertas_tkt = $rwconfig['QTD_OFERTAS_TKT'];
    $qtd_produtos_tkt = $rwconfig['QTD_COMPRAS_TKT'];
    $cod_loja = $arrayDados['idloja'];
    if (!$conf || !$confblacklist) {
        $xamls = addslashes("Não existe configuração no TICKET!");
        fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], 'acao_B_Ticket_de_Ofertas', $xamls);
    } else {
    }

    if ($rwconfig['LOG_LISTAWS'] == 'S') {
        //Select Habitos de compra
        //verifica se o cod_cliente existe na base de dados
        //if($arraybusca['COD_CLIENTE']!='')  
        //{    
        if ($rsblk['COD_CATEGOR'] != '') {
            $cod_categorBlkand = "AND C.COD_CATEGOR NOT IN ($cod_categorBlk)";
        }

        /* $sqlhabitos="SELECT  DISTINCT  C.DES_PRODUTO, C.COD_PRODUTO,C.COD_EXTERNO 
                   FROM VENDAS A,ITEMVENDA B, PRODUTOCLIENTE C
                   WHERE A.COD_CLIENTE = ".$arraybusca['COD_CLIENTE']." AND
                   A.COD_VENDA=B.COD_VENDA AND
                   B.COD_PRODUTO=C.COD_PRODUTO AND
                   C.COD_EMPRESA=$cod_empresa  AND
                   A.DAT_CADASTR >= ADDDATE( NOW(), INTERVAL - $max_historico_tkt DAY) AND
                   A.DAT_CADASTR <= ADDDATE( NOW(), INTERVAL - $min_historico_tkt DAY) 
                   $cod_categorBlkand
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
                   A.COD_CLIENTE = " . $arraybusca['COD_CLIENTE'] . " 
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
            fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], $xamls);
            $habitos[] = array('msgerro' => 'Cliente que nao for cadastrado não gera habito de compra!');
        } else {
            //verifica se tem itens na lista de produtos
            if (mysqli_num_rows($habitosexec) == 0) {
                $msghab = 'Não há Habito de compras!';
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], $msghab);
                $habitos[] = array('msgerro' => $msghab);
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
            fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], 'HABITO DE COMPRAS OK');
        }

        //=========================================FIM DO HABITO DE COMPRAS

        //ofertasTicket 

        $sqltkt = "SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
               where  A.COD_EMPRESA = $cod_empresa AND
                  A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                  A.COD_PRODUTO = C.COD_PRODUTO AND										   
                   A.LOG_ATIVOTK = 'S' AND 
                   A.LOG_PRODTKT = 'S' AND
                  ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET('$cod_loja',A.COD_UNIVEND_AUT))) AND
                  ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET('$cod_loja',A.COD_UNIVEND_BLK))) AND
                  ((A.DAT_INIPTKT <= NOW()) OR ( A.DAT_INIPTKT IS NULL) AND 
                  (A.DAT_FIMPTKT >= NOW()) OR ( A.DAT_FIMPTKT IS NULL))   
                  ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_produtos_tkt";
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
            fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], $xamls);
        } else {
            //verifica se tem itens na lista de produtos
            if (mysqli_num_rows($tktexec) == 0) {
                $msgtkt = 'Não há Produtos no ticket!';
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], $msgtkt);
                $ofertasTicket[] = array('msgerro' => $msgtkt);
            } else {
                // exibi itens na lista de ws    
                while ($rwtkt = mysqli_fetch_assoc($tktexec)) {
                    if ($rwtkt['DES_IMAGEM'] != "") {
                        $IMG = "http://img.bunker.mk/media/clientes/$cod_empresa/produtos/" . $rwtkt['DES_IMAGEM'] . "";
                    }
                    $cod_tkt .= $rwtkt['COD_PRODUTO'] . ',';
                    $ofertasTicket[] = array(
                        'codigoexterno' => $rwtkt['COD_EXTERNO'],
                        'codigointerno' => $rwtkt['COD_PRODUTO'],
                        'descricao' => $rwtkt['NOM_PRODTKT'],
                        'preco' => $rwtkt['VAL_PRODTKT'],
                        'precopromocional' => '0,12',
                        'desconto' => $rwtkt['VAL_PROMTKT'],
                        'imagem' => $IMG
                    );
                }
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], 'OFERTASTICKET OK......');
            }
        }

        //================================================FIM DAS OFERTAS DO TKT
        //ofertas destaque

        $sqldestaque = "SELECT C.COD_EXTERNO,C.DES_IMAGEM, A.* FROM PRODUTOTKT A,CATEGORIATKT B, PRODUTOCLIENTE C
                   where  A.COD_EMPRESA = $cod_empresa AND
                      A.COD_CATEGORTKT = B.COD_CATEGORTKT AND
                      A.COD_PRODUTO = C.COD_PRODUTO AND										   
                      A.LOG_ATIVOTK = 'S' AND 
                      A.LOG_OFERTAS = 'S' AND 
                      ((A.COD_UNIVEND_AUT = '0') OR (FIND_IN_SET('$cod_loja',A.COD_UNIVEND_AUT))) AND
                      ((A.COD_UNIVEND_BLK = '0') OR (!FIND_IN_SET('$cod_loja',A.COD_UNIVEND_BLK))) AND
                      ((A.DAT_INIPTKT <= NOW()) OR ( A.DAT_INIPTKT IS NULL) AND 
                      (A.DAT_FIMPTKT >= NOW()) OR ( A.DAT_FIMPTKT IS NULL))   
                      ORDER BY B.NUM_ORDENAC, rand() LIMIT $qtd_ofertas_tkt";
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
            fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], $xamls);
        } else {
            //verifica se tem itens na lista de produtos
            if (mysqli_num_rows($descexec) == 0) {
                $msgP = 'Não há produtos em promoção!';
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], $msgP);
                $ofertapromocao[] = array('msgerro' => $msgP);
            } else {
                // exibi itens na lista de ws    
                while ($rwdesc = mysqli_fetch_assoc($descexec)) {
                    if ($rwdesc['DES_IMAGEM'] != "") {
                        $IMG = "http://img.bunker.mk/media/clientes/$cod_empresa/produtos/" . $rwdesc['DES_IMAGEM'] . "";
                    }
                    $cod_oferta .= $rwdesc['COD_PRODUTO'] . ',';
                    $ofertapromocao[] = array(
                        'codigoexterno' => $rwdesc['COD_EXTERNO'],
                        'codigointerno' => $rwdesc['COD_PRODUTO'],
                        'descricao' => $rwdesc['NOM_PRODTKT'],
                        'preco' => $rwdesc['VAL_PRODTKT'],
                        'valorcomdesconto' => $rwdesc['VAL_PROMTKT'],
                        'imagem' => $IMG
                    );
                }
                fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], 'Ofertas destaque OK ...');
            }
        }

        //===================================FIM ofertas destaque  
        //se cod cliente = vazio passa zero pra nao dar erro no insert
        if ($arraybusca['COD_CLIENTE'] == '') {
            $cod_client = 0;
        } else {
            $cod_client = $arraybusca['COD_CLIENTE'];
        }
        if ($arrayDados['idmaquina'] == '?' || $arrayDados['idmaquina'] == '') {
            $idmaquina = 0;
        } else {
            $idmaquina = $arrayDados['idmaquina'];
        }
        //=================================================================================================
        ///////////////////////////

        $todosProdutos = substr($cod_oferta . $cod_tkt . $cod_habito, 0, -1);
        $sql1 = "CALL SP_ALTERA_TICKET (
				0, 
				'" . $cod_client . "', 
				'" . $arrayDados['cod_empresa'] . "', 
				'" . $cod_loja . "', 
				'" . $idmaquina . "', 
				'" . $array['COD_USUARIO'] . "', 
				'" . $todosProdutos . "', 
				'CAD'    
				) ";

        $ROWsql = mysqli_query($arrayDados['connempresa'], $sql1);
        $arrayretorno = mysqli_fetch_assoc($ROWsql);
        mysqli_free_result($arrayretorno);
        mysqli_next_result($arrayDados['connempresa']);

        ////
        /////////ARRAY PARA GRAVA TKT
        $ofertapromocao = addslashes(str_replace(array("\n", ""), array("", " "), var_export($ofertapromocao, true)));
        $ofertapromocao = str_replace(" ", "", $ofertapromocao);
        $ofertasTicket = addslashes(str_replace(array("\n", ""), array("", " "), var_export($ofertasTicket, true)));
        $ofertasTicket = str_replace(" ", "", $ofertasTicket);
        $habitos = addslashes(str_replace(array("\n", ""), array("", " "), var_export($habitos, true)));
        $habitos = str_replace(" ", "", $habitos);
        //LOG_MISDIA =N NAO GERA DIARIO
        if ($LOG_EMISDIA == "S") {
            $DAT_VALIDADE = "'" . date('Y-m-d') . "',";
        } else {
            $DAT_VALIDADE = 'NULL,';
        }
        $insert = "INSERT INTO TICKET_DADOS(COD_TICKET,
                                      DES_PROMOCAO,
                                      DES_TICKET,
                                      DES_HABITOS
                                      COD_EMPRESA,
                                      COD_CLIENTE,
                                      COD_UNIVEND,
                                      DAT_VALIDADE,
                                      LOG_EMISDIA
                                      )
                                       VALUES
                                     (
                                       " . $arrayretorno['COD_TICKET'] . ",
                                       '" . $ofertapromocao . "',
                                       '" . $ofertasTicket . "',
                                       '" . $habitos . "',     
                                       " . $arrayDados['cod_empresa'] . ",
                                       " . $cod_client . ",
                                       " . $cod_loja . ",
                                         $DAT_VALIDADE
                                       '" . $LOG_EMISDIA . "'    
                                        )";
        mysqli_query($arrayDados['connempresa'], rtrim(ltrim(trim($insert))));
        fngravalogMSG($arrayDados['connadm'], $arrayDados['login'], $arrayDados['cod_empresa'], $arrayDados['cpf'], $arrayDados['idloja'], $arrayDados['idmaquina'], $arrayDados['codvendedor'], $arrayDados['nomevendedor'], $array['pagina'], "Gravando array do ticket gerado!");

        //========================================================    
    }
    //FIM DO IF DA FLAG ATIVA OU DESATIVA  

    return array(
        'url_ticketdeofertas' => 'http://ticket.fidelidade.mk/?tkt=' . $id,
        'urltotem' => "http://totem.bunker.mk/cadastro.do?key=$urltotem",
        'regrapreco' => $regrapreco,
        'ofertasHabito' => array('produtoHabito' => $habitos),
        'ofertasTicket' => array('produtoTicket' => $ofertasTicket),
        'ofertasPromocao' => array('produtoPromocao' => $ofertapromocao),
        'coderro' => '17',
        'msgerro' => 'bem vindo ao tktmania'
    );
}
