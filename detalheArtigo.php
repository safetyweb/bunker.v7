<?php
    
    //echo fnDebug('true');

    $hashLocal = mt_rand(); 
    
    if( $_SERVER['REQUEST_METHOD']=='POST' )
    {
        $request = md5( implode( $_POST ) );
        
        if( isset( $_SESSION['last_request'] ) && $_SESSION['last_request']== $request )
        {
            $msgRetorno = 'Essa página já foi utilizada';
            $msgTipo = 'alert-warning';
            // var_dump($msgTipo);
        }
        else
        {
            $_SESSION['last_request']  = $request;            
            $cod_artigo = fnLimpaCampoZero($_REQUEST['COD_ARTIGO']);
            $cod_categor = fnLimpaCampoZero($_REQUEST['COD_CATEGOR']);
            $cod_subcategor = fnLimpaCampoZero($_REQUEST['COD_SUBCATEGOR']);
            $nom_modulos = fnLimpaCampoZero($_REQUEST['NOM_MODULOS']);
            $nom_artigo = fnLimpaCampo($_REQUEST['NOM_ARTIGO']);
            $des_artigo = addslashes(htmlentities($_REQUEST['DES_ARTIGO']));
            $des_chamada = fnLimpaCampo($_REQUEST['DES_CHAMADA']);
            $des_imagem = fnLimpaCampo($_REQUEST['DES_IMAGEM']);
            $des_urlvideo = fnLimpaCampo($_REQUEST['DES_URLVIDEO']);
            $des_anexo1 = fnLimpaCampo($_REQUEST['DES_ANEXO1']);
            $des_anexo2 = fnLimpaCampo($_REQUEST['DES_ANEXO2']);
            $des_anexo3 = fnLimpaCampo($_REQUEST['DES_ANEXO3']);
            if (empty($_REQUEST['LOG_ATIVO'])) {$log_ativo='N';}else{$log_ativo=$_REQUEST['LOG_ATIVO'];}
            if (empty($_REQUEST['LOG_DESTAQUE'])) {$log_destaque='N';}else{$log_destaque=$_REQUEST['LOG_DESTAQUE'];}
            
            $cod_usucada = $_SESSION['SYS_COD_USUARIO'];
       
            $opcao = $_REQUEST['opcao'];
            $hHabilitado = $_REQUEST['hHabilitado'];
            $hashForm = $_REQUEST['hashForm'];          
            
            if ($opcao != ''){                
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
                                                
    }else{
        $cod_empresa = 0;       
        //fnEscreve('entrou else');
    }

    // echo "_".$cod_empresa;


    if(isset($_GET['idA'])){
        $cod_artigo = fnDecode($_GET['idA']);
		
		if ($cod_artigo == 0){
			
			$cod_busca = fnDecode($_GET['idC']);
        
			$sql = "SELECT AT.*, CT.DES_CATEGOR, CT.DES_ICONE, CT.DES_COR, ST.DES_SUBCATEGOR,  ST.DES_ICONE SUB_ICONE,  ST.DES_COR SUB_COR, M.NOM_MODULOS
					FROM ARTIGO_TUTORIAL AT 
					LEFT JOIN CATEGORIA_TUTORIAL CT ON CT.COD_CATEGOR = AT.COD_CATEGOR
					LEFT JOIN SUBCATEGORIA_TUTORIAL ST ON ST.COD_SUBCATEGOR = AT.COD_SUBCATEGOR
					LEFT JOIN MODULOS M ON M.COD_MODULOS = AT.COD_MODULOS
					WHERE COD_ARTIGO = (select max(COD_ARTIGO) from ARTIGO_TUTORIAL where COD_CATEGOR = $cod_busca) ";
			
		} else {
			
			$sql = "SELECT AT.*, CT.DES_CATEGOR, CT.DES_ICONE, CT.DES_COR, ST.DES_SUBCATEGOR,  ST.DES_ICONE SUB_ICONE,  ST.DES_COR SUB_COR, M.NOM_MODULOS
					FROM ARTIGO_TUTORIAL AT 
					LEFT JOIN CATEGORIA_TUTORIAL CT ON CT.COD_CATEGOR = AT.COD_CATEGOR
					LEFT JOIN SUBCATEGORIA_TUTORIAL ST ON ST.COD_SUBCATEGOR = AT.COD_SUBCATEGOR
					LEFT JOIN MODULOS M ON M.COD_MODULOS = AT.COD_MODULOS
					WHERE COD_ARTIGO = $cod_artigo";
			
		}



        $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
        $qrArtigo = mysqli_fetch_assoc($arrayQuery);
        // var_dump($qrArtigo);
		
		//fnEscreve($cod_artigo);
        
        $cod_artigo = $qrArtigo['COD_ARTIGO'];
        $cod_categor = $qrArtigo['COD_CATEGOR'];
        $cod_subcategor = $qrArtigo['COD_SUBCATEGOR'];
        $nom_modulos = $qrArtigo['NOM_MODULOS'];        
        $nom_artigo = $qrArtigo['NOM_ARTIGO'];
        $des_artigo = $qrArtigo['DES_ARTIGO'];
        $des_categor = $qrArtigo['DES_CATEGOR'];
        $des_chamada = $qrArtigo['DES_CHAMADA'];
        $des_imagem = $qrArtigo['DES_IMAGEM'];
        $des_urlvideo = $qrArtigo['DES_URLVIDEO'];
        $des_anexo1 = $qrArtigo['DES_ANEXO1'];        
        $des_anexo2 = $qrArtigo['DES_ANEXO2'];        
        $des_anexo3 = $qrArtigo['DES_ANEXO3'];

        if ($qrArtigo['LOG_ATIVO']=='S') {
            $log_ativo= "<span class='fas fa-check text-success'></span>";
            }else{
            $log_ativo = "<span class='fas fa-times text-danger'></span>";
            }        

        if ($qrArtigo['LOG_DESTAQUE']=='S') {
            $log_destaque= "<span class='fas fa-check text-success'></span>";
            }else{
            $log_destaque = "<span class='fas fa-times text-danger'></span>";
            }

    }else{

        $cod_artigo = 0;
        $cod_categor = "";
        $cod_subcategor = "";
        $nom_modulos = "";        
        $nom_artigo = "";
        $des_artigo = "";
        $des_categor = "";
        $des_chamada = "";
        $des_imagem = "";
        $des_urlvideo = "";
        $des_anexo1 = "";        
        $des_anexo2 = "";        
        $des_anexo3 = "";
        $log_ativo="";
        $log_destaque=""; 

    }
    
    //fnMostraForm(); 

?>
    
                    <div class="push30"></div>
                    
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
                                                                                                            
                                    <div class="push30"></div>

                                    <div class="row">

                                        <div class="col-md-2 col-md-offset-1">

											<h3>Categorias</h3>
                                            
											<div class="push10"></div>
                                            
                                            <?php 
                                                    
                                                if ($_SESSION[SYS_COD_EMPRESA] != 2 && $_SESSION[SYS_COD_EMPRESA] != 3) {
                                                    $sqlWhere = " WHERE (LOG_PUBLICO = 'S' OR (LOG_PUBLICO = 'N' AND POSITION(',{$cod_empresa},' IN CONCAT(',,' ,ifnull(COD_MULTEMP,0) ,','))>0))";
                                                }

                                                $sql = "SELECT COD_CATEGOR, DES_CATEGOR, DES_COR, DES_ICONE, LOG_PUBLICO FROM CATEGORIA_TUTORIAL $sqlWhere
                                                           ORDER BY NUM_ORDENAC";

                                                $arrayQuery = mysqli_query($connAdm->connAdm(),$sql) or die(mysqli_error());
                                                
                                                $count=0;
                                                while ($qrCat = mysqli_fetch_assoc($arrayQuery)){
                                            ?>
											
												
												<a href="action.php?mod=<?php echo fnEncode(1481)?>&idA=<?php echo fnEncode(0)?>&idC=<?= fnEncode($qrCat['COD_CATEGOR'])?>">
                                                    &rsaquo; <?=$qrCat['DES_CATEGOR']?>
                                                </a> 
                                                <div class="push10"></div>

                                            <?php
                                                }
                                            ?>

                                        </div>

                                        <div class="col-md-8">

                                            <div class="row">

                                                <div class="col-md-8">                                           
                                                    
                                                    <h3>#<?= $qrArtigo['COD_ARTIGO']." - ".$qrArtigo['NOM_ARTIGO']?></h3>
                                                    <h4><?= $qrArtigo['DES_CHAMADA'] ?></h4>
                                                     
                                                </div>
												
                                            </div>
                                            
                                            <div class="push10"></div>
                                            <div class="row">

                                                <div class="col-md-12">                                            
                                                    <span>Categoria: &nbsp;<b><?= $qrArtigo['DES_CATEGOR'] ?></b>
													&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	
                                                    <span>Subcategoria: &nbsp;<b><?= $qrArtigo['DES_SUBCATEGOR'] ?></b>
                                                                                                                                               
                                                </div>
                                                
                                            </div>

                                            <?php

                                                if($des_urlvideo != ""){

                                                    if(strstr( $des_urlvideo, '&' )){

                                                        $arr = explode("&", $des_urlvideo,2);
                                                        $des_urlvideo = $arr[0]."?rel=0";

                                                    }

                                                    if(strstr( $des_urlvideo, 'watch?v=' )){

                                                        $des_urlvideo = str_replace("watch?v=", "embed/", $des_urlvideo)."?rel=0";

                                                    }


                                            ?>

                                                <div class="push50"></div>

                                                <div class="row">
                                                    
                                                    <div class="col-md-12">
                                                        <div class="embed-responsive embed-responsive-16by9">
                                                            <iframe class="embed-responsive-item" src="<?=$des_urlvideo?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="margin-left: auto; margin-right: auto;"></iframe>
                                                        </div>
                                                    </div>

                                                </div>

                                            <?php 


                                                }else if($des_imagem != ""){

                                            ?>

                                                <div class="push50"></div>

                                                <div class="row">
                                                    
                                                    <div class="col-md-12">
                                                        <div class="embed-responsive embed-responsive-16by9">
                                                            <img class="img-fluid" src="media/clientes/0/artigo/<?=$des_imagem?>" alt="Sem Imagem" style="margin-left: auto; margin-right: auto; width: 100%;">
                                                        </div>
                                                    </div>

                                                </div>

                                            <?php

                                                }

                                            ?>

                                            <div class="push30"></div>

                                            <div class="row">
                                                <div class="col-md-12">                                           
                                                    <h5>Descrição</h5>
                                                    <div class="push10"></div>
                                                        <?= html_entity_decode($qrArtigo['DES_ARTIGO']); ?>                                                                                                                                       
                                                </div> 
                                            </div>
											
                                            <div class="push30"></div>

                                            <?php

                                                if($qrArtigo['DES_ANEXO1'] !=  "" || $qrArtigo['DES_ANEXO2'] !=  "" || $qrArtigo['DES_ANEXO3'] !=  ""){


                                            ?>

                                                <div class="row">

                                                    <div class="col-md-12">

                                                        <?php

                                                            if($qrArtigo['DES_ANEXO1'] != ""){

                                                        ?>
                                                        
                                                        <tr>
                                                            <td><a href="media/clientes/0/artigo/<?=$qrArtigo[DES_ANEXO1]?>" download><span class="fa fa-download"></span></a></td>
                                                            <td>&nbsp;<small><?php echo $qrArtigo['DES_ANEXO1']; ?></small></td>
                                                        </tr>

                                                        <?php 

                                                            }

                                                            if($qrArtigo['DES_ANEXO2'] != ""){

                                                        ?>
                                                        
                                                        <tr>
                                                            <td><a href="media/clientes/0/artigo/<?=$qrArtigo[DES_ANEXO2]?>" download><span class="fa fa-download"></span></a></td>
                                                            <td>&nbsp;<small><?php echo $qrArtigo['DES_ANEXO2']; ?></small></td>
                                                        </tr>

                                                        <?php 

                                                            }

                                                            if($qrArtigo['DES_ANEXO3'] != ""){

                                                        ?>
                                                        
                                                        <tr>
                                                            <td><a href="media/clientes/0/artigo/<?=$qrArtigo[DES_ANEXO3]?>" download><span class="fa fa-download"></span></a></td>
                                                            <td>&nbsp;<small><?php echo $qrArtigo['DES_ANEXO3']; ?></small></td>
                                                        </tr>

                                                        <?php 

                                                            }

                                                        ?>

                                                    </div>

                                                </div>

                                                <div class="push30"></div>

                                            <?php 

                                                }

                                            ?>

                                            <div class="row">
                                                <div class="col-md-12">                                           
                                                    <h4>Artigos Relacionados: </h4>													
							
														<?php 

														$sql2 = "SELECT COD_ARTIGO, NOM_ARTIGO FROM ARTIGO_TUTORIAL WHERE COD_CATEGOR = $cod_categor and COD_ARTIGO <> $cod_artigo ORDER BY NOM_ARTIGO";

														//fnEscreve($sql2);
														$arrayQueryArt = mysqli_query($connAdm->connAdm(),$sql2);

														while ($qrArt = mysqli_fetch_assoc($arrayQueryArt)){
															?>
																											
															<a href="action.php?mod=<?php echo fnEncode(1481)?>&idA=<?php echo fnEncode($qrArt[COD_ARTIGO])?>">&rsaquo; <?=$qrArt['NOM_ARTIGO']?> </a> 
															<div class="push5"></div>
															
															<?php 		
														}  										

														?>


													</div> 
                                            </div>
											
                                            <div class="push100"></div>


                                        </div>

                                    </div>

                                </div>

                                <div class="push30"></div>                             
                                
                            </div><!-- fim Portlet -->
                        </div>
                            
                    </div>

                </div>

   <!--  <script type="text/javascript" src="js/plugins/datepicker/moment.js"></script>
    <script type="text/javascript" src="js/plugins/datepicker/moment-pt-br.js"></script>
    <script type="text/javascript" src="js/plugins/datepicker/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap-datetimepicker.css" />
    <link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te-1.4.0.css">
    <link rel="stylesheet" type="text/css" href="js/plugins/jQuery-TE/jquery-te.png">
    <script type="text/javascript" src="js/plugins/jQuery-TE/jquery-te-1.4.0.min.js"></script>
     -->
    
    <script type="text/javascript">
        $(function(){

        });
    </script>   