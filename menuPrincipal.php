<?php


	$hashLocal = mt_rand();	
	
	if( $_SERVER['REQUEST_METHOD']=='POST' )
	{
            	
		$codBusca = $_REQUEST['codBusca'];
		$nomBusca = fnLimpaCampo($_REQUEST['nomBusca']);
                
		//busca se ja tem na base para acertar a primeira gravação
		$sql1 = "select * from menuprincipal where COD_SISTEMA='".$codBusca."'";					
		$result1 = mysqli_query($connAdm->connAdm(), $sql1) or die(mysqli_error());
		$retQueryJson = mysqli_fetch_assoc($result1);
                
                
		if (isSet($_REQUEST['menuMontadoJson']))
                {
                    $campoMontadoJson = $_REQUEST['menuMontadoJson'];
               
                }
                
		$opcao = $_REQUEST['opcao'];
		$hHabilitado = $_REQUEST['hHabilitado'];
		$hashForm = $_REQUEST['hashForm'];	
		
		
// $retQueryJson['DES_MENUPRI']=="'[]'"
   
                //se tem dados
		if ($retQueryJson['DES_MENUPRI']!="'[]'" && $retQueryJson['DES_MENUPRI']!=""  ){			
			$temMenu = "sim";
                       				
			}else{
                        $temMenu = "nao";
    									
                        }		
			if ($opcao != ''){
				
				//mensagem de retorno
				switch ($opcao)
				{
					case 'CAD':
						//fnEscreve("entrou cad");	
                                         
                                            if($retQueryJson['COD_SISTEMA']!=$codBusca){
                                                
						$menuJsonCad = addslashes(str_replace(array("\n",""),array(""," "), var_export($campoMontadoJson,true))); 
                                                                                   //insere menu principal 
										   $SQLCOMMAND1="insert into menuprincipal (COD_SISTEMA,DES_MENUPRI) 
																				values
																				('".fnLimpaCampoZero($_POST['codBusca'])."','".$menuJsonCad."');";
										   mysqli_query($connAdm->connAdm(),$SQLCOMMAND1) or die(mysqli_error());
 											//fnEscreve($SQLCOMMAND1);	
                                                                                   
                                                             			

                                                                                   
						$msgRetorno = "Registro gravado com <strong>sucesso!</strong>";
                                                $temMenu = "sim";
                                                $opcao="ALT";
                                            }else{
                                                $opcao="ALT";
                                                                      //atualiza modulos do perfil master 
										   $SqlUpdate="UPDATE PERFIL SET COD_MODULOS='".$_POST['perfil']."' WHERE   COD_SISTEMA='".fnLimpaCampoZero($_POST['codBusca'])."' AND COD_EMPRESA IS NULL";
                                                                                   
                                                                                   mysqli_query($connAdm->connAdm(),$SqlUpdate) or die(mysqli_error());
 								
                                                $msgRetorno = "Registro já existe na base!";
                                                $msgTipo = 'alert-warning';
                                                
                                            }
                                            
						break;
					case 'ALT':
						//fnEscreve("entrou alt");	
						$menuJsonAlt = addslashes(str_replace(array("\n",""),array(""," "), var_export($campoMontadoJson,true))); 
											$SQLCOMMAND1="update menuprincipal set DES_MENUPRI= '".$menuJsonAlt."' where COD_SISTEMA=".$codBusca.""; 
											mysqli_query($connAdm->connAdm(),$SQLCOMMAND1) or die(mysqli_error());
 											//fnEscreve($SQLCOMMAND1);	
						
                                                 //atualiza modulos do perfil master 
                                                $SqlUpdate="UPDATE PERFIL SET COD_MODULOS='".$_POST['perfil']."' WHERE   COD_SISTEMA='".fnLimpaCampoZero($_POST['codBusca'])."' AND COD_EMPRESA IS NULL";
                                             
                                                mysqli_query($connAdm->connAdm(),$SqlUpdate) or die(mysqli_error()); 
                                                
						$msgRetorno = "Registro alterado com <strong>sucesso!</strong>";		
						break;
					case 'EXC':
						$msgRetorno = "Registro excluido com <strong>sucesso!</strong>";		
						break;

					break;
				}
                                
                                if($msgTipo==''){
				$msgTipo = 'alert-success';
                                }
			} 
		
	}
