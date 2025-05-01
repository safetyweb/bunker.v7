<?php 
	
	//echo "<h5>_".$opcao."</h5>";

	$hashLocal = mt_rand();	
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
		$request = md5( implode( $_POST ) );
		
		if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
		{
			$msgRetorno = 'Essa página já foi utilizada';
			$msgTipo = 'alert-warning';
		}
		else
		{
			$_SESSION['last_request']  = $request;	

		}
	}
      
	
	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		//busca dados da empresa
		$cod_empresa = fnDecode($_GET['id']);	
		$sql = "SELECT COD_EMPRESA, NOM_EMPRESA FROM empresas where COD_EMPRESA = '".$cod_empresa."' ";
		//fnEscreve($sql);
		$arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$qrBuscaEmpresa = mysqli_fetch_assoc($arrayQuery);
		
		if (isset($arrayQuery)){
			$cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
			$nom_empresa = $qrBuscaEmpresa['NOM_EMPRESA'];
		}
												
	}else {
		$cod_empresa = 0;		
		//fnEscreve('entrou else');
	}
	
	//fnMostraForm();
	$usu_cadastr = $_SESSION["SYS_COD_USUARIO"];
	//fnEscreve($usu_cadastr);
	if(isset($usu_cadastr)){
		$sql = "SELECT COD_USUARIO, NOM_USUARIO FROM USUARIOS WHERE COD_USUARIO = $usu_cadastr";
		$qrUsu = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
		$listaUsu = mysqli_fetch_assoc($qrUsu);
		$usu_cadastr = $listaUsu['NOM_USUARIO'];
		//fnEscreve($usu_cadastr);
	}

	//setando locale da data
	setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
	date_default_timezone_set('America/Sao_Paulo');
	$mes = strtoupper(strftime('%B', strtotime('today')));
	$mes = substr("$mes", 0, 3);
	$diaSemana = ucwords(strftime('%A', strtotime('today')));

