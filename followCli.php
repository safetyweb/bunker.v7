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

			$cod_grupotr = fnLimpaCampoZero($_REQUEST['COD_GRUPOTR']);
			$des_grupotr = fnLimpaCampo($_REQUEST['DES_GRUPOTR']);
			$cod_empresa = fnLimpaCampo($_REQUEST['COD_EMPRESA']);

			$opcao = $_REQUEST['opcao'];
			$hHabilitado = $_REQUEST['hHabilitado'];
			$hashForm = $_REQUEST['hashForm'];
						
			if ($opcao != ''){

				$sql = "CALL SP_ALTERA_GRUPOTRABALHO (
				 '".$cod_grupotr."', 
				 '".$des_grupotr."', 
				 '".$cod_empresa."', 
				 '".$opcao."'    
				) ";
				
				//echo $sql;
				
				mysqli_query($connAdm->connAdm(),trim($sql)) or die(mysqli_error());				
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";	
						break;
					case 'ALT':
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;
					break;
				}			
				$msgTipo = 'alert-success';
				
			}  	

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

?>
		
					<?php if ($popUp != "true"){ ?>
						<div class="push30"></div> 
					<?php } ?>
					
					<div class="row">				
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<?php if ($popUp != "true"){  ?>							
							<div class="portlet portlet-bordered">
							<?php } else { ?>
							<div class="portlet" style="padding: 0 20px 20px 20px;" >
							<?php } ?>
							
								<?php if ($popUp != "true"){  ?>
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
										<span class="text-primary"><?php echo $NomePg; ?></span>
									</div>
									<?php include "atalhosPortlet.php"; ?>
								</div>
								<?php } ?>
								
								<div class="portlet-body">
									
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>

									<?php if ($popUp != "true"){ ?>
										<?php $abaCli = 1054; include "abasClienteConfig.php"; ?>
									<?php } ?>
																		
									
									<div class="push30"></div> 

<style>

/**
 * Colors:
 *
 * - light-blue: rgb(107, 191, 238)
 * - dark-blue: rgb(52, 148, 203)
 * - grayish blue: #BDD0DC (time)
 */

ol.timeline {
    border-left: 5px solid;
    border-color: rgb(107, 191, 238);
    padding-left: 38px;
    margin-left: 8em;
    list-style: none;
}

.timeline > li {
    position: relative;
    margin-top: 10pt;
    color: white;
}

.timeline > li:before {
    background-color: rgb(52, 148, 203);
    text-align: center;

    width: 35px; 
	height: 35px;
    line-height: 35px;

    font-size: 110%;

    border: 0.5em solid rgb(107, 191, 238);
    border-radius: 50%;

    position: absolute;
    left: -3.5em;
}
.timeline > li.call:before {
    content: "\260E";
}
.timeline > li.flight:before {
    content: '\2708';
}
.timeline > li.todo:before {
    content: '\2714';
}
.timeline > li.email:before {
    content: '\0040';
}

.timeline > li time {
    display: block;
    float: left;
    position: absolute;
    left: -9em;
    text-align: right;
}
.timeline > li time > * {
    display: block;
}
.timeline > li time small {
    color: #BDD0DC;
    font-size: 80%;
}
.timeline > li time big {
    color: rgb(107, 191, 238);
    font-size: 200%;
}
.timeline > li:nth-child(even) time big {
    color: rgb(52, 148, 203); /* dark blue */
}

.timeline > li article {
    background-color: rgb(107, 191, 238);
    margin: 0; 
	padding: 1px 30px 20px 30px;
    border-radius: 5pt;
}
.timeline > li article:after {
    content: "\25C0";
    color: rgb(107, 191, 238);
    position: absolute;
    top: 0.75em; left: -0.6em;
}
/* http://css-tricks.com/how-nth-child-works/ */
.timeline > li:nth-child(even) article {
    background-color: rgb(52, 148, 203); /* dark blue */
}
.timeline > li:nth-child(even) article:after {
    color: rgb(52, 148, 203); /* dark blue */
}

.timeline > li article h3 {
    padding-bottom: 5pt;
    border-bottom: 1pt dotted;
    margin-bottom: 10pt;
}

