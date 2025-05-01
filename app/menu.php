	<?php 
		include 'header.php'; 
		$tituloPagina = "Navegação";
		include "navegacao.php";
        include '_system/Lista_oferta.php';

        if(!isset($_SESSION["usuario"]) || $_SESSION["usuario"] == ""){

            session_destroy();
        
            header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));
           
        }

        $cod_cliente = "(SELECT COD_CLIENTE FROM CLIENTES WHERE NUM_CGCECPF ='".$_SESSION['usuario']."' AND COD_EMPRESA = $cod_empresa)";

        $sql = "CALL `SP_CONSULTA_SALDO_CLIENTE`($cod_cliente)";
                                
        $arrayQuerySaldo = mysqli_query(connTemp($cod_empresa,''),$sql) or die(mysqli_error());
        $qrBuscaTotais = mysqli_fetch_assoc($arrayQuerySaldo);

        if (isset($arrayQuerySaldo)){
            
            $credito_disponivel = $qrBuscaTotais['CREDITO_DISPONIVEL'];
        }

        $dat_ini = date("Y-m-d",strtotime("+30 days"));

        $sql1 = "SELECT Sum(val_saldo) AS CREDITO_EXPIRAR
                    FROM   creditosdebitos 
                    WHERE  cod_cliente = $cod_cliente
                           AND tip_credito = 'C' 
                           AND cod_statuscred = 1 
                           AND ( ( log_expira = 'S' 
                                   AND Date_format(dat_expira, '%Y-%m-%d') <= 
                                       '$dat_ini' ) 
                                  OR ( log_expira = 'N' ) )";
        // fnEscreve($sql1);

        $arrayQueryExp = mysqli_query(connTemp($cod_empresa,''),$sql1) or die(mysqli_error());
        $qrBuscaExp = mysqli_fetch_assoc($arrayQueryExp);

        if (isset($arrayQueryExp)){
            
            $credito_expirar = $qrBuscaExp['CREDITO_EXPIRAR'];

        }

        $cpf = $_SESSION['usuario'];


        $arrayOfertas=fnofertas($cpf,$dadoslogin);

        // if($_SESSION["usuario"] == 1734200014){
        //     $log_bannerhome = 'S';

        //     echo "<pre>";
        //     print_r($dadoslogin);
        //     echo "</pre>";

        //     // echo "<pre>";
        //     // print_r($arrayOfertas);
        //     // echo "</pre>";
        // }


	?>

    <style type="text/css">
        .shadow{
           -webkit-box-shadow: 0px 0px 18px -2px rgba(204,200,204,1);
            -moz-box-shadow: 0px 0px 18px -2px rgba(204,200,204,1);
            box-shadow: 0px 0px 18px -2px rgba(204,200,204,1);
            width: 100%;
            border-radius: 5px;
        }
        .carousel{
            border-radius: 10px 10px 10px 10px;
            overflow: hidden;
        }
        .carousel-caption{
            /*background-color: rgba(0,0,0,0.2);*/
            border-radius: 30px 30px 30px 30px;
        }
        .contorno{
          color: black;
          -webkit-text-fill-color: white; /* Will override color (regardless of order) */
          /*-webkit-text-stroke-width: 0.5px;
          -webkit-text-stroke-color: white;*/
          text-shadow: 1px 1px black;
        }
    </style>
		
        <div class="container">

            <?php
                if($log_bannerhome == "S"){
            ?>

                <div class="push30"></div>

                <div class="col-xs-12">

                    <div id="carouselOfertas" class="carousel slide">

                      <ol class="carousel-indicators">
                        <?php

                            if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket'])){
                                $count = 0;
                                $active = 'active';

                                // fnEscreve('if 1');
            
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
                                 // fnEscreve('if 2');

                            $active = 'active';
            
                            foreach ($arrayOfertas['oferta0']['produtoticket'] AS $chave => $valor){

                                 ?>

                                    <div class="item <?=$active?>">
                                    <?php if($valor['imagem']!=''){ ?>
                                      <img src="<?=$valor['imagem']?>" width="100%" height="160px" style="height: 190px;">
                                    <?php }else{ ?>
                                        <img src="../media/clientes/branco.jpg" width="100%" height="160px" style="height: 190px;">
                                    <?php } ?>
                                      <div class="carousel-caption d-none d-md-block contorno">
                                        <p style="font-size: 28px;"><b><?=strtoupper($valor['descricao'])?></b></p>
                                        <h5 style="font-size: 13px;"><strike>DE: R$<?=fnValor($valor['preco'],2)?></strike></h5>
                                        <h5><b>POR: <span style="font-size: 21px;">R$<?=fnValor($valor['valorcomdesconto'],2)?></span></b></h5>
                                      </div>
                                    </div>

                                 <?php

                                 $active = '';

                            }
                            
                        } else {
                             // fnEscreve('else 2');

                            ?>

                            <div class="item active">
                              <img src="<?=$arrayOfertas['oferta0']['produtoticket']['imagem']?>" width="100%" height="160px" alt="Sem Imagem"style="height: 190px;">
                              <div class="carousel-caption d-none d-md-block">
                                <h5><?=strtoupper($arrayOfertas['oferta0']['produtoticket']['descricao'])?></h5>
                                <p><small><strike>De: R$<?=fnValor($arrayOfertas['oferta0']['produtoticket']['preco'],2)?></strike></small></p>
                                <p>Por: R$<?=fnValor($arrayOfertas['oferta0']['produtoticket']['valorcomdesconto'],2)?></p>
                              </div>
                            </div>

                            <?php
                            
                        }

                        ?>

                      </div>

                        <!-- Carousel controls -->
                        <!-- <a class="carousel-control left" href="#carouselOfertas" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left"></span>
                        </a>
                        <a class="carousel-control right" href="#carouselOfertas" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right"></span> -->
                        </a>

                    </div>
                    
                </div>

            <?php
                }
            ?>

            <div class="col-xs-12 text-center text-grey" style=" color: <?=$cor_titulos?>">

                <!-- <h4><b>DIOGO LIMA DE SOUZA</b></h4>
            
                <small><p>CARTÃO: 01734200014</p></small> -->

                <div class="push20"></div>
            
                <h4><b>SEU SALDO É DE: <?= ($casasDec==2) ? "R$".fnValor($credito_disponivel,$casasDec) : fnValor($credito_disponivel,$casasDec)." PONTOS" ?></b></h4>

                <?php if($credito_expirar != 0 && $credito_expirar != ""){ ?>

                    <small><p>EXPIRA EM 30 DIAS: <?= ($casasDec==2) ? "R$".fnValor($credito_expirar,$casasDec) : fnValor($credito_expirar,$casasDec)." PONTOS" ?> </p></small>

                <?php } ?>
            
                <!-- <p><b>24/01/2019</b></p> -->

            </div>

			<div class="push30"></div>
            <div class="push5"></div>

            <div class="row">

                <?php if (array_key_exists("0", $arrayOfertas['oferta0']['produtoticket']) && $log_ofertas == 'S'){ ?>

                    <a href="ofertas.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" style="color: rgba(95,95,95,0.6); text-decoration: none; color: <?=$cor_textos?>" <?=$chk_ofertas?>>
                        <div class="col-xs-5 col-xs-offset-1 text-center">
                            <div class="shadow">
                                <div class="push20"></div>
                                <span class="fal fa-tags fa-3x"></span>
                                <div class="push10"></div>
                                <p style="font-size: 16px;">Minhas Ofertas</p>
                                <div class="push5"></div>
                            </div>
                        </div>
                    </a>

                <?php }else{ ?>

                    <a href="javascript:void(0)" style="text-decoration: none; color: rgba(95,95,95,0.1);">
                        <div class="col-xs-5 col-xs-offset-1 text-center">
                            <div class="shadow">
                                <div class="push20"></div>
                                <span class="fal fa-tags fa-3x"></span>
                                <div class="push10"></div>
                                <p style="font-size: 16px;">Minhas Ofertas</p>
                                <div class="push5"></div>
                            </div>
                        </div>
                    </a>

                <?php } ?>

                <?php if($log_dados == 'S'){ ?>

                <a href="cadastro.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" style="text-decoration: none; color: <?=$cor_textos?>" <?=$chk_dados?>>
                    <div class="col-xs-5 text-center">
                        <div class="shadow">
                            <div class="push20"></div>
                            <span class="fal fa-address-card fa-3x"></span>
                            <div class="push10"></div>
                            <p style="font-size: 16px;">Meus Dados</p>
                            <div class="push5"></div>
                        </div>
                    </div>
                </a>

                <?php }else{ ?>

                    <a href="javascript:void(0)" style="text-decoration: none; color: rgba(95,95,95,0.1);">
                        <div class="col-xs-5 col-xs-offset-1 text-center">
                            <div class="shadow">
                                <div class="push20"></div>
                                <span class="fal fa-address-card fa-3x"></span>
                                <div class="push10"></div>
                                <p style="font-size: 16px;">Meus Dados</p>
                                <div class="push5"></div>
                            </div>
                        </div>
                    </a>

                <?php } ?>

            </div>

            <div class="push30"></div>

            <div class="row">

                <?php if($log_extrato == 'S'){ ?>

                <a href="relGanhos.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" style="text-decoration: none; color: <?=$cor_textos?>" <?=$chk_extrato?>>
                    <div class="col-xs-5 col-xs-offset-1 text-center">
                        <div class="shadow">
                            <div class="push20"></div>
                            <span class="fal fa-file-invoice-dollar fa-3x"></span>
                            <div class="push10"></div>
                            <p style="font-size: 16px;">Extrato</p>
                            <div class="push5"></div>
                        </div>
                    </div>
                </a>

                <?php }else{ ?>

                    <a href="javascript:void(0)" style="text-decoration: none; color: rgba(95,95,95,0.1);">
                        <div class="col-xs-5 col-xs-offset-1 text-center">
                            <div class="shadow">
                                <div class="push20"></div>
                                <span class="fal fa-file-invoice-dollar fa-3x"></span>
                                <div class="push10"></div>
                                <p style="font-size: 16px;">Extrato</p>
                                <div class="push5"></div>
                            </div>
                        </div>
                    </a>

                <?php } ?>

                <?php if($log_premios == 'S'){ ?>

                <a href="premios.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" style="text-decoration: none; color: <?=$cor_textos?>" <?=$chk_premios?>>
                    <div class="col-xs-5 text-center">
                        <div class="shadow">
                            <div class="push20"></div>
                            <span class="fal fa-gifts fa-3x"></span>
                            <div class="push10"></div>
                            <p style="font-size: 16px;">Prêmios</p>
                            <div class="push5"></div>
                        </div>
                    </div>
                </a>

                <?php }else{ ?>

                    <a href="javascript:void(0)" style="text-decoration: none; color: rgba(95,95,95,0.1);">
                        <div class="col-xs-5 col-xs-offset-1 text-center">
                            <div class="shadow">
                                <div class="push20"></div>
                                <span class="fal fa-gifts fa-3x"></span>
                                <div class="push10"></div>
                                <p style="font-size: 16px;">Prêmios</p>
                                <div class="push5"></div>
                            </div>
                        </div>
                    </a>

                <?php } ?>

            </div>

            <div class="push30"></div>
            <div class="push5"></div>

            <div class="row">

                <?php if($log_parceiros == 'S'){ ?>

                <a href="regioes.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" style="text-decoration: none; color: <?=$cor_textos?>" <?=$chk_parceiros?>>
                    <div class="col-xs-5 col-xs-offset-1 text-center">
                        <div class="shadow">
                            <div class="push20"></div>
                            <span class="fal fa-handshake fa-3x"></span>
                            <div class="push10"></div>
                            <p style="font-size: 16px;">Parceiros</p>
                            <div class="push5"></div>
                        </div>
                    </div>
                </a>

                <?php }else{ ?>

                    <a href="javascript:void(0)" style="text-decoration: none; color: rgba(95,95,95,0.1);">
                        <div class="col-xs-5 col-xs-offset-1 text-center">
                            <div class="shadow">
                                <div class="push20"></div>
                                <span class="fal fa-handshake fa-3x"></span>
                                <div class="push10"></div>
                                <p style="font-size: 16px;">Parceiros</p>
                                <div class="push5"></div>
                            </div>
                        </div>
                    </a>

                <?php } ?>

                <?php if($log_enderecos == 'S'){ ?>

                <a href="parceiros.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" style="text-decoration: none; color: <?=$cor_textos?>" <?=$chk_enderecos?>>
                    <div class="col-xs-5 col-xs-offset-1 text-center">
                        <div class="shadow">
                            <div class="push20"></div>
                            <span class="fal fa-map-marker-alt fa-3x"></span>
                            <div class="push10"></div>
                            <p style="font-size: 16px;">Endereços</p>
                            <div class="push5"></div>
                        </div>
                    </div>
                </a>

                <?php }else{ ?>

                    <a href="javascript:void(0)" style="text-decoration: none; color: rgba(95,95,95,0.1);">
                        <div class="col-xs-5 col-xs-offset-1 text-center">
                            <div class="shadow">
                                <div class="push20"></div>
                                <span class="fal fa-map-marker-alt fa-3x"></span>
                                <div class="push10"></div>
                                <p style="font-size: 16px;">Endereços</p>
                                <div class="push5"></div>
                            </div>
                        </div>
                    </a>

                <?php } ?>

                <?php if($log_comunica == 'S'){ ?>

                <a href="faleConosco.do?key=<?=fnEncode($_SESSION["EMPRESA_COD"])?>" style="text-decoration: none; color: <?=$cor_textos?>" <?=$chk_comunica?>>
                    <div class="col-xs-5 text-center">
                        <div class="shadow">
                            <div class="push20"></div>
                            <span class="fal fa-user-headset fa-3x"></span>
                            <div class="push10"></div>
                            <p style="font-size: 16px;">Fale Conosco</p>
                            <div class="push5"></div>
                        </div>
                    </div>
                </a>

                <?php }else{ ?>

                    <a href="javascript:void(0)" style="text-decoration: none; color: rgba(95,95,95,0.1);">
                        <div class="col-xs-5 col-xs-offset-1 text-center">
                            <div class="shadow">
                                <div class="push20"></div>
                                <span class="fal fa-user-headset fa-3x"></span>
                                <div class="push10"></div>
                                <p style="font-size: 16px;">Fale Conosco</p>
                                <div class="push5"></div>
                            </div>
                        </div>
                    </a>

                <?php } ?>

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