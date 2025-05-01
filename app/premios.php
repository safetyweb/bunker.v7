<?php 
		include_once 'header.php'; 
		$tituloPagina = "Prêmios";
		include_once "navegacao.php";
		include_once '_system/Lista_oferta.php';

		// if(!isset($_SESSION["usuario"])){
        
        //    header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));
           
        // }

	?>

<style>


	figure { 
	  display: block; 
	  position: relative; 
	  overflow: hidden; 
	}

	figcaption { 
	  position: absolute; 
	  background: rgba(255,255,255,0.75); 
	  color: #000; 
	  padding: 8px 15px 0 15px;
	  width: 100%;
	  text-align: center;
	  font-size: 14px;
	  font-weight: bold;
	  line-height: 12px;
	  letter-spacing: -0.05rem;

	  opacity: 0;
	  bottom: 0; 
	  /*left: -30%;*/
	  -webkit-transition: all 0.6s ease;
	  -moz-transition:    all 0.6s ease;
	  -o-transition:      all 0.6s ease;
	}

	figure:hover figcaption {
	  opacity: 1;
	  left: 0;
	}

	figure:hover:before {
	  opacity: 0;
	}
	
	.cap-bot:before {  bottom: 15px; left: 10px; }

	.cap-bot figcaption { 
		left: 0; 
		bottom: 0; 
		opacity: 1;
	}


</style>	
		
        <div class="container">

			<div class="push50"></div>

			<div class="row">
				
				<div class="col-xs-12">

					<?php

						$sql1="SELECT A.*,B.DES_CATEGOR as GRUPO,C.DES_SUBCATE as SUBGRUPO from PRODUTOPROMOCAO A 
						LEFT JOIN CAT_PROMOCAO B ON A.COD_CATEGOR = B.COD_CATEGOR 
						LEFT JOIN SUB_PROMOCAO C ON A.COD_SUBCATE = C.COD_SUBCATE 
						where A.COD_EMPRESA='".$cod_empresa."' 
						AND A.COD_EXCLUSA=0 
						AND log_markapontos = 1 order by A.DES_PRODUTO
						 ";

						//fnEscreve($sql1);
						$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1) or die(mysqli_error());
						
						$count=0;
						while ($qrListaProduto = mysqli_fetch_assoc($arrayQuery)){
							$count++;
							$des_imagem = $qrListaProduto['DES_IMAGEM'];
							$des_produto = $qrListaProduto['DES_PRODUTO'];
							$num_pontos = $qrListaProduto['NUM_PONTOS'];
							
							?>
							
						<div class="col-xs-12 gallery-item gallery-popup all kittens " style="padding: 15px;">
							<figure class="cap-bot">  
								<a href="#" class="zoom">
								<?php if($des_imagem != ''){ ?>
								<img src="https://img.bunker.mk/media/clientes/<?php echo $cod_empresa; ?>/produtospromo/<?php echo $des_imagem; ?>" style="width:100%; height: 40vh;" alt="gallery-image" title="gallery-image" class="img-responsive" />
								<figcaption>
									<?php echo $des_produto; ?> <br>
									<?php echo $num_pontos; ?> <small>Pontos</small>
								</figcaption>
								<?php }else{
								?>
								<div class="row text-center">
									<p style="font-weight: bolder; font-size: 12px;">
										<?php echo $des_produto; ?> <br>
										<?php echo $num_pontos; ?> <small>Pontos</small><br><br><br>
									</p>
								</div>
								<?php } ?>
								</a>
							</figure>
						</div>

						<div class="push30"></div>
							
						<?php		
						}

					?>
					
				</div>

			</div>

			<?php

				if (array_key_exists("0", $arrayOfertas['oferta1']['produtohabito'])){
		  			$count = 0;
		  			$active = 'active';
		  	?>

		  		<div class="push30"></div>
		  		<div class="row">
					<div class="col-xs-12 text-center">
						<h5 style="font-weight: 700;">Meu hábito</h5>
					</div>
				</div>

				<div class="push20"></div>

				<div class="row">

		  	<?php 

				    foreach ($arrayOfertas['oferta1']['produtohabito'] AS $chave => $valor){
				    	?>

				    		<div class="col-xs-12" style='font-weight: 700;'>&emsp; &bull; &nbsp; <?=$valor['descricao']?></div>
                            <div class='push'></div>
                            <span style='font-weight: 700; margin: 0 0 0 34px; font-size: 13px;'>Código: <?=$valor['codigoexterno']?></span>
                            <div class="push15"></div>

				    		<!-- <div class='col-xs-12'>&emsp; &bull; &nbsp; <?=$valor['descricao']?>
							<div class='push'></div> 
				    		<span style='font-weight: 700; margin: 0 0 0 34px; font-size: 13px;'><?=$valor['codigoexterno']?></span></div> -->
				     		
				     	<?php
				    	$count++;
				    	$active = '';							         
				    }

			?>
				</div>
					
			<?php 
				    
				}else{

			?>

						<div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
							<div class="shadow2">
				        		<div class="push5"></div>
				                <div class="col-xs-12 zeraPadLateral text-center">
				                    <h5>No momento, não há prêmios disponíveis.</h5>
				                </div>
				        		<div class="push5"></div>
				            </div>
				        </div>
			
			<?php 
				    
				}

			?> 

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