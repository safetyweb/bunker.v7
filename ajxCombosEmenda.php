<?php 
include './_system/_functionsMain.php';

$cod_empresa = fnLimpaCampoZero(fnDecode($_GET['id']));



?>

				<div>

					<div id="relatorioObjetoAjx">
						<select data-placeholder="Selecione um estado" name="COD_OBJETO" id="COD_OBJETO" class="chosen-select-deselect">
							<option value=""></option>
							<?php

								$sql = "SELECT * FROM OBJETO_EMENDA WHERE COD_EMPRESA = $cod_empresa";
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

								while($qrBusca = mysqli_fetch_assoc($arrayQuery)){
							?>

								<option value="<?=$qrBusca[COD_OBJETO]?>"><?=$qrBusca[DES_OBJETO]?></option>

							<?php
								}

							?>
							<!-- <option value=""></option>										
							<option value="">Aquisição de Equipamentos para a Coordenadoria de Defesa e Saúde Animal</option>										
							<option value="">Obras</option>										
							<option value="">Custeio</option>										
							<option value="">Aquisição de Equipamentos</option>										
							<option value="">Infraestrutura Urbana</option>										
							<option value="">Iluminação Pública</option>										
							<option value="">Reforma do Canil Municipal</option>										
							<option value="">Custeio de Exames Laboratoriais</option> -->										
							<option value="add">&nbsp;ADICIONAR NOVO</option>								
						</select>
						<script type="text/javascript">
                        	$('#COD_OBJETO').change(function(){
								valor = $(this).val();
								if(valor=="add"){
									$(this).val('').trigger("chosen:updated");
									$('#btnCad_COD_OBJETO').click();
								}
							});
                        </script>
					</div>
					
					<div id="relatorioOrgaoAjx">
						<select data-placeholder="Selecione um orgão" name="COD_ORGAO" id="COD_ORGAO" class="chosen-select-deselect">
							<option value=""></option>
							<?php

								$sql = "SELECT * FROM ORGAO_EMENDA WHERE COD_EMPRESA = $cod_empresa";
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

								while($qrBusca = mysqli_fetch_assoc($arrayQuery)){
							?>

								<option value="<?=$qrBusca[COD_ORGAO]?>"><?=$qrBusca[DES_ORGAO]?></option>

							<?php
								}

							?>
							<!-- <option value=""></option>										
							<option value="">Saúde</option>										
							<option value="">Desenvolvimento Social</option>										
							<option value="">Desenvolvimento Regional</option>										
							<option value="">Cultura</option>										
							<option value="">Econômia Criativa</option>										
							<option value="">Econômia Criativa</option>	 -->
							<option value="add">&nbsp;ADICIONAR NOVO</option>								
						</select>
						<script type="text/javascript">
                        	$('#COD_ORGAO').change(function(){
								valor = $(this).val();
								if(valor=="add"){
									$(this).val('').trigger("chosen:updated");
									$('#btnCad_COD_ORGAO').click();
								}
							});
                        </script>
					</div>
				
					<div id="relatorioTipoAjx">
						<select data-placeholder="Selecione um tipo" name="COD_TIPO" id="COD_TIPO" class="chosen-select-deselect">
							<option value=""></option>
							<?php

								$sql = "SELECT * FROM TIPO_EMENDA WHERE COD_EMPRESA = $cod_empresa";
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

								while($qrBusca = mysqli_fetch_assoc($arrayQuery)){
							?>

								<option value="<?=$qrBusca[COD_TIPO]?>"><?=$qrBusca[DES_TIPO]?></option>

							<?php
								}

							?>
							<!-- <option value=""></option>					
							<option value="1">Impositivas</option>					
							<option value="2">Voluntárias</option> -->					
							<option value="add">&nbsp;ADICIONAR NOVO</option>								
						</select>
						<script type="text/javascript">
                        	$('#COD_TIPO').change(function(){
								valor = $(this).val();
								if(valor=="add"){
									$(this).val('').trigger("chosen:updated");
									$('#btnCad_COD_TIPO').click();
								}
							});
                        </script>
					</div>
				
					<div id="relatorioStatusAjx">
						<select data-placeholder="Selecione um estado" name="COD_STATUS" id="COD_STATUS" class="chosen-select-deselect">
							<option value=""></option>
							<?php

								$sql = "SELECT * FROM STATUS_EMENDA WHERE COD_EMPRESA = $cod_empresa";
								$arrayQuery = mysqli_query(connTemp($cod_empresa,''),$sql);

								while($qrBusca = mysqli_fetch_assoc($arrayQuery)){
							?>

								<option value="<?=$qrBusca[COD_STATUS]?>"><?=$qrBusca[DES_STATUS]?></option>

							<?php
								}

							?>

							<option value="add">&nbsp;ADICIONAR NOVO</option>								
						</select>
						<script type="text/javascript">
                        	$('#COD_STATUS').change(function(){
								valor = $(this).val();
								if(valor=="add"){
									$(this).val('').trigger("chosen:updated");
									$('#btnCad_COD_STATUS').click();
								}
							});
                        </script>
					</div>

					<div id="scripts">
						<script>
							$(".chosen-select-deselect").chosen({allow_single_deselect:true});
						</script>
					</div>

				</div>