?>
			<style>

			/* -- Roboto Font ------------------------------ */
			@import "https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic&subset=latin,cyrillic";


			.tile{ 
				height: 185px;
				color: #fff;

				overflow: hidden;
				cursor: pointer;
				outline: 0;
				border: 0;
				border-radius: 0;
				-webkit-transition: all 0.15s ease-in-out;
				-o-transition: all 0.15s ease-in-out;
				transition: all 0.15s ease-in-out; 
			}

			.tile .row{
				padding: 0;
				margin: 0;
			}

			.tile .col-xs-1, .tile .col-xs-2, .tile .col-xs-3, .tile .col-xs-4, .tile .col-xs-5,
			.tile .col-xs-6, .tile .col-xs-7, .tile .col-xs-8, .tile .col-xs-9, .tile .col-xs-10,
			.tile .col-xs-11, .tile .col-xs-12 {
				margin: 0;
				padding: 0;
			}

			.tile a,.tile a:hover,.tile a:visited,.tile a:link,.tile a:active{ 
				text-decoration: none; 
				display: block;
				color: #fff; 
			}

			.tile .set1{
				padding: 14px 0;
			}
			.tile .set2{
				padding: 10px 0;
			}

			.dotw .set1{
				padding: 14px 0 0 0;
			}

			.dotw .set2{
				padding: 20px 0 0 0;
			}

			.dotw span p{
				font-size: 24px;
				margin: 0;
				color: #fff;
			}

			.tile-user span p{
				font-size: 11px;
				margin-left: 5px;
			}

			.dynamicTile .col-sm-2.col-xs-4,
			.dynamicTile .col-sm-4.col-xs-8 { padding:5px; }

			@media (max-width:767px) {

				.tile { height: 126px; }

				.col-xs-8, .col-xs-4 { width:50%; }

				.tile p{ font-size: 12px; }

				.tile .fa-3x{ font-size: 40px; }

				.tile .set1{
					padding: 5px 0;
				}

				.tile .set2{
					padding: 3px 0 10px 0;
				}

				.dotw .set2{
					padding: 7px 0 0 0;
				}

				.dotw span p{
					font-size: 19px;
				}

				.tile-user .set1{
					padding: 5px 0 0 0;
				}

				.tile-user .set2{
					padding: 0;
				}

			}

			@media (min-width:768px) and (max-width:1024px) {

				.tile{ height: 150px; }

				.tile .set1{
					padding: 7px 0;
				}
				.tile .set2{
					padding: 7px 0 11px 0;
				}

				.tile p{ font-size: 14px; }

				.dotw span p{
					font-size: 22px;
					margin: 0;
				}
			}

			.tile-red {
			  background-color: #e84e40;
			}

			.tile-pink {
			  background-color: #ec407a;
			}

			.tile-purple {
			  background-color: #ab47bc;
			}

			.tile-deep-purple {
			  background-color: #7e57c2;
			}

			.tile-indigo {
			  background-color: #5c6bc0;
			}

			.tile-blue {
			  background-color: #738ffe;
			}

			.tile-light-blue {
			  background-color: #29b6f6;
			}

			.tile-cyan {
			  background-color: #26c6da;
			}

			.tile-teal {
			  background-color: #26a69a;
			}

			.tile-green {
			  background-color: #2baf2b;
			}

			.tile-light-green {
			  background-color: #9ccc65;
			}

			.tile-lime {
			  background-color: #d4e157;
			}

			.tile-yellow {
			  background-color: #ffee58;
			}

			.tile-amber {
			  background-color: #ffca28;
			}

			.tile-orange {
			  background-color: #ffa726;
			}

			.tile-deep-orange {
			  background-color: #ff7043;
			}

			.tile-brown {
			  background-color: #8d6e63;
			}


			.tile.tile-grey {
			  background: #9E9E9E !important; }

			.tile.tile-blue-grey {
			  background: #607D8B !important; }

			.tile.tile-black {
			  background: #000000 !important; }

			.tile.tile-white {
			  background: #ffffff !important; }

			.tile:hover,
			.tile:active,
			.tile.active,
			.tile:focus,
			.tile:active:focus,
			.tile.active:focus {
			  z-index:1000;
			  box-shadow:rgba(0, 0, 0, 0.3) 0 16px 16px 0;
			  -webkit-box-shadow:rgba(0, 0, 0, 0.3) 0 16px 16px 0;
			  -moz-box-shadow:rgba(0, 0, 0, 0.3) 0 16px 16px 0;
			  -webkit-filter: brightness(90%);
			}

			/* -- Efeito onda ----------------------------------- */
			.ripple-effect {
			  position: relative;
			  overflow: hidden;
			  -webkit-transform: translatez(0);
			}
			.ink {
			  display: block;
			  position: absolute;
			  pointer-events: none;
			  border-radius: 50%;
			  transform: scale(0);
			}
			.ink {
			  background: #333;
			  opacity: 0.1;
			}
			.ink.animate {
			  -webkit-animation: ripple-effect 0.8s linear;
			  -o-animation: ripple-effect 0.8s linear;
			  animation: ripple-effect 0.8s linear;
			}

			@keyframes ripple-effect {
			  100% {
			    opacity: 0;
			    -webkit-transform: scale(2.5);
			    -ms-transform: scale(2.5);
			    -o-transform: scale(2.5);
			    transform: scale(2.5);
			  }
			}
			@-webkit-keyframes ripple-effect {
			  100% {
			    opacity: 0;
			    -webkit-transform: scale(2.5);
			    -ms-transform: scale(2.5);
			    -o-transform: scale(2.5);
			    transform: scale(2.5);
			  }
			}
			@-moz-keyframes ripple-effect {
			  100% {
			    opacity: 0;
			    -webkit-transform: scale(2.5);
			    -ms-transform: scale(2.5);
			    -o-transform: scale(2.5);
			    transform: scale(2.5);
			  }
			}
			@-ms-keyframes ripple-effect {
			  100% {
			    opacity: 0;
			    -webkit-transform: scale(2.5);
			    -ms-transform: scale(2.5);
			    -o-transform: scale(2.5);
			    transform: scale(2.5);
			  }
			}
			@-o-keyframes ripple-effect {
			  100% {
			    opacity: 0;
			    -webkit-transform: scale(2.5);
			    -ms-transform: scale(2.5);
			    -o-transform: scale(2.5);
			    transform: scale(2.5);
			  }
			}

			/*-- Animação de exibição -------------------------------- */
			.display-animation > * {
			  -webkit-transform: scale(0);
			  -ms-transform: scale(0);
			  -o-transform: scale(0);
			  transform: scale(0);
			}
			.display-animation > .animated {
			  -webkit-animation: display 0.5s cubic-bezier(0.55, 0, 0.1, 1) forwards;
			  -o-animation: display 0.5s cubic-bezier(0.55, 0, 0.1, 1) forwards;
			  animation: display 0.5s cubic-bezier(0.55, 0, 0.1, 1) forwards;
			}
			.no-js .display-animation > * {
			  -webkit-transform: scale(1);
			  -ms-transform: scale(1);
			  -o-transform: scale(1);
			  transform: scale(1);
			}

			@keyframes display {
			  from {
			    -webkit-transform: scale(0);
			  }
			  to {
			    -webkit-transform: scale(1);
			  }
			}
			@-o-keyframes display {
			  from {
			    -webkit-transform: scale(0);
			  }
			  to {
			    -webkit-transform: scale(1);
			  }
			}
			@-ms-keyframes display {
			  from {
			    -webkit-transform: scale(0);
			  }
			  to {
			    -webkit-transform: scale(1);
			  }
			}
			@-moz-keyframes display {
			  from {
			    -webkit-transform: scale(0);
			  }
			  to {
			    -webkit-transform: scale(1);
			  }
			}
			@-webkit-keyframes display {
			  from {
			    -webkit-transform: scale(0);
			  }
			  to {
			    -webkit-transform: scale(1);
			  }
			}

				/*Menu DropDown*/
.kebab {
  cursor: pointer;
  position: relative;
  display: inline-block;
  box-sizing: border-box;
  padding: 0px;
  top: 5px;
}
.kebab figure {
  width: 6px;
  height: 6px;
  border-radius: 5px;
  background: #fff;
  margin: 3px 0;  
}

.middle {
  transition: all 0.25s cubic-bezier(0.72, 1.2, 0.71, 0.72);
  transform: scale(1);
  position: relative;
  box-shadow: 0 0.1px 0.1px 0 rgba(0, 0, 0, 0.16), 0 0.1px 0.3px 0 rgba(0, 0, 0, 0.12);
  -webkit-filter: blur(0.1px);
  filter: blur(0.1px);
}

.middle.active {
  transform: scale(4.5);
  transition: all 0.25s cubic-bezier(0.32, 2.04, 0.85, 0.54);
  box-shadow: 0 0.1px 0.1px 0 rgba(0, 0, 0, 0.16), 0 0.1px 0.3px 0 rgba(0, 0, 0, 0.12);
}

.cross {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%) scale(0);
  margin-top: -1px;
  font-family: "Nunito", sans-serif;
  color: black;
  transition: all 0.2s cubic-bezier(0.72, 1.2, 0.71, 0.72);
  font-size: 22px;
  user-select: none;
}

.cross.active {
  transform: translate(-50%, -50%) scale(1);
  transition: all 0.15s cubic-bezier(0.32, 2.04, 0.85, 0.54);
}

h1, a, li {
  font-family: Roboto, sans-serif;
}

h1 {
  font-size: 26px;
  background: #00bcd4;
  color: white;
  padding: 40px 0 40px 20%;
  margin-bottom: 50px;
}

a, li {
  color: #fff;
  text-decoration: none;
}

.dropdown2 {
  background-color: blue;
  position: absolute;
  right: 50px;
  top: -50px;
  transition: all 0.25s ease-out;
  transform: scale(0);
  transform-origin: 100% 0;
  box-shadow: 0 2px 4px 0 rgba(0, 0, 0, 0.16), 0 2px 8px 0 rgba(0, 0, 0, 0.12);
}

.dropdown2 li {
  display: inline-block;
  width: 100%;
  
}
.dropdown2 li a {
  width: 100%;
  padding: 1em 18px;
  display: inline-block;
  white-space: pre;
  box-sizing: border-box;
}

.dropdown2 li a:hover {
  background: #ebebeb;
}

.dropdown2:hover ul {
  transform: scale(1);
}

.dropdown2.active {
  transform: scale(0.8);
  transition: all 0.25s cubic-bezier(0.5, 1.8, 0.9, 0.8);
}




			</style>
			
					<div class="push30"></div> 

					<h3>Tiles Bootstrap</h3>

					<div class="push30"></div>
					
					<div class="container dynamicTile">
					
					   <div class="row display-animation">

					   	<!--Tile Home-->
							<div class="col-sm-2 col-xs-4 ripple-effect">
								<div class="tile tile-green">

									<div class="row set1">
										<div class="col-xs-10"></div>
										<a href="#" onclick="return false">
											<div class="col-xs-2">
												<span class="fal fa-ellipsis-v fa-2x"></span>
											</div>
										</a>
									</div>

									<a href="#" onclick="return false">
										<div class="row set2">
											<div class="col-xs-12 text-center">
												<span class="fal fa-home fa-3x"></span>
											</div>
										</div>
									</a>

									<a href="#" onclick="return false">
										<div class="row set1">
											<div class="col-xs-1"></div>
											<div class="col-xs-11"><p>Home</p></div>
										</div>
									</a>

								</div>	
							</div>

						<!--Tile Dia-->
							<div class="col-sm-2 col-xs-4 ripple-effect">
								<div class="tile tile-red dotw">
									
									<div class="row set1">

										<div class="col-xs-1"></div>
										<div class="col-xs-9">
											<span><p><?php echo strftime('%d', strtotime('today')); ?></p></span>
											<span><p><?php echo $mes; ?></p></span>
											<span><p><?php echo date("Y", strtotime('today')); ?></p></span>
										</div>

										<a href="#" onclick="return false">
											<div class="col-xs-2">
												<span class="fal fa-ellipsis-v fa-2x"></span>
											</div>
										</a>

									</div>
								
									<div class="row set2">
										<div class="col-xs-1"></div>
										<div class="col-xs-11"><p><?php echo $diaSemana; ?></p></div>
									</div>

								</div>	
							</div>

						<!--Tile Pink-->
							<div class="col-sm-4 col-xs-8 ripple-effect">
								<div class="tile tile-indigo">

									<div class="row set1">
										<div class="col-xs-10"></div>
										<a href="#" onclick="return false">
											<div class="col-xs-1 text-right">
												<span class="fal fa-ellipsis-v fa-2x"></span>
											</div>
											<div class="col-xs-1"></div>
										</a>
									</div>

									<a href="#" onclick="return false">
										<div class="row set2">
											<div class="col-xs-12 text-center">
												<span class="fal fa-paper-plane fa-3x"></span>
											</div>
										</div>
									</a>

									<a href="#" onclick="return false">
										<div class="row set1">
											<div class="col-xs-1"></div>
											<div class="col-xs-11"><p>Coluna Dupla</p></div>
										</div>
									</a>

								</div>	
							</div>

						<!--Tile Orange-->
							<div class="col-sm-4 col-xs-8 ripple-effect">
								<div class="tile tile-orange">

									<div class="row set1">
										<div class="col-xs-10"></div>
										<a href="#" onclick="return false">
											<div class="col-xs-1 text-right">
												<span class="fal fa-ellipsis-v fa-2x"></span>
											</div>
											<div class="col-xs-1"></div>
										</a>
									</div>

									<a href="#" onclick="return false">
										<div class="row set2">
											<div class="col-xs-12 text-center">
												<span class="fal fa-cart-plus fa-3x"></span>
											</div>
										</div>
									</a>

									<a href="#" onclick="return false">
										<div class="row set1">
											<div class="col-xs-1"></div>
											<div class="col-xs-11"><p>Coluna Dupla</p></div>
										</div>
									</a>

								</div>	
							</div>


						<!--Tile Pomegranate-->
							<div class="col-sm-4 col-xs-8 ripple-effect">
								<div class="tile tile-purple">

									<div class="row set1">
										<div class="col-xs-10"></div>
										<a href="#" onclick="return false">
											<div class="col-xs-1 text-right">
												<span class="fal fa-ellipsis-v fa-2x"></span>
											</div>
											<div class="col-xs-1"></div>
										</a>
									</div>

									<a href="#" onclick="return false">
										<div class="row set2">
											<div class="col-xs-12 text-center">
												<span class="fal fa-file fa-3x"></span>
											</div>
										</div>
									</a>

									<a href="#" onclick="return false">
										<div class="row set1">
											<div class="col-xs-1"></div>
											<div class="col-xs-11"><p>Coluna Dupla</p></div>
										</div>
									</a>

								</div>	
							</div>

						<!--Tile User-->
							<div class="col-sm-2 col-xs-4 ripple-effect">
								<div class="tile tile-teal tile-user">

									<div class="row set1">
										<div class="col-xs-10"><span><p><?php echo $usu_cadastr; ?></p></span></div>
										<a href="#" onclick="return false">
											<div class="col-xs-2">
												<span class="fal fa-ellipsis-v fa-2x"></span>
											</div>
										</a>
									</div>

									<a href="#" onclick="return false">
										<div class="row set2">
											<div class="col-xs-12 text-center">
												<span class="fal fa-user-circle fa-3x"></span>
											</div>
										</div>
									</a>

								</div>	
							</div>

						<!--Tile Wisteria-->
							<div class="col-sm-4 col-xs-8 ripple-effect">
								<div class="tile tile-pink">

									<div class="row set1">
										<div class="col-xs-10"></div>
										<a href="#" onclick="return false">
											<div class="col-xs-1 text-right">
												<span class="fal fa-ellipsis-v fa-2x"></span>
											</div>
											<div class="col-xs-1"></div>
										</a>
									</div>

									<a href="#" onclick="return false">
										<div class="row set2">
											<div class="col-xs-12 text-center">
												<span class="fal fa-download fa-3x"></span>
											</div>
										</div>
									</a>

									<a href="#" onclick="return false">
										<div class="row set1">
											<div class="col-xs-1"></div>
											<div class="col-xs-11"><p>Coluna Dupla</p></div>
										</div>
									</a>

								</div>	
							</div>

						<!--Tile Home-->
							<!-- <div class="col-sm-2 col-xs-4 ripple-effect">
								<div class="tile tile-brown">
									<div class="row set1">
										<div class="col-xs-10"></div>										
										<a href="#" onclick="return false">
											<div class="kebab">
												<div class="col-xs-2">
													<a data-toggle="collapse" class="collapsed"  href="#collapseAdmin">
														<figure></figure>
													    <figure class="middle"></figure>
													    <p class="cross">x</p>
													    <figure></figure>
													</a>
												</div>
											</div>
										</a>									
									</div>

									<div class="collapse" id="collapseAdmin">
										<div class="row set2">
											<div class="col-xs-12 text-center">
												<ul class="dropdown2">
											      <li><a href="http://www.g.com">Opção1</a></li>
											      <li><a href="http://www.g.com">Opção2</a></li>
											      <li><a href="http://www.g.com">Opção3</a></li>									      
											    </ul>											
											</div>
										</div>
									</div>

									<div class="collapse" id="collapseAdmin2">
										<a href="#" onclick="return false">
											<div class="row set2">
												<div class="col-xs-12 text-center">
													<span class="fal fa-terminal fa-3x"></span>
												</div>
											</div>
										</a>
										<a href="#" onclick="return false">
											<div class="row set1">
												<div class="col-xs-1"></div>
												<div class="col-xs-11"><p>Admin</p></div>
											</div>
										</a>
									</div>
								</div>	
							</div> -->
							<!-- Fim -->

							<!--Tile Home 2-->
							<div class="col-sm-2 col-xs-4 ripple-effect">
								<div class="tile tile-black">
									<div class="row set1">
										<div class="col-xs-10"></div>
										<div class="kebab">
										<a href="#" onclick="return false">
											<div class="col-xs-2">
												<figure></figure>
											    <figure class="middle"></figure>
											    <p class="cross">x</p>
											    <figure></figure>
											</div>
										</a>
										</div>
									</div>									

										
											<div class="col-xs-12 text-center">
												<ul class="dropdown2">
											      <li><a href="http://www.g.com">Opção1</a></li>
											      <li><a href="http://www.g.com">Opção2</a></li>
											      <li><a href="http://www.g.com">Opção3</a></li>
											      <li><a href="http://www.g.com">Opção4</a></li>
											    </ul>
											</div>
									

					

										<a href="#" onclick="return false">
											<div class="row set2">
												<div class="col-xs-12 text-center">
													<span class="fal fa-terminal fa-3x"></span>
												</div>
											</div>
										</a>

										<a href="#" onclick="return false">
											<div class="row set1">
												<div class="col-xs-1"></div>
												<div class="col-xs-11"><p>Admin</p></div>
											</div>
										</a>
								</div>	
							</div>



							<!--Tile teste-->
							
  
									 
									  <!-- <div class="kebab">									    
									    <ul class="dropdown2">
									      <li><a href="http://www.g.com">Opção1</a></li>
									      <li><a href="http://www.g.com">Opção2</a></li>
									      <li><a href="http://www.g.com">Opção3</a></li>
									      <li><a href="http://www.g.com">Opção4</a></li>
									    </ul>
									  </div> -->
									
  									

								
							<!-- Fim -->									




						</div>							   
					</div>
				<div class="push30"></div> 
<!-- LINKS -->



	
	<script type="text/javascript">
		
		$(document).ready(function() {

			var speed = 1500;

		    $('.display-animation').each(function() {
		       
		        $(this).children().each(function() {

		            var elementOffset = $(this).offset();
		            var offset = elementOffset.left*0.8 + elementOffset.top;
		            var delay = parseFloat(offset/speed).toFixed(2);

		            $(this)
		                .css("-webkit-animation-delay", delay+'s')
		                .css("-o-animation-delay", delay+'s')
		                .css("animation-delay", delay+'s')
		                .addClass('animated');

		        });
		    });

			//inicia mostrando o ícone
			$('#collapseAdmin2').collapse('show');

			//evento quando o menu for escondido
			$('#collapseAdmin').on('hide.bs.collapse', function () {
				$('#collapseAdmin2').collapse('show');
			});

			//evento quando o ícone for escondido
			$('#collapseAdmin').on('show.bs.collapse', function () {
				$('#collapseAdmin2').collapse('hide');
			});
		});


			(function($) {
			    $(".ripple-effect").click(function(e){
			        var rippler = $(this);

			        // criando elemento .ink se ele não existir
			        if(rippler.find(".ink").length == 0) {
			            rippler.append("<span class='ink'></span>");
			        }

			        var ink = rippler.find(".ink");

			        // previnindo cliques duplos rápidos
			        ink.removeClass("animate");

			        // set .ink diametr
			        if(!ink.height() && !ink.width())
			        {
			            var d = Math.max(rippler.outerWidth(), rippler.outerHeight());
			            ink.css({height: d, width: d});
			        }

			        // get click coordinates
			        var x = e.pageX - rippler.offset().left - ink.width()/2;
			        var y = e.pageY - rippler.offset().top - ink.height()/2;

			        // set .ink position and add class .animate
			        ink.css({
			            top: y+'px',
			            left:x+'px'
			        }).addClass("animate");
			    })
			})(jQuery);



			// JavaSricp
var kebab = document.querySelector('.kebab'),
  middle = document.querySelector('.middle'),
  cross = document.querySelector('.cross'),
  dropdown2 = document.querySelector('.dropdown2');

	kebab.addEventListener('click', function() {
  middle.classList.toggle('active');
  cross.classList.toggle('active');
  dropdown2.classList.toggle('active');
})
		
	</script>	