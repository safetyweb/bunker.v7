<?php

        include '_system/_functionsMain.php';

//echo fnDebug('true');	
    $opcao = $_GET['opcao'];
    $itens_por_pagina = $_GET['itens_por_pagina'];	
    $pagina = $_GET['idPage'];
    $cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));
    $cod_cliente = fnLimpacampo($_REQUEST['COD_CLIENTE']);
    $nom_cliente = fnLimpacampo($_REQUEST['NOM_CLIENTE']);
    $num_cartao = fnLimpacampo($_REQUEST['NUM_CARTAO']);
    $num_celular = fnLimpacampo($_REQUEST['NUM_CELULAR']);
    $des_emailus = fnLimpacampo(trim($_REQUEST['DES_EMAILUS']));
    $num_cgcecpf = fnLimpaDoc(fnLimpacampo($_REQUEST['NUM_CGCECPF']));
                                            
    if ($cod_cliente!=0){
            $andCodigo = 'and cod_cliente='.$cod_cliente; }
            else { $andCodigo = ' ';}

    if ($nom_cliente!=''){ 
             $andNome = 'and nom_cliente like "'.$nom_cliente.'%"';	} 
            else {$andNome = ' '; } 

    if ($num_cartao!=''){ 													
             $andCartao = 'and num_cartao='.$num_cartao; }
            else {$andCartao = ' '; } 

    if ($num_cgcecpf!=''){ 
             $andCpf = 'and num_cgcecpf ='.$num_cgcecpf; }
            else {$andCpf = ' '; }
            
            $sql = "SELECT 1 FROM  ".connTemp($cod_empresa,'true').".clientes where cod_empresa = ".$cod_empresa." 
                                                                                                        ".$andCodigo."
                                                                                                        ".$andNome."
                                                                                                        ".$andCartao."
                                                                                                        ".$andCpf."
                                                                                                        ORDER BY NOM_CLIENTE ";
            
            //fnEscreve($sql);
            $resPagina = mysqli_query(connTemp($cod_empresa,''),$sql);
            $total = mysqli_num_rows($resPagina);
            //seta a quantidade de itens por página, neste caso, 2 itens
            //fnEscreve($total['CONTADOR']);
            //calcula o número de páginas arredondando o resultado para cima
            $numPaginas = ceil($total/$itens_por_pagina);
            //variavel para calcular o início da visualização com base na página atual
            $inicio = ($itens_por_pagina*$pagina)-$itens_por_pagina;
            
            $sql = "select * from clientes where cod_empresa = ".$cod_empresa." 
                                                                                ".$andCodigo."
                                                                                ".$andNome."
                                                                                ".$andCartao."
                                                                                ".$andCpf."
                                                                                ".$andEmail."
                                                                                ".$andCelular."
                                                                                ORDER BY NOM_CLIENTE limit $inicio,$itens_por_pagina";

            $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);
            // fnEscreve($sql);
            //echo "___".$sql."___";
            $count=0;
            while ($qrListaEmpresas = mysqli_fetch_assoc($arrayQuery))
              {														  
                    $count++;                                                                                                              					  
                    echo"
                            <tr>
                              <td class='text-center'><input type='radio' name='radio1' onclick='retornaForm(".$count.")'></th>
                              <td>".$qrListaEmpresas['COD_CLIENTE']."</td>
                              <td>".fnMascaraCampo($qrListaEmpresas['NUM_CARTAO'])."</td>
                              <td>".fnMascaraCampo($qrListaEmpresas['NOM_CLIENTE'])."</td>
                              <td>".fnMascaraCampo($qrListaEmpresas['DES_EMAILUS'])."</td>
                              <td>".fnMascaraCampo(fnformatCnpjCpf(fnCompletaDoc($qrListaEmpresas['NUM_CGCECPF'],$qrListaEmpresas['TIP_CLIENTE'])))."</td>
                            </tr>
                            <input type='hidden' id='ret_COD_CLIENTE_".$count."' value='".fnEncode($qrListaEmpresas['COD_CLIENTE'])."'>
                            <input type='hidden' id='ret_COD_EMPRESA_".$count."' value='".fnEncode($cod_empresa)."'>
                            "; 
                      }
											
											

 ?>

