<?php

	// echo fnDebug('true');

	$hashLocal = mt_rand();	

	//busca dados da url	
	if (is_numeric(fnLimpacampo(fnDecode($_GET['id'])))){
		$cod_empresa = fnDecode($_GET['id']);
    }

    if (is_numeric(fnLimpacampo(fnDecode($_GET['idC'])))){
    	$cod_cliente = fnDecode($_GET['idC']);
	}	
		
    $sql="SELECT COD_EMPRESA, NOM_FANTASI, COD_CHAVECO, LOG_CATEGORIA, LOG_AUTOCAD
            FROM empresas WHERE COD_EMPRESA=0$cod_empresa";
            
    //fnEscreve($sql);		
    $qrBuscaEmpresa = mysqli_fetch_assoc(mysqli_query($connAdm->connAdm(),trim($sql)));
    $cod_empresa = $qrBuscaEmpresa['COD_EMPRESA'];
    $nom_empresa = $qrBuscaEmpresa['NOM_FANTASI'];

    //categoria de clientes		
    $sql2="SELECT B.NOM_FAIXACAT,A.* 
            FROM clientes A
            left join categoria_cliente B ON B.COD_CATEGORIA=A.COD_CATEGORIA
            WHERE A.COD_CLIENTE = $cod_cliente and 
            A.COD_EMPRESA = $cod_empresa";
            
    $qrBuscaCliente = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sql2));		
    // fnEscreve($sql2);	
  
if (isset($qrBuscaCliente)){

	$cod_usuario = $qrBuscaCliente['COD_CLIENTE'];
	$cod_externo = $qrBuscaCliente['COD_EXTERNO'];
	$nom_usuario = $qrBuscaCliente['NOM_CLIENTE'];
	$num_cartao =  $qrBuscaCliente['NUM_CARTAO'];
	$num_cgcecpf = $qrBuscaCliente['NUM_CGCECPF'];
	$num_rgpesso = $qrBuscaCliente['NUM_RGPESSO'];
	$dat_nascime = $qrBuscaCliente['DAT_NASCIME'];
	$des_enderec = $qrBuscaCliente['DES_ENDEREC'];
	$num_enderec = $qrBuscaCliente['NUM_ENDEREC'];
	$des_complem = $qrBuscaCliente['DES_COMPLEM'];
	$des_bairroc = $qrBuscaCliente['DES_BAIRROC'];
	$num_cepozof = $qrBuscaCliente['NUM_CEPOZOF'];
	$nom_cidadec = $qrBuscaCliente['NOM_CIDADEC'];
	$cod_usucada = $qrBuscaCliente['COD_USUCADA'];
	$cod_estado = $qrBuscaCliente['COD_ESTADO'];
	$cod_municipio = $qrBuscaCliente['COD_MUNICIPIO'];
	$dat_admissao = fnDataShort($qrBuscaCliente['DAT_ADMISSAO']);
	$latitude = $qrBuscaCliente['LAT'];
	$longitude = $qrBuscaCliente['LNG'];

    $sqlUf = "SELECT COD_ESTADO, UF FROM ESTADO WHERE COD_ESTADO=$cod_estado";
    $arrayEstado = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlUf));		
    $nom_estado = $arrayEstado["UF"];

    $sqlMun = "SELECT COD_MUNICIPIO, NOM_MUNICIPIO FROM MUNICIPIOS WHERE COD_MUNICIPIO = $cod_municipio";
    $arrayMunic = mysqli_fetch_assoc(mysqli_query(connTemp($cod_empresa,''),$sqlMun));		
    $nom_municipio = $arrayMunic["NOM_MUNICIPIO"];

    $sqlReprova = "SELECT COD_STATUS FROM ANEXO_DOC 
                    WHERE COD_EMPRESA = $cod_empresa 
                    AND COD_CLIENTE = $cod_cliente
                    AND COD_STATUS = 3
                    ORDER BY COD_STATUS";

    // FNeSCREVE($sqlReprova);
    $arrayReprova = mysqli_query(connTemp($cod_empresa,''), $sqlReprova);

    $numReprova = mysqli_num_rows($arrayReprova);

    $status = 1;

    if($numReprova > 0){

        $status = 3;

    }else{


        $sqlAnalise = "SELECT COD_STATUS FROM ANEXO_DOC 
                        WHERE COD_EMPRESA = $cod_empresa 
                        AND COD_CLIENTE = $cod_cliente
                        AND COD_STATUS NOT IN(2,3)
                        ORDER BY COD_STATUS";

        // FNeSCREVE($sqlAnalise);
        $arrayAnalise = mysqli_query(connTemp($cod_empresa,''), $sqlAnalise);

        $numAnalise = mysqli_num_rows($arrayAnalise);

        if($numAnalise > 0){

            $status = 1;

        }else{


            $sqlAprova = "SELECT COD_STATUS FROM ANEXO_DOC 
                            WHERE COD_EMPRESA = $cod_empresa 
                            AND COD_CLIENTE = $cod_cliente
                            AND COD_STATUS = 2
                            ORDER BY COD_STATUS";

            // FNeSCREVE($sqlAprova);
            $arrayAprova = mysqli_query(connTemp($cod_empresa,''), $sqlAprova);

            $numAprova = mysqli_num_rows($arrayAprova);

            if($numAprova > 0){

                $status = 2;

            }else{

                $status = 1;

            }


        }


    }
}
// fnEscreve($cod_usuario);