</style>									
									

      <ol class="timeline">
        <li class="call">
          <time><small>4/10/2016</small> <big>18:30</big></time>
          <article>
            <h3>Máquina não suporta rodar o Fidelidade</h3>

            <p>
            Winter purslane courgette pumpkin quandong komatsuna fennel green bean cucumber watercress. Winter purslane garbanzo artichoke broccoli lentil corn okra silver beet celery quandong. Plantain salad beetroot bunya nuts black-eyed pea collard greens radish water spinach gourd chicory prairie turnip avocado sierra leone bologi.
            </p>
          </article>
        </li>
        <li class="flight">
          <time><small>4/11/2016</small> <big>12:04</big></time>
          <article>
            <h3>Experimentando o suporte</h3>

            <p>
            Caulie dandelion maize lentil collard greens radish arugula sweet pepper water spinach kombu courgette lettuce. No, not again. I... why does it say paper jam when there is no paper jam? I swear to God, one of these days, I just kick this piece of shit out the window. Celery coriander bitterleaf epazote radicchio shallot winter purslane collard greens spring onion squash lentil. Artichoke salad bamboo shoot black-eyed pea brussels sprout garlic kohlrabi.
            </p>
          </article>
        </li>
        <li class="todo">
          <time><small>4/12/2016</small> <big>05:36</big></time>
          <article>
            <h3>Problemas de Integração</h3>

          <p>
          Parsnip lotus root celery yarrow seakale tomato collard greens tigernut epazote ricebean melon tomatillo soybean chicory broccoli beet greens peanut salad. Lotus root burdock bell pepper chickweed shallot groundnut pea sprouts welsh onion wattle seed pea salsify turnip scallion peanut arugula bamboo shoot onion swiss chard. Avocado tomato peanut soko amaranth grape fennel chickweed mung bean soybean endive squash beet greens carrot chicory green bean. <strong>What? You pooped in the refrigerator? And you ate the whole wheel of cheese? How’d you do that? Heck, I’m not even mad; that’s amazing.</strong> Quandong pea chickweed tomatillo quandong cauliflower spinach water spinach.
          </p>
          </article>
        </li>
        <li class="email">
          <time><small>15/5/2016</small> <big>13:15</big></time>
          <article>
            <h3>Falha na Webservice</h3>

            <p>
            Peanut gourd nori welsh onion rock melon mustard jícama. Desert raisin amaranth kombu aubergine kale seakale brussels sprout pea. Black-eyed pea celtuce bamboo shoot salad kohlrabi leek squash prairie turnip catsear rock melon chard taro broccoli turnip greens. Slappin' the base. Fennel quandong potato watercress ricebean swiss chard garbanzo. Endive daikon brussels sprout lotus root silver beet epazote melon shallot.
            </p>
          </article>
        </li>
        <li class="call">
          <time><small>14/6/2016</small> <big>21:30</big></time>
          <article>
            <h3>Como gerar relatório CRM</h3>

            <p>
            Ultron approves.Parsley amaranth tigernut silver beet maize fennel spinach. Ricebean black-eyed pea maize scallion green bean spinach cabbage jícama bell pepper carrot onion corn plantain garbanzo. NEMO! Sierra leone bologi komatsuna celery peanut swiss chard silver beet squash dandelion maize chicory burdock tatsoi dulse radish wakame beetroot.
            </p
          </article>
        </li>
        <li class="flight">
          <time><small>24/7/2016</small> <big>12:11</big></time>
          <article>
          <h3>Perfil Administrador</h3>

          <p>
          Caulie dandelion maize lentil collard greens radish arugula sweet pepper water spinach kombu courgette lettuce. GOJIRA! Celery coriander bitterleaf epazote radicchio shallot winter purslane collard greens spring onion squash lentil. Artichoke salad bamboo shoot black-eyed pea brussels sprout garlic kohlrabi.
          </p>
          </article>
        </li>
        <li class="todo">
          <time><small>12/8/2016</small> <big>09:56</big></time>
          <article>
            <h3>Lentidão na rede</h3>

            <p>
            <strong>Destaque</strong>. Parsnip lotus root celery yarrow seakale tomato collard greens tigernut epazote ricebean melon tomatillo soybean chicory broccoli beet greens peanut salad. Lotus root burdock bell pepper chickweed shallot groundnut pea sprouts welsh onion wattle seed pea salsify turnip scallion peanut arugula bamboo shoot onion swiss chard. Avocado tomato peanut soko amaranth grape fennel chickweed mung bean soybean endive squash beet greens carrot chicory green bean. Tigernut dandelion sea lettuce garlic daikon courgette celery maize parsley komatsuna black-eyed pea bell pepper aubergine cauliflower zucchini. Quandong pea chickweed tomatillo quandong cauliflower spinach water spinach.
            </p>
          </article>
        </li>
      </ol>

  
	
										
									<div class="push50"></div>
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
					
	<link rel="stylesheet" href="fonts/font-awesome-4.2.0/css/font-awesome.min.css"/>
	<link rel="stylesheet" href="js/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>
	
	<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/iconset/iconset-fontawesome-4.2.0.min.js"></script>
	<script type="text/javascript" src="js/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js"></script>
    
	<script src="js/plugins/minicolors/jquery.minicolors.min.js"></script>
    <link rel="stylesheet" href="js/plugins/minicolors/jquery.minicolors.css">
	
	<script type="text/javascript">
		
        $(document).ready( function() {
			
			//color picker
			$('.pickColor').minicolors({
				control: $(this).attr('data-control') || 'hue',				
				theme: 'bootstrap'
			});
			
			//icon picker
			$('.btnSearchIcon').iconpicker({ 
				cols: 8,
				iconset: 'fontawesome',   
				rows: 6,
				searchText: 'Procurar  &iacute;cone'
			});	
			
			$('.btnSearchIcon').on('change', function(e) { 
				//console.log(e.icon);
				$("#icone").val(e.icon);		
			});	
			
        });
		
		function retornaForm(index){
			$("#formulario #COD_GRUPOTR").val($("#ret_COD_GRUPOTR_"+index).val());
			$("#formulario #DES_GRUPOTR").val($("#ret_DES_GRUPOTR_"+index).val());
			$('#formulario').validator('validate');			
			$("#formulario #hHabilitado").val('S');						
		}
		
	</script>		