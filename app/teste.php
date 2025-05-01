    <?php 
        include_once  'header.php'; 
        $tituloPagina = "Navegação";
        include_once "navegacao.php";
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
        /*  session_start([
                        'cookie_params' => [
                                                'lifetime' => 3600, // tempo de vida da sessão em segundos
                                                'path' => '/',      // caminho do cookie
                                                'domain' => 'adm.bunkerapp.com.br',     // domínio do cookie
                                                'secure' => true,   // só enviar o cookie se a conexão for segura (HTTPS)
                                                'httponly' => true   // impedir que o cookie seja acessado por JavaScript
                                            ]
                    ]);       */ 
        //$_SESSION["usuario"] = fnDecode($_GET[idU]);
        // $usuario = fnDecode($_GET[idU]);
        
        // include "controleSession.php";
        // include '_system/Lista_oferta.php';

        // if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"] == ""){
        
        //    header('Location:app.php?key='.$_GET[key]);
           
        // }

        $sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE, DES_EMAILUS, COD_TPCLIENTE FROM CLIENTES WHERE NUM_CGCECPF ='$usuario' AND COD_EMPRESA = $cod_empresa";
        $arrayQueryCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);
        $qrCli = mysqli_fetch_assoc($arrayQueryCli);

        $cod_cliente = $qrCli['COD_CLIENTE'];
        $nom_cliente = $qrCli['NOM_CLIENTE'];
        $nom_cliente = explode(" ", $nom_cliente);
        $nom_cliente = ucfirst(strtolower($nom_cliente[0]));
        $DES_EMAILUS = $qrCli['DES_EMAILUS'];
        $COD_TPCLIENTE = $qrCli['COD_TPCLIENTE'];