?>

<style type="text/css">
    #blocker
    {
        display:none; 
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: .8;
        background-color: #fff;
        z-index: 1000;
    }
        
    #blocker div
    {
        position: absolute;
        top: 30%;
        left: 48%;
        width: 200px;
        height: 2em;
        margin: -1em 0 0 -2.5em;
        color: #000;
        font-weight: bold;
    }
</style>
<div id="blocker">
    <div style="text-align: center;"><img src="images/loading2.gif"><br/> Aguarde. Gerando PDF... ;-)<br/><small>(este processo pode demorar vários minutos)</small></div>
</div>

<?php if ($popUp != "true"){  ?>							
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
                <span class="text-primary"><?php echo $NomePg; ?> / <?php echo $nom_empresa; ?></span>
            </div>
            
                <?php								
                //$formBack = "1015";
                include "atalhosPortlet.php"; 
                ?>	
                
        </div>
        <?php } ?>	
        
            <div class="portlet-body">

                <?php if ($msgRetorno <> '') { ?>	
                <div class="alert <?php echo $msgTipo; ?> alert-dismissible top30" role="alert" id="msgRetorno">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?php echo $msgRetorno; ?>
                </div>
                <?php } ?>
                <?php if ($status != 2) { ?> 
                <div class="alert alert-danger alert-dismissible top30" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    Existem documentos pendentes que requerem verificação.
                </div>
                <?php } ?>
                
                <?php 
                //menu superior - cliente
                
                $abaCli = 1848;						
                include "abasClienteRH.php";

                ?>
                
                <div class="push30"></div> 

                <div class="login-form">
                
                    <form data-toggle="validator" role="form2" method="post" id="formulario" action="action.php?mod=QSEuTVi6dkU¢&id=<?=fnEncode($cod_empresa)?>&idC=<?=fnEncode($cod_cliente)?>">
                                                
                    <fieldset>
                        <legend>Dados Gerais</legend> 
                        
                        <!-- bloco dados básicos -->
                        <div class="col-xs-12">
                        
                            <div class="row">													
                            
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Código</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_USUARIO" id="COD_USUARIO" value="<?php echo $cod_usuario;?>">
                                    </div>
                                </div>
                                
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required">Empresa</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
                                        <input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
                                    </div>														
                                </div>
                                                    
                                <div class="col-xs-7">
                                    <label for="inputName" class="control-label required">Nome do Funcionário</label>
                                    <input type="text" name="NOM_USUARIO" id="NOM_USUARIO" readonly="readonly" value="<?php echo $nom_usuario; ?>" maxlength="50" class="form-control input-sm" style="border-radius:0 3px 3px 0;" data-error="Campo obrigatório">
                                </div>
                            </div>
                            
                            <div class="row">
                                

                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CNPJ/CPF</label>
                                        <input type="text" class="form-control input-sm cpfcnpj" readonly="readonly" name="NUM_CGCECPF" id="NUM_CGCECPF" value="<?php echo fnCompletaDoc($num_cgcecpf,'F');?>" maxlength="18" data-error="Campo obrigatório">
                                    </div>
                                </div>
                                
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">RG</label>
                                        <input type="text" class="form-control input-sm" name="NUM_RGPESSO" readonly="readonly" id="NUM_RGPESSO" value="<?php echo $num_rgpesso;?>" maxlength="15" data-error="Campo obrigatório">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>					
                            
                                <div class="col-xs-4">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label required lbl_req">Data de Nascimento</label>
                                        <input type="text" class="form-control input-sm data" name="DAT_NASCIME" readonly="readonly" value="<?php echo $dat_nascime;?>" id="DAT_NASCIME" maxlength="10">
                                    </div>
                                </div>													
                                
                            </div>

                        <!-- fim bloco dados basicos -->
                        </div>
                                                                        
                    </fieldset>	

                    <div class="push10"></div>
                        
                    <fieldset>
                        <legend>Localização</legend> 
                            
                            <div class="row">
                                                                                               
                                <div class="col-xs-5">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Endereço</label>
                                        <input type="text" readonly="readonly" class="form-control input-sm" name="DES_ENDEREC" value="<?php echo $des_enderec;?>"id="DES_ENDEREC" maxlength="40">
                                        <div class="help-block with-errors"></div>
                                        
                                    </div>
                                </div>	
                                
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Número</label>
                                        <input type="text" readonly="readonly" class="form-control input-sm" name="NUM_ENDEREC" value="<?php echo $num_enderec; ?>" id="NUM_ENDEREC" maxlength="10">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Complemento</label>
                                        <input type="text" readonly="readonly" class="form-control input-sm" name="DES_COMPLEM" value="<?php echo $des_complem;?>" id="DES_COMPLEM" maxlength="100">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Bairro</label>
                                        <input type="text" readonly="readonly" class="form-control input-sm" name="DES_BAIRROC" value="<?php echo $des_bairroc;?>" id="DES_BAIRROC" maxlength="60">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="push10"></div>
                                
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">CEP</label>
                                        <input type="text" readonly="readonly" class="form-control input-sm cep" name="NUM_CEPOZOF" value="<?php echo $num_cepozof;?>" id="NUM_CEPOZOF" maxlength="9">
                                    </div>
                                </div>
                                
                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Estado</label>
                                        <input type="text" readonly="readonly" class="form-control input-sm" name="COD_ESTADO" value="<?php echo $nom_estado;?>" id="COD_ESTADO">
                                    </div>
                                </div>	

                                <div class="col-xs-2">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Cidade</label>
                                        <input type="text" readonly="readonly" class="form-control input-sm" name="COD_MUNICIPIO" value="<?php echo $nom_municipio;?>" id="COD_MUNICIPIO">
                                    </div>
                                </div>
                                
                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Latitude</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly"  name="LATITUDE" id="LATITUDE" value="<?=$latitude?>">
                                    </div>														
                                </div>

                                <div class="col-xs-3">
                                    <div class="form-group">
                                        <label for="inputName" class="control-label">Longitude</label>
                                        <input type="text" class="form-control input-sm leitura" readonly="readonly" name="LONGITUDE" id="LONGITUDE" value="<?=$longitude?>">
                                    </div>														
                                </div>
                                
                                
                            </div>			
                            
                    </fieldset>	
                    
                    <div class="push10"></div>
                    <hr>	


					<div class="col-lg-12">

						<div class="no-more-tables">

							<form name="formLista">

								<table class="table table-bordered table-striped table-hover tableSorter">
									<thead>
										<tr>
											<th>Código</th>
											<th>Tipo</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$sql = "SELECT * FROM ANEXO_DOCUMENTO 
												WHERE COD_EMPRESA = $cod_empresa 
												AND COD_CLIENTE = $cod_cliente 
												AND COD_EXCLUSA IS NULL";
										$arrayQuery = mysqli_query(connTemp($cod_empresa,''), $sql);

										$count = 0;
										while ($qrBuscaDoc = mysqli_fetch_assoc($arrayQuery)) {

                                            $sqlAnexo = "SELECT * FROM ANEXO_DOC WHERE COD_CLIENTE = $cod_cliente AND COD_ANEXO = ".$qrBuscaDoc["COD_ANEXO"];
                                            $arrayAnexo = mysqli_query(connTemp($cod_empresa,''),$sqlAnexo);
                                            $qrAnexo = mysqli_fetch_assoc($arrayAnexo);

											$sqlAprova = "SELECT COD_STATUS FROM ANEXO_DOC 
															WHERE COD_EMPRESA = $cod_empresa 
															AND COD_CLIENTE = $cod_cliente
															AND COD_ANEXO = $qrBuscaDoc[COD_ANEXO]
															AND COD_STATUS NOT IN(1)";
											$arrayAprova = mysqli_query(connTemp($cod_empresa,''), $sqlAprova);
											$numAprova = mysqli_num_rows($arrayAprova);
											if($numAprova <= 0){
                                                continue;
											}
											$count++;
											echo "
													<tr>
														<td>" . $qrBuscaDoc['COD_ANEXO'] . "</td>
														<td>" . $qrBuscaDoc['TIP_DOC'] . "</td>
													</tr>
													<input type='hidden' id='ret_COD_ANEXO_" . $count . "' value='" . $qrBuscaDoc['COD_ANEXO'] . "'>
													<input type='hidden' id='ret_DES_DOC_" . $count . "' value='" . $qrBuscaDoc['DES_DOC'] . "'>
													<input type='hidden' id='ret_TIP_DOC_" . $count . "' value='" . $qrBuscaDoc['TIP_DOC'] . "'>
													<input type='hidden' class='ret_URL_ANEXO' id='ret_URL_ANEXO_" . $count . "' value='https://adm.bunker.mk/media/clientes/$cod_empresa/documentos/documento.$cod_cliente/".$qrAnexo['NOM_ORIGEM'] . "'>
													";
										}

										?>

									</tbody>
								</table>

							</form>

						</div>

					</div>

                    <div class="push10"></div>

                    <div class="form-group text-right col-lg-12">
                        <a href="javascript:" onClick="geraPDF();return false;" class="btn btn-info getBtn addBox"><i class="fa fa-print" aria-hidden="true"></i>&nbsp; Imprimir Prestação de Contas</a>
                    </div>										
                    
                    </form>

                    <div class="push"></div>
                
                </div>								
            
            </div>
        </div>
        <!-- fim Portlet -->
    </div>
    
