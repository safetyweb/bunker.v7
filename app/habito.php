<?php 
		include_once 'header.php'; 
		$tituloPagina = "Meus Produtos";
		include_once "navegacao.php";
		include_once '_system/Lista_oferta.php';

		// if(!isset($_SESSION["usuario"])){
        
    //        header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));
           
    //     }

		$cpf = $usuario;


    $arrayOfertas=fnofertas($cpf,$dadoslogin);

        // if($_SESSION["usuario"] == 1734200014){
        //     $log_bannerlista = 'S';
        // }

	if (count($arrayOfertas['oferta1']) > 0 && !$arrayOfertas['oferta1']['produtohabito'][0]){
		$produtoHabito = $arrayOfertas['oferta1']['produtohabito'];
		$arrayOfertas['oferta1']['produtohabito'] = "";
		$arrayOfertas['oferta1']['produtohabito'][0] = $produtoHabito;
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

	.carousel-indicators{
		z-index: 0;
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
		
        <div class="container">

			<div class="push50"></div>
			<div class="push5"></div>

			<div class="row">

				<?php 

					// echo "<pre>";
					// print_r($arrayOfertas);
					// echo "</pre>";

					if (count($arrayOfertas['oferta1']) > 0){

						foreach ($arrayOfertas['oferta1']['produtohabito'] as $habito){

							if($habito['descricao'] != ""){

							?>

								<div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
			            			<div class="shadow2">
	                            		<div class="push5"></div>
		                                <div class="col-xs-5 text-center" style="padding: 0;">
		                                	<p><small><?=$habito['codigoexterno']?></small></p>
		                                </div>
		                                <div class="col-xs-7">
		                                    <p><small><?=strtoupper($habito['descricao'])?></small></p>
		                                </div>
	                            		<div class="push5"></div>
		                            </div>
	                            </div>

							<?php

							}

						}

					}else{

					?>

						<div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
							<div class="shadow2">
				        		<div class="push5"></div>
				                <div class="col-xs-12 zeraPadLateral text-center">
				                    <h5>Não há produtos</h5>
				                </div>
				        		<div class="push5"></div>
				            </div>
				        </div>

					<?php

					}

				?>

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