/*
        $json=array("RD_userId"=>$cod_cliente,
                    "RD_userCompany"=>$cod_empresa,
                    "RD_userMail"=>$DES_EMAILUS,
                    "RD_userName"=>$nom_cliente,
                    "RD_userType"=>$COD_TPCLIENTE);
        $jsoncookies=json_encode($json);
//        setcookie("REDE_DUQUE",$jsoncookies, time() + (86400 * 30));
setcookie("REDE_DUQUE",$jsoncookies);
setcookie("REDE_DUQUE_V2", base64_encode(fnEncode($jsoncookies)));

        
        
        if ($_SESSION[usuario]=='01734200014')
        {
            echo'aqui';
            echo $_COOKIE[REDE_DUQUE];
            echo $_COOKIE[REDE_DUQUE_V2];
        }   
*/
        $sql = "CALL total_wallet('$cod_cliente', '$cod_empresa')";
                        
        //fnEscreve($sql);
        
        $arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
        $qrBuscaTotais = mysqli_fetch_assoc($arrayQuery);
        
        
        if (isset($arrayQuery)){
            
            $total_creditos = $qrBuscaTotais['TOTAL_CREDITOS'];
            $total_debitos = $qrBuscaTotais['TOTAL_DEBITOS'];
            $credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
            $credito_aliberar = $qrBuscaTotais['CREDITO_ALIBERAR'];
            $credito_expirados = $qrBuscaTotais['CREDITO_EXPIRADOS'];
            $credito_bloqueado = $qrBuscaTotais['CREDITO_BLOQUEADO'];
        }else{
            
            $total_creditos = 0;
            $total_debitos = 0;
            $credito_disponivel = 0;
            $credito_aliberar = 0;
            $credito_expirados = 0;
            $credito_bloqueado = 0;
            
        }

        // $sql = "CALL `SP_CONSULTA_SALDO_CLIENTE`($cod_cliente)";
                                
        // $arrayQuerySaldo = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
        // $qrBuscaTotais = mysqli_fetch_assoc($arrayQuerySaldo);

        // if (isset($arrayQuerySaldo)){
            
        //     $credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
        // }

        // $dat_ini = date("Y-m-d",strtotime("+30 days"));

        // $sql1 = "SELECT Sum(val_saldo) AS CREDITO_EXPIRAR
        //             FROM   creditosdebitos 
        //             WHERE  cod_cliente = $cod_cliente
        //                    AND tip_credito = 'C' 
        //                    AND cod_statuscred = 1 
        //                    AND ( ( log_expira = 'S' 
        //                            AND Date_format(dat_expira, '%Y-%m-%d') <= 
        //                                '$dat_ini' ) 
        //                           OR ( log_expira = 'N' ) )";
        // // fnEscreve($sql1);

        // $arrayQueryExp = mysqli_query(connTemp($cod_empresa,''),$sql1) or die(mysqli_error());
        // $qrBuscaExp = mysqli_fetch_assoc($arrayQueryExp);

        // if (isset($arrayQueryExp)){
            
        //     $credito_expirar = $qrBuscaExp['CREDITO_EXPIRAR'];

        // }


        $cpf = $usuario;

        $linkExtrato = "relGanhos.php?key=".$_GET[key]."&t=".$rand;

        if($cod_empresa == 19){
            $linkExtrato = "relCompras.php?key=".$_GET[key]."&t=".$rand;
        }

        $linkEnderecos = "regioes.php?key=".$_GET[key]."&t=".$rand;

        if($cod_empresa == 19){
            // $linkEnderecos = "tipoDestino.php";
            $linkEnderecos = "enderecosDuque.php?key=".$_GET[key]."&idp=".fnEncode('N')."&t=".$rand;
        }

        $tipoToken = "tipoToken.php";

        if($cpf == '01734200014' || $cpf == '42147177830' || $cpf == '417524198142' || $cpf == '41119557895'){
            $tipoToken = "tipoToken_old.php";
        }

    ?>

    <style>
        .shadow{
           -webkit-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
            -moz-box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
            box-shadow: 0px 0px 8px -2px rgba(204,200,204,1);
            /*width: 100%;*/
            border-radius: 5px;
        }

        .carousel{
            border-radius: 10px 10px 10px 10px;
            overflow: hidden;
        }
        .carousel-caption{
             color: <?=$cor_textos?>;
            /*background-color: rgba(0,0,0,0.2);*/
            border-radius: 30px 30px 30px 30px;
            padding-top: 5px;
            padding-bottom: 5px;
            bottom: 0px;
            left: 0;
            right: 0;
            background-color: rgba(255,255,255,0.7);
        }
        .contorno{
          /*-webkit-text-fill-color: white;  Will override color (regardless of order) */
          /*-webkit-text-stroke-width: 0.5px;
          -webkit-text-stroke-color: white;*/
          text-shadow: 1px 1px black;
        }

        .carousel-indicators{
            z-index: 0;
        }

        .carousel-control.left, .carousel-control.right {
            background-image: none
        }

        .img-lista{
            height: 85px; 
            width: 85px;
            border-radius: 50px; 
        }

        .center{
            margin: auto;
            position:absolute;
            right: 0;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
        }


    </style>
        
    <div class="container" style="margin-top: 40px;">

        <?php

        if(($cod_empresa == 19 && $credito_disponivel > 0) || $cod_empresa != 19){

        ?>

        <div class="col-xs-12 text-center shadow2 fundoCel1" style=" color: <?=$cor_textfull?>; background-color: <?=$cor_fullpag?>;  border-radius: 30px 30px 5px 5px;">
        
            <div class="push"></div>

            <div class="col-md-12 textoCel1">
                <h4 style="margin-bottom: 0;"><?=$nom_cliente?>, <span class="f12">você tem</span></h4>
                <span class="f21">
                    <strong style="font-size: 36px;"><?=fnValor($credito_disponivel,$casasDec)?></strong>
                </span>
                <div class="push"></div>
                <span class="f10"><?= ($casasDec==2) ? "REAIS" : " PONTO(S)" ?> DISPONÍVEIS</span>
            </div>

            <div class="push10"></div>

            <div class="col-md-12 texto2Cel1">

                <div class="push10"></div>

                <hr class="separador">

                <div class="push10"></div>

                <?php if($log_expira == "S"){ ?>
                
                    <div class="col-xs-4 text-center">
                        <span class="f14"><?=fnValor($total_debitos,$casasDec)?></span><br/>
                        <span class="f12">Resgatado</span>
                    </div>
                    <div class="col-xs-4 text-center">
                        <span class="f14"><?=fnValor($credito_expirados,$casasDec)?></span><br/>
                        <span class="f12">Expirado</span>
                    </div>
                    <div class="col-xs-4 text-center">
                        <span class="f14"><?=fnValor($total_creditos,$casasDec)?></span><br/>
                        <span class="f12">Ganho</span>
                    </div>

                <?php }else{ ?>
                    <div class="col-xs-12 text-center">
                        <span class="f14"><?=fnValor($total_debitos,$casasDec)?></span><br/>
                        <span class="f12">Resgatado</span>
                    </div>
                <?php } ?>

                <div class="push10"></div>
                        
            </div>

        </div>

        <?php

        }          

        ?>

        <div class="push30"></div>

        <div class="row">

            <!-- <div class="col-xs-12"> -->
                
                <!-- blocos coluna única -->

                <div id="colUnica" style="display: <?=$disp_unica?>">

                    <a href="<?=$tipoToken?>?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_TOKEN LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_token?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-barcode fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Gerar Token</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="cadVeiculo.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_VEICULO LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_veiculo?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-car fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Meus Veículos</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>
                    
                    <a href="ofertas.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_OFERTAS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_ofertas?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-tags fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Minhas Ofertas</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="banner.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_JORNAL LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_jornal?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-newspaper fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Jornal de Ofertas</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="habito.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_HABITO LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_habito?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-bags-shopping fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Minhas Compras</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="cadastro_V2.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_DADOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_dados?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-address-card fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Meus Dados</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="<?=$linkExtrato?>" class="LOG_EXTRATO LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_extrato?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-file-invoice-dollar fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Extrato</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="historicoPush.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_MENSAGEM LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_mensagem?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-envelope fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Mensagens</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="premios.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_PREMIOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_premios?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-gifts fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Prêmios</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="parceiros.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_parceiros?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-handshake fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Parceiros</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="<?=$linkEnderecos?>?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_enderecos?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-map-marker-alt fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Endereços</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="faleConosco.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_COMUNICA LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_comunica?>">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-user-headset fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Fale Conosco</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                    <a href="intro.php?key=<?=$_GET[key]?>&t=<?=$rand?>" class="LOG_LOGOUT LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: block">
                        <div class="shadow2">
                            <div class="push"></div>
                            <div class="col-xs-3">
                                <div class="push10"></div>
                                <span class="fal fa-sign-out fa-2x"></span>
                            </div>
                            <div class="col-xs-7">
                                <div class="push10"></div>
                                <div class="push5"></div>
                                <p style="font-size: 16px;">Sair</p>
                            </div>
                            <div class="col-xs-2 text-right">
                                <div class="push10"></div>
                                <span class="fal fa-angle-right fa-2x"></span>
                            </div>
                            <div class="push"></div>
                        </div>
                    </a>

                </div>

                <!-- blocos coluna dupla -->

                <div id="colDupla" style="display: <?=$disp_dupla?>">

                    <a href="<?=$tipoToken?>?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_TOKEN LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_token?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-barcode fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Gerar Token</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="cadVeiculo.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_VEICULO LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_veiculo?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-car fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Meus Veículos</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="ofertas.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_OFERTAS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_ofertas?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-tags fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Minhas Ofertas</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="banner.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_JORNAL LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_jornal?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-newspaper fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Jornal de Ofertas</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="habito.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_HABITO LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_habito?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-bags-shopping fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Minhas Compras</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="cadastro_V2.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_DADOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_dados?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-address-card fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Meus Dados</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="<?=$linkExtrato?>" class="LOG_EXTRATO LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_extrato?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-file-invoice-dollar fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Extrato</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="historicoPush.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_MENSAGEM LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_mensagem?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-envelope fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Mensagens</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="premios.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_PREMIOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_premios?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-gifts fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Prêmios</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="parceiros.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_parceiros?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-handshake fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Parceiros</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="<?=$linkEnderecos?>?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_enderecos?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-map-marker-alt fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Endereços</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="faleConosco.php?key=<?=$_GET[key]?>&idU=<?=$usuEncrypt?>&t=<?=$rand?>" class="LOG_COMUNICA LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_comunica?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-user-headset fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Fale Conosco</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="intro.php?key=<?=$_GET[key]?>&t=<?=$rand?>" class="LOG_LOGOUT LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: block">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-sign-out fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Sair</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                </div>

            <!-- </div> -->

        </div>

    </div> <!-- /container -->
    <?php include 'footer.php'; ?>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>

    <script>

        $(document).ready(function(){

            $('.carousel').carousel({
              interval: 5000
            });

            $(".carousel").swipe({

              swipe: function(event, direction, distance, duration, fingerCount, fingerData) {

                if (direction == 'left') $(this).carousel('next');
                if (direction == 'right') $(this).carousel('prev');

              },
              allowPageScroll:"vertical"

            });

        });

    </script>