</div>

<div class="push20"></div>


<script type="text/javascript">

let merge_pdf = [],
    status = "<?=$status?>";
function geraPDF(){

    if(status == 2){

        merge_pdf = [];

        $('#blocker').show();

        let anexos = ['.gerais'];
        $(".ret_URL_ANEXO").each(function( index ) {
            anexos.push($( this ).val());
        });
        geraPDF_dir(anexos,0);

    }else{

        $.alert({
            title: "",
            content: "Existem documentos pendentes que requerem verificação.",
            type: 'red'
        });

    }

}
function geraPDF_dir(anexos,indx){
    if (indx <= anexos.length-1){
        var filename="pdf_pc_"+indx;
        console.log("Inicio",filename,anexos[indx]);

        $.ajax({
            type: "POST",
            url: "pdfPrestacaoContas.php?filename="+filename+"&acao=save&file="+anexos[indx]+"&id=<?=@$_GET["id"]?>&idC=<?=@$_GET["idC"]?>",
            success: function(data){
                merge_pdf.push(filename);
                console.log('Gerado ',filename,data);
                geraPDF_dir(anexos,indx+1);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                console.log("erro",errorThrown);
                $('#blocker').hide();
            }
        });

    }else{
        mergePDF();
    }
}
function mergePDF(){
	console.log(JSON.stringify(merge_pdf));
	var filename = "prestacao_contas";

	$.ajax({
		type: "POST",
		data: {"data":JSON.stringify(merge_pdf)},
		url: "pdfPrestacaoConvenio.php?acao=merge&filename="+filename,
		success: function(data){
			console.log('Merge ',filename,data);
			if (data != ""){
				$.confirm({
					title: 'Erro ao gerar PDF!',
					animation: 'opacity',
					closeAnimation: 'opacity',
					content: data,
					buttons: {
						ok: function () {
							
						},
					}
				});
			}else{
				$.confirm({
					title: 'PDF Gerado!',
					animation: 'opacity',
					closeAnimation: 'opacity',
					content: 'O documento foi gerado com sucesso!',
					buttons: {
						abrir: function () {
							window.open('temp_pdf/'+filename+'.pdf', '_blank');
						},
						cancelar: function () {
							
						},
					}
				});
				}
			$('#blocker').hide();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			console.log("erro",errorThrown);
			$('#blocker').hide();
		}
	});
}

</script>	
