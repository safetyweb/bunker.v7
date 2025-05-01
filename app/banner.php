<?php 
		include_once 'header.php'; 
		$tituloPagina = "Jornal de ofertas";
		include_once "navegacao.php";
		include_once '_system/Lista_oferta.php';

		if(!isset($_SESSION["usuario"])){
        
           header('Location:app.do?key='.fnEncode($_SESSION["EMPRESA_COD"]));
           
        }

		$cpf = $_SESSION['usuario'];


        $arrayOfertas=fnofertas($cpf,$dadoslogin);

        if($_SESSION["usuario"] == 1734200014){
            $log_bannerlista = 'S';
        }

		$sql1="SELECT A.DES_IMAGEM, 
						A.DES_LINK, 
						A.DES_BANNER, 
						A.LOG_LINKWHATS FROM BANNER_APP A 
				WHERE A.COD_EMPRESA = $cod_empresa 
				AND A.COD_EXCLUSA = 0 
				AND A.LOG_ATIVO = 'S' 
				AND A.LOG_PARCEIRO = 'N' 
				ORDER BY A.DES_BANNER";

		// fnEscreve($sql1);
		$arrayQuery = mysqli_query(connTemp($cod_empresa,""),$sql1);

		$num_rows = mysqli_num_rows($arrayQuery);

	?>


<style>

    .carousel-control{
    	background: none!important;
		display: flex;
		justify-content: center;
		flex-direction: column;
		align-items: center;
    }
	.carousel-control span{
		color: #008AD6;
		font-size: 30px;
	}
    /*.carousel-caption{
        background-color: rgba(0,0,0,0.2);
        border-radius: 30px 30px 30px 30px;
    }*/
    .contorno{
      color: black;
      -webkit-text-fill-color: white; /* Will override color (regardless of order) */
      /*-webkit-text-stroke-width: 0.5px;
      -webkit-text-stroke-color: white;*/
      text-shadow: 1px 1px black;
    }

	.carousel-indicators{
		position: absolute;
		bottom: 0;
		left:0px;
		width: 100%;
		margin: 0;
		left: 0;
		right: 0;
		width: 100%;
	}

	.carousel-indicators .active {
		background-color: #008AD6;
	}
	.carousel-indicators li {
		border-color: #008AD6;
	}

	.carousel-inner {
		height:auto;
		height: calc(100vh - 110px) !important;
	}
	.carousel-inner > .item > img,
	.carousel-inner > .item > a > img {
	  width: 100%;
	  margin: auto;
	  min-height:160px;
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
		<div class="push10"></div>

		<div class="row">

		<?php
			if($num_rows > 0){
		?>

			<div class="col-xs-12">

				<div id="carouselOfertas" class="carousel slide shadow2">

					<ol class="carousel-indicators">
						<?php

							$count = 0;
							$active = 'active';
							
							while ($num_rows >= $count){														  

						?>
									<li data-target="#carouselOfertas" data-slide-to="<?=$count?>" class="<?=$active?>"></li>
						<?php

								$count++;
								$active = '';	
							}

							if($num_rows <= 1){
						?>
								<li data-target="#carouselOfertas" data-slide-to="0" class="active"></li>
						<?php

							}

						?>
					</ol>
					<div class="carousel-inner">

						<?php

							$active = 'active';

							while ($qrJornal = mysqli_fetch_assoc($arrayQuery)){	

								?>

									<div class="item <?=$active?>">
									<?php 
										if($qrJornal['DES_IMAGEM'] != ''){

											if($qrJornal['DES_LINK'] != ''){ 

												if ($qrJornal['LOG_LINKWHATS'] == 'S') {
													$link = "https://api.whatsapp.com/send?phone=".$qrJornal['DES_LINK']."&text=".urlencode($qrJornal['DES_BANNER']);
												}else{
													$link = $qrJornal['DES_LINK'];
												}
									?>
												<a href="<?=$link?>">
													<div class="zoom"><img src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/banner/<?=$qrJornal[DES_IMAGEM]?>" width="100%"></div>
												</a>
									<?php
											}else{ 
									?>
												<div class="zoom"><img src="https://img.bunker.mk/media/clientes/<?=$cod_empresa?>/banner/<?=$qrJornal[DES_IMAGEM]?>" width="100%"></div>
									<?php
											}

										}else{ 
									?>
											<img src="https://img.bunker.mk/media/clientes/branco.jpg" width="100%">
									<?php 
										} 
									?>
									</div>

								<?php

								$active = '';

							}

						?>

					</div>

					<!-- Carousel controls -->
					<a class="carousel-control left" href="#carouselOfertas" data-slide="prev">
						<span class="fal fa-angle-left"></span>
					</a>
					<a class="carousel-control right" href="#carouselOfertas" data-slide="next">
						<span class="fal fa-angle-right"></span>
					</a>

				</div>

			</div>

		<?php
			}else{
		?>

				<div class="col-xs-12 reduzMargem corIcones" style="color: <?=$cor_textos?>">
					<div class="shadow2">
						<div class="push5"></div>
						<div class="col-xs-12 zeraPadLateral text-center">
							<h5>Jornal indisponível no momento</h5>
						</div>
						<div class="push5"></div>
					</div>
				</div>

		<?php
			}
		?>

		<div class="push20"></div>

	</div>

    <?php include 'footer.php'; ?>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js"></script>

<script>

$(function() {

   $('.carousel').carousel({
	  interval: 20000
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
<script type="text/javascript" src="libs/directive.js"></script>