    <?php 
        include 'header.php'; 
        $tituloPagina = "Navegação";
        include "navegacao.php";
        include '_system/Lista_oferta.php';

         if(!isset($_SESSION["usuario"])){
        
           header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));
           
        }

        $sqlCli = "SELECT COD_CLIENTE, NOM_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF ='$_SESSION[usuario]' AND COD_EMPRESA = $cod_empresa";
        $arrayQueryCli = mysqli_query(connTemp($cod_empresa,''),$sqlCli);
        $qrCli = mysqli_fetch_assoc($arrayQueryCli);

        $cod_cliente = $qrCli['COD_CLIENTE'];
        $nom_cliente = $qrCli['NOM_CLIENTE'];
        $nom_cliente = explode(" ", $nom_cliente);
        $nom_cliente = ucfirst(strtolower($nom_cliente[0]));

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


        $cpf = $_SESSION['usuario'];

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

        <div class="col-xs-12 text-center shadow2 fundoCel1" style=" color: <?=$cor_textfull?>; background-color: <?=$cor_fullpag?>;  border-radius: 5px;">
        
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

                <div class="push10"></div>
                        
            </div>

        </div>

        <?php

        if($log_bannerhome == 'S'){

            $arrayOfertas=fnofertas($cpf,$dadoslogin);

        ?>

            <div class="push30"></div>

            <div class="col-xs-12 shadow">

                    <div id="carouselOfertas" class="carousel slide" style="">

                      <ol class="carousel-indicators">
                        <?php

                            if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket'])){
                                $count = 0;
                                $active = 'active';
            
                                foreach ($arrayOfertas['oferta0']['produtoticket'] AS $chave => $valor){
                                    ?>
                                        <li data-target="#carouselOfertas" data-slide-to="<?=$count?>" class="<?=$active?>"></li>
                                    <?php
                                    $count++;
                                    $active = '';                                    
                                }
                                
                            } else {
                               ?>
                                <li data-target="#carouselOfertas" data-slide-to="0" class="active"></li>
                               <?php
                            }

                        ?>
                      </ol>
                      <div class="carousel-inner">

                        <?php

                            if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket'])){

                            $active = 'active';
            
                            foreach ($arrayOfertas['oferta0']['produtoticket'] AS $chave => $valor){

                                if($valor['descricao'] != ''){

                                ?>

                                    <div class="item <?=$active?>">
                                    <?php if($valor['imagem']!=''){ ?>
                                      <img src="<?=$valor['imagem']?>" width="100%" height="160px" style="height: 190px;">
                                    <?php }else{ ?>
                                        <img src="../media/clientes/branco.jpg" width="100%" height="160px" style="height: 190px;">
                                    <?php } ?>
                                      <div class="carousel-caption d-none d-md-block">
                                        <h5><?=strtoupper($valor['descricao'])?></h5>
                                        <p><small><strike>DE: R$<?=$valor['preco']?></strike></small> &nbsp; POR: R$<?=$valor['valorcomdesconto']?></p>
                                      </div>
                                    </div>

                                <?php

                                    $active = '';

                                }

                            }
                            
                        } else {

                            if($arrayOfertas['oferta0']['produtoticket']['descricao'] != ''){

                            ?>

                                <div class="item active">
                                  <?php if($arrayOfertas['oferta0']['produtoticket']['imagem']!=''){ ?>
                                      <img src="<?=$arrayOfertas['oferta0']['produtoticket']['imagem']?>" width="100%" height="160px" style="height: 190px;">
                                  <?php }else{ ?>
                                    <img src="../media/clientes/branco.jpg" width="100%" height="160px" style="height: 190px;">
                                  <?php } ?>
                                  <div class="carousel-caption d-none d-md-block contorno">
                                    <p style="font-size: 24px;"><b><?=strtoupper($arrayOfertas['oferta0']['produtoticket']['descricao'])?></b></p>
                                    <h5 style="font-size: 13px;"><strike>DE: R$<?=$arrayOfertas['oferta0']['produtoticket']['preco']?></strike></h5>
                                    <h5><b>POR: <span style="font-size: 21px;">R$<?=$arrayOfertas['oferta0']['produtoticket']['valorcomdesconto']?></span></b></h5>
                                  </div>
                                </div>

                            <?php

                            }
                            
                        }

                        ?>

                      </div>

                        <!-- Carousel controls -->
                        <a class="carousel-control left" href="#carouselOfertas" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="carousel-control right" href="#carouselOfertas" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span>
                        </a>

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
                    
                    <a href="ofertas.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_OFERTAS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_ofertas?>">
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

                    <a href="banner.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_JORNAL LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_jornal?>">
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

                    <a href="habito.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_HABITO LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_habito?>">
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

                    <a href="cadastro_V2.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_DADOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_dados?>">
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

                    <a href="relGanhos.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_EXTRATO LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_extrato?>">
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

                    <a href="premios.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_PREMIOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_premios?>">
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

                    <a href="parceiros.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_parceiros?>">
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

                    <a href="regioes.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_enderecos?>">
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

                    <a href="faleConosco.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_COMUNICA LOG_COLUNAS corIcones col-xs-12 reduzMargem" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_comunica?>">
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

                </div>

                <!-- blocos coluna dupla -->

                <div id="colDupla" style="display: <?=$disp_dupla?>">

                    <a href="ofertas.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_OFERTAS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_ofertas?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-tags fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Minhas Ofertas</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="banner.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_JORNAL LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_jornal?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-newspaper fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Jornal de Ofertas</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="habito.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_HABITO LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_habito?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-bags-shopping fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Minhas Compras</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="cadastro_V2.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_DADOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_dados?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-address-card fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Meus Dados</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="relGanhos.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_EXTRATO LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_extrato?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-file-invoice-dollar fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Extrato</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="premios.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_PREMIOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_premios?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-gifts fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Prêmios</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="parceiros.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_parceiros?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-handshake fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Parceiros</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="regioes.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_ENDERECOS LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_enderecos?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-map-marker-alt fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Endereços</p>
                            <div class="push5"></div>
                        </div>
                    </a>

                    <a href="faleConosco.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" class="LOG_COMUNICA LOG_COLUNAS corIcones col-xs-6 text-center" style="text-decoration: none; color: <?=$cor_textos?>; display: <?=$disp_comunica?>">
                        <div class="shadow2">
                            <div class="push20"></div>
                            <span class="fal fa-user-headset fa-md"></span>
                            <div class="push10"></div>
                            <p style="font-size: 14px;">Fale Conosco</p>
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