//busca o json atualizado da base 
//fnEscreve($codBusca);					
$sql = "select * from menuprincipal where COD_SISTEMA='".$codBusca."'";					
$jsonAtual = mysqli_query($connAdm->connAdm(), $sql) or die(mysqli_error());
$retQueryJsonAtual = mysqli_fetch_assoc($jsonAtual);

//carrega json da tabela
$ARRAY = REPLACE_STD_SET($retQueryJsonAtual['DES_MENUPRI']);
$menuJson= json_decode($ARRAY,true); 

//fnMostraForm();
//fnEscreve($sql1);
//echo "asdsadasd";
// print_r($result1);
//fnEscreve($msgRetorno);
	
?>  
<style type="text/css">

		.cf:after { visibility: hidden; display: block; font-size: 0; content: " "; clear: both; height: 0; }
		* html .cf { zoom: 1; }
		*:first-child+html .cf { zoom: 1; }

		p { line-height: 1.5em; }
		.small { color: #666; font-size: 0.875em; }
		.large { font-size: 1.25em; }

		/**
		 * Nestable
		 */

		.dd { position: relative; display: block; margin: 0; padding: 0; max-width: 300px; min-height: 200px; list-style: none; font-size: 13px; line-height: 20px; }

		.dd-list { display: block; position: relative; margin: 0; padding: 0; list-style: none; }
		.dd-list .dd-list { padding-left: 30px; }
		.dd-collapsed .dd-list { display: none; }

		.dd-item,
		.dd-empty,
		.dd-placeholder { display: block; position: relative; margin: 0; padding: 0; min-height: 20px; font-size: 13px; line-height: 20px; }

		.dd-handle { display: block; height: 30px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
			background: #fafafa;
			background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
			background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
			background:         linear-gradient(top, #fafafa 0%, #eee 100%);
			-webkit-border-radius: 3px;
					border-radius: 3px;
			box-sizing: border-box; -moz-box-sizing: border-box;
		}
		.dd-handle:hover { color: #2ea8e5; background: #fff; }
                .dd-nodrag { display: block; height: 30px; margin: 5px 0; padding: 5px 10px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
                 background: #fafafa;
                 background: -webkit-linear-gradient(top, #dedede 0%, #cecece 100%);
                 background:    -moz-linear-gradient(top, #dedede 0%, #cecece 100%);
                 background:         linear-gradient(top, #dedede 0%, #cecece 100%);
                 -webkit-border-radius: 3px;
                   border-radius: 3px;
                 box-sizing: border-box; -moz-box-sizing: border-box;
                }
                .dd-nodrag:hover { color: #2ea8e5; background: #dedede; }

                
		.dd-item > button { display: block; position: relative; cursor: pointer; float: left; width: 25px; height: 20px; margin: 5px 0; padding: 0; text-indent: 100%; white-space: nowrap; overflow: hidden; border: 0; background: transparent; font-size: 12px; line-height: 1; text-align: center; font-weight: bold; }
		.dd-item > button:before { content: '+'; display: block; position: absolute; width: 100%; text-align: center; text-indent: 0; }
		.dd-item > button[data-action="collapse"]:before { content: '-'; }

		.dd-placeholder,
		.dd-empty { margin: 5px 0; padding: 0; min-height: 30px; background: #f2fbff; border: 1px dashed #b6bcbf; box-sizing: border-box; -moz-box-sizing: border-box; }
		.dd-empty { border: 1px dashed #bbb; min-height: 100px; background-color: #e5e5e5;
			background-image: -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
							  -webkit-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
			background-image:    -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
								 -moz-linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
			background-image:         linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff),
									  linear-gradient(45deg, #fff 25%, transparent 25%, transparent 75%, #fff 75%, #fff);
			background-size: 60px 60px;
			background-position: 0 0, 30px 30px;
		}

		.dd-dragel { position: absolute; pointer-events: none; z-index: 9999; }
		.dd-dragel > .dd-item .dd-handle { margin-top: 0; }
		.dd-dragel .dd-handle {
			-webkit-box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
					box-shadow: 2px 4px 6px 0 rgba(0,0,0,.1);
		}

		/**
		 * Nestable Extras
		 */

		.nestable-lists { display: block; clear: both; padding: 30px 0; width: 100%; border: 0; border-top: 2px solid #ddd; border-bottom: 2px solid #ddd; }

		#nestable-menu { padding: 0; margin: 20px 0; }

		#nestable-output,
		#nestable2-output, #nestable3-output, #nestable4-output { width: 100%; height: 7em; font-size: 0.75em; line-height: 1.333333em; font-family: Consolas, monospace; padding: 5px; box-sizing: border-box; -moz-box-sizing: border-box; }

		#nestable4 .dd-handle {
			color: #fff;
			border: 1px solid #999;
			background: #bbb;
			background: -webkit-linear-gradient(top, #bbb 0%, #999 100%);
			background:    -moz-linear-gradient(top, #bbb 0%, #999 100%);
			background:         linear-gradient(top, #bbb 0%, #999 100%);
		}
		#nestable4 .dd-handle:hover { background: #bbb; }
		#nestable4 .dd-item > button:before { color: #fff; }

		.dd-hover > .dd-handle { background: #2ea8e5 !important; }

		/**
		 * Nestable Draggable Handles
		 */

		.dd3-content { display: block; height: 30px; margin: 5px 0; padding: 5px 10px 5px 40px; color: #333; text-decoration: none; font-weight: bold; border: 1px solid #ccc;
			background: #fafafa;
			background: -webkit-linear-gradient(top, #fafafa 0%, #eee 100%);
			background:    -moz-linear-gradient(top, #fafafa 0%, #eee 100%);
			background:         linear-gradient(top, #fafafa 0%, #eee 100%);
			-webkit-border-radius: 3px;
					border-radius: 3px;
			box-sizing: border-box; -moz-box-sizing: border-box;
		}
		.dd3-content:hover { color: #2ea8e5; background: #fff; }

		.dd-dragel > .dd3-item > .dd3-content { margin: 0; }

		.dd3-item > button { margin-left: 30px; }

		.dd3-handle { position: absolute; margin: 0; left: 0; top: 0; cursor: pointer; width: 30px; text-indent: 100%; white-space: nowrap; overflow: hidden;
			border: 1px solid #aaa;
			background: #ddd;
			background: -webkit-linear-gradient(top, #ddd 0%, #bbb 100%);
			background:    -moz-linear-gradient(top, #ddd 0%, #bbb 100%);
			background:         linear-gradient(top, #ddd 0%, #bbb 100%);
			border-top-right-radius: 0;
			border-bottom-right-radius: 0;
		}
		.dd3-handle:before { content: '='; display: block; position: absolute; left: 0; top: 3px; width: 100%; text-align: center; text-indent: 0; color: #fff; font-size: 20px; font-weight: normal; }
		.dd3-handle:hover { background: #ddd; }

		/**
		 * Socialite
		 */

		.socialite { display: block; float: left; height: 35px; }

    </style>
	

					<div class="push20"></div> 
					
					<div class="row">
					
						<div class="col-md12 margin-bottom-30">
							<!-- Portlet -->
							<div class="portlet portlet-bordered">
								<div class="portlet-title">
									<div class="caption">
										<i class="glyphicon glyphicon-calendar"></i>
                                        <span class="text-primary"> <?php echo $NomePg; ?> / <?php echo $nomBusca; ?></span>
									</div>
									
									<?php 
									$formBack = "1013";
									include "atalhosPortlet.php"; 
									?>
									
								</div>
								<div class="portlet-body">
								
									<?php if ($msgRetorno <> '') { ?>	
									<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									 <?php echo $msgRetorno; ?>
									</div>
									<?php } ?>	
									
									<div class="push10"></div> 
									
									<ul class="nav nav-tabs">
									  <li class="active"><a data-toggle="tab" href="#menuPrincipal">Menu Principal</a></li>
									  <li><a data-toggle="tab" href="#retJson">Matriz jSon</a></li>
									</ul>
									
									<div class="push30"></div> 
									
									<div class="login-form">
									
										<form method="POST" id="formulario" action="<?php echo $cmdPage; ?>">
										
								<div class="tab-content">
								
									<!-- aba menu principal -->
									<div id="menuPrincipal" class="tab-pane fade in active">
									
											<div class="row">
												<div class="col-md-4">											
													
													<div class="dd" id="nestable">
													<h5> Itens do Menu </h5>
														<ol class="dd-list">
														
															<?php 
															
									
																$arrMenu = array();
                                                                $arrMenuP = array();
																$sql1 = "select * from menus order by NOM_MENUSIS";
																$arrayQuery1 = mysqli_query($connAdm->connAdm(),$sql1) or die(mysqli_error());
																
																$count=0;
																while ($qrBuscaMenu = mysqli_fetch_assoc($arrayQuery1))
																	{
																	array_push($arrMenu, array("cod_menu" => $qrBuscaMenu['COD_MENUSIS'],"nom_menu" => $qrBuscaMenu['NOM_MENUSIS'] ));
                                                                                                                                        
                                                                                                                                        
                                                                                                                                        }
                                                                                                                                                            
                                                                                                                                            for($cargaM=0;$cargaM <= count($arrMenu) -1;$cargaM++)
                                                                                                                                            {
                                                                                                                                                 $tipoMenu1 = $arrMenu[$cargaM]['nom_menu'];
                                                                                                                                                 $codMenu1=$arrMenu[$cargaM]['cod_menu'];
                                                                                                                                                 $modbusca2='MEN_'.$codMenu1;
                                                                                                                                                 $mod= 'dd-handle';
                                                                                                                                                if(recursive_array_search($modbusca2,$menuJson) !== false)
                                                                                                                                                {
                                                                                                                                                    $mod='dd-nodrag';

                                                                                                                                                }
                                                                                                                                                else
                                                                                                                                                {
                                                                                                                                                    $mod='dd-handle';

                                                                                                                                                }


                                                                                                                                                                 //echo $menuPrinc;                                                                                                                                                             
                                                                                                                                                                echo '<li class="dd-item" data-id="MEN_'.$codMenu1.'">
                                                                                                                                                                                  <div class="'.$mod.'"><i class="fa fa-bars" aria-hidden="true"></i>&nbsp;'.$tipoMenu1.'</div>
                                                                                                                                                                                  </li>
                                                                                                                                                                                  ';  

                                                                                                                                                            }
                                                                                                                                                          
															
															?>													
											
														</ol>
													</div>
													
													<div class="push30"></div>
													
													<div class="dd" id="nestable2">
													<h5>Itens do Sub Menu</h5>
														<ol class="dd-list">
														
															<?php 
																
																$arrSub = array();
																$sql2 = "select * from submenus order by nom_submenus";
																$arrayQuery2 = mysqli_query($connAdm->connAdm(),$sql2) or die(mysqli_error());
																
																$count=0;
																while ($qrBuscaSubMenu = mysqli_fetch_assoc($arrayQuery2))
																	{										
																	array_push($arrSub, array("cod_sub" => $qrBuscaSubMenu['COD_SUBMENUS'],"nom_sub" => $qrBuscaSubMenu['NOM_SUBMENUS'] ));
                                                                                                                                        }
															
                                                                                                                                       
                                        
                                                                                                                                       for($cargaS=0;$cargaS <= count($arrSub) -1;$cargaS++)
                                                                                                                                       {
                                                                                                                                                $tipoSUB = $arrSub[$cargaS]['nom_sub'];
                                                                                                                                                $codSUB = $arrSub[$cargaS]['cod_sub'];
                                                                                                                                                $modbusca1='SUB_'.$codSUB;
                                                                                                                                                $mod ='dd-handle';
                                                                                                                                               
                                                                                                                                              if(recursive_array_search($modbusca1,$menuJson) !== false)
                                                                                                                                                {
                                                                                                                                                    $mod='dd-nodrag';

                                                                                                                                                }
                                                                                                                                                else
                                                                                                                                                {
                                                                                                                                                    $mod='dd-handle';

                                                                                                                                                }

                                                                                                                                               
                                                                                                                                                        
                                                                                                                                                        
                                                                                                                                                    
                                                                                                                                                
                                                                                                                                               // echo $VerificaSub;
                                                                                                                                                echo '<li class="dd-item" data-id="SUB_'.$codSUB.'">
                                                                                                                                                        <div class="'.$mod.'"><i class="fa fa-list" aria-hidden="true"></i>&nbsp;'.$tipoSUB.'</div>
                                                                                                                                                        </li>	
                                                                                                                                                    ';   


                                                                                                                                            }
                                                                                                                                  ?>	
												
														</ol>
													</div>													
														
												</div>
												<div class="col-md-4">
																					
													<div class="dd" id="nestable3">
													<h5>Módulos</h5>
														<ol class="dd-list">
														
															<?php 
																
																$arrMod = array();
																$sql3 = "select * from modulos order by DES_MODULOS";
																$arrayQuery3 = mysqli_query($connAdm->connAdm(),$sql3) or die(mysqli_error());
																
																$count=0;
																while ($qrBuscaModulo = mysqli_fetch_assoc($arrayQuery3))
																	{										
																	array_push($arrMod, array("cod_mod" => $qrBuscaModulo['COD_MODULOS'],"nom_mod" => $qrBuscaModulo['NOM_MODULOS'] ));
				 
																	}

                                                                                                                                        for($cargaM=0; $cargaM <= count($arrMod) -1; $cargaM++)
                                                                                                                                        {

                                                                                                                                                $tipoM = $arrMod[$cargaM]['nom_mod'];
                                                                                                                                                $codM = $arrMod[$cargaM]['cod_mod'];
                                                                                                                                                $modbusca='MOD_'.$codM;
                                                                                                                                                $mod='dd-handle';
                                                                                                                                                if(recursive_array_search($modbusca,$menuJson) !== false)
                                                                                                                                                {
                                                                                                                                                    $mod='dd-nodrag';

                                                                                                                                                }
                                                                                                                                                else
                                                                                                                                                {
                                                                                                                                                    $mod='dd-handle';

                                                                                                                                                }
	
                                                                                                                                          echo' <li class="dd-item" data-id="MOD_'.$codM.'">
																																					<div class="'.$mod.'"  style="overflow: hidden; text-overflow: ellipsis;"><i class="fa fa-caret-right" aria-hidden="true"></i>&nbsp; ('.$codM.") ".$tipoM.' </div>
																																				</li>
                                                                                                                                            ';



                                                                                                                                        }
                                                                                                                                        


                                                                                                                                     
                                                                                                                                         
                                                                                                                                        
															
															?>	
									
														</ol>
													</div>												
												
												</div>
												
												
												<div class="col-md-4">
																						
														<div class="dd clone" id="nestable4">
															<h5> Navegação / Menu  </h5>
															<ol class="dd-list">
															
															  <?php if ($temMenu == "nao") { 
															
                                                                                                                                  echo '<li class="dd-item" data-id="MEN_0000">
																	<div class="dd-handle"><i class="fa fa-list" aria-hidden="true"></i>&nbsp; Monte aqui seu menu</div>
																</li>';
															   } else { 
                                          
                                                                                                                            
//construcão do menu
for ($i = 0; $i <= count($menuJson) - 1; $i++) 
{                      
    $tipoMenu = substr($menuJson[$i]['id'],0,3);
    $codMenu = substr($menuJson[$i]['id'],4,5);
    $idMenu = $menuJson[$i]['id'];
    if($tipoMenu=='MEN'){
        $icoMenu3 = "fa fa-bars";
        $vl=( array_search($codMenu, array_column( $arrMenu, 'cod_menu' ) ) );
        $menuV =$arrMenu[$vl]['nom_menu'];
        echo '<li class="dd-item" data-id="'.$idMenu.'">
                <div class="dd-handle"><i class="'.$icoMenu3.'" aria-hidden="true"></i>&nbsp;'.$menuV.'</div>
                    <ol class="dd-list" style="">';  
                     //Sub menu Loop  o ol acima inicia o sub menu                 
                    for ($sub = 0; $sub <= count($menuJson[$i]['children']) -1; $sub++) 
                    {
                        $tipoMenu2 = substr($menuJson[$i]['children'][$sub]['id'],0,3);
                        $codMenu2 = substr($menuJson[$i]['children'][$sub]['id'],4,5);
                        $idMenu1 = $menuJson[$i]['children'][$sub]['id'];
                            if($tipoMenu2=='SUB'){
                                $icoMenu3 = "fa fa-bars";
                                $vl=( array_search($codMenu2, array_column( $arrSub, 'cod_sub' ) ) );
                                $menuVs =$arrSub[$vl]['nom_sub'];
                                
                                    echo'  <li class="dd-item" data-id="'.$idMenu1.'">
                                                 <div class="dd-handle"><i class="'.$icoMenu3.'" aria-hidden="true"></i>&nbsp;'.$menuVs.'</div>
                                                    <ol class="dd-list" style=""> 
                                                  ';
                                                  //modulo Loop ol acima começa o modulo do sub menu
                                                    for ($submod = 0; $submod <= count($menuJson[$i]['children'][$sub]['children']) -1; $submod++) 
                                                    {
                                                        $tipoMenu3 = substr($menuJson[$i]['children'][$sub]['children'][$submod]['id'],0,3);
                                                        $codMenu3 = substr($menuJson[$i]['children'][$sub]['children'][$submod]['id'],4,5);
                                                        $idMenu3 = $menuJson[$i]['children'][$sub]['children'][$submod]['id'];
                                                        
                                                        if($tipoMenu3=='MOD'){
                                                            $icoMenu3 = "fa fa-caret-right";
                                                            $vl=( array_search($codMenu3, array_column($arrMod, 'cod_mod' ) ) );
                                                            $menuV =$arrMod[$vl]['nom_mod'];
                                                            $codV =$arrMod[$vl]['cod_mod'];
                                                                echo'  
                                                                    
                                                                           <li class="dd-item" data-id="'.$idMenu3.'">
                                                                              <div class="dd-handle"><i class="'.$icoMenu3.'" aria-hidden="true"></i>&nbsp; ('.$codV.') '.$menuV.'</div>
                                                                           </li>
                                                                   
                                                                   
                                                                '; 
                                                        }
                                                    }    
                                                
                                    echo'           </ol>
                                          </li>
                                        ';
                            }  
                          
                            //Modulo sozinho no sub menu
                            if($tipoMenu2=='MOD'){
                                $icoMenu3 = "fa fa-caret-right";
                                $vl=( array_search($codMenu2, array_column( $arrMod, 'cod_mod' ) ) );
                                $menuVs =$arrMod[$vl]['nom_mod'];
                                $codVs =$arrMod[$vl]['cod_mod'];
                               
                                echo' <li class="dd-item" data-id="'.$idMenu1.'">
                                         <div class="dd-handle"><i class="'.$icoMenu3.'" aria-hidden="true"></i>&nbsp; ('.$codVs.') '.$menuVs.'</div>
                                     </li>';
                            } 
                            
                    }         
        echo'       </ol>
            </li>';
    }
//modulo no menu principal sozinho
   if($tipoMenu=='MOD'){
        
        $icoMenu3 = "fa fa-caret-right";
        $vl=( array_search($codMenu, array_column( $arrMod, 'cod_mod' ) ) );
        $menuV =$arrMod[$vl]['nom_mod'];
        echo' <li class="dd-item" data-id="'.$idMenu.'">
                 <div class="dd-handle"><i class="'.$icoMenu3.'" aria-hidden="true"></i>&nbsp;'.$menuV.'</div>
                </li>';
    } 
   //Sub Menu na principal do modulo
    //vai ter?
} 
                          

                                                                                                                             
                                                                                                                               
//
for($cargaM=0; $cargaM <= count($arrMod) -1; $cargaM++)
{
//substr($cod_sistemas,0,-1);
        $tipoM = $arrMod[$cargaM]['nom_mod'];
        $codM = $arrMod[$cargaM]['cod_mod'];
        $modbusca='MOD_'.$codM;
        $mod='dd-handle';
        if(recursive_array_search($modbusca,$menuJson) !== false)
        {
          //  $teste=$modbusca='MOD_'.$codM;
            $perfilmaster.=$codM.",";
        
        }
        else
        {
          $teste="";  

        }
      
      $perfilmaster1=substr($perfilmaster,0,-1);
}



                                        
                                                                                                                   }?>
															

                                                                                                                            
                                                                                                                            
                                                                                                                            
															</ol>
															
														</div>											
												
												</div>
											</div>
											
											<div class="push30"></div>
											<hr>	
											<div class="form-group text-right col-lg-12">
												
												  <button type="reset" class="btn btn-default"><i class="fa fa-star-half-o" aria-hidden="true"></i>&nbsp; Apagar</button>
												  <?php if ($temMenu == "nao") { ?>
												  <button type="submit" name="CAD" id="CAD" class="btn btn-primary getBtn"><i class="fa fa-plus" aria-hidden="true"></i>&nbsp; Cadastrar</button>
												  <?php } else { ?>
												  <button type="submit" name="ALT" id="ALT" class="btn btn-primary getBtn"><i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Alterar</button>
												  <?php } ?>
												
											</div>	
											
											<div class="push5"></div> 

								</div>
								<!-- fim aba menu principal -->
								
								<!-- aba retJson -->
								<div id="retJson" class="tab-pane fade in">
								
									
											<div style="display:none;">
											<textarea id="nestable-output"></textarea>
											<textarea id="nestable2-output"></textarea>
											<textarea id="nestable3-output"></textarea>
											</div>
                                           
                                            <textarea id="nestable4-output" name="menuMontadoJson"><?php echo $retQueryJsonAtual['DES_MENUPRI']; ?></textarea>
                                          
											
											<div class="push100"></div> 

								</div>
								<!-- fim retJson -->
							
							</div><!-- fim tab-content -->	
							
										
										<!-- variaveis do sistema escolhido -->
                                                                                <input type="hidden" name="perfil" id="codBusca" value="<?php echo $perfilmaster1; ?>">
										<input type="hidden" name="codBusca" id="codBusca" value="<?php echo $codBusca; ?>">
										<input type="hidden" name="nomBusca" id="nomBusca" value="<?php echo $nomBusca; ?>">
										<input type="hidden" name="opcao" id="opcao" value="">
										<input type="hidden" name="hashForm" id="hashForm" value="<?php $hashLocal; ?>" />	
										<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">								
				
										</form>										
									
									</div>								
								
								</div>
							</div>
							<!-- fim Portlet -->
						</div>
						
					</div>					
						
					<div class="push20"></div> 
	
<script src="js\plugins\jquery.nestable.js"></script>
<script>

$(document).ready(function()
{

	$("#shortRFH").prop('disabled', true);
	$("#shortRFH").addClass("disabled");
	

    var updateOutput = function(e)
    {
        var list   = e.length ? e : $(e.target),
            output = list.data('output');
        if (window.JSON) {
            output.val(window.JSON.stringify(list.nestable('serialize')));//, null, 2));

        } else {
            output.val('JSON browser support required for this demo.');
        }
    };

    // activate Nestable for list 1
    $('#nestable').nestable({
        group: 1
    })
    //.on('change', updateOutput);

    // activate Nestable for list 2
    $('#nestable2').nestable({
        group: 1
    })
    //.on('change', updateOutput);
	
	// activate Nestable for list 3
    $('#nestable3').nestable({
        group: 1
    })
    //.on('change', updateOutput);

	// activate Nestable for list 4
    $('#nestable4').nestable({
        group: 1
    })
    .on('change', updateOutput);
	
    // output initial serialised data
    updateOutput($('#nestable').data('output', $('#nestable-output')));
    updateOutput($('#nestable2').data('output', $('#nestable2-output')));
    updateOutput($('#nestable3').data('output', $('#nestable3-output')));
    updateOutput($('#nestable4').data('output', $('#nestable4-output')));
    

    $('#nestable-menu').on('click', function(e)
    {
        var target = $(e.target),
            action = target.data('action');
        if (action === 'expand-all') {
            $('.dd').nestable('expandAll');
        }
        if (action === 'collapse-all') {
            $('.dd').nestable('collapseAll');
        }
    });


});

</script>
<?php

//Update base Perfil's
//atualiza modulos do perfil master 
$SqlUpdate1="UPDATE PERFIL SET COD_MODULOS='".$perfilmaster1."' WHERE   COD_SISTEMA='".fnLimpaCampoZero($_POST['codBusca'])."' AND COD_EMPRESA IS NULL";
mysqli_query($connAdm->connAdm(),$SqlUpdate1) or die(mysqli_error());

?>