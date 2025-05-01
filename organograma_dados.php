<?php

if ($des_organograma == "{}"){
	$des_organograma = '{"id":"1583941908460926"};';
}
$des_organograma = str_replace("\\\"","\"",$des_organograma);

?>
<script>
var modUsuarios = "<?=fnEncode("1548")?>";
var cod_empresa = "<?=fnEncode($cod_empresa)?>";
</script>

  <link rel="stylesheet" href="js/OrgChart-master/src/css/jquery.orgchart.css">
  <style type="text/css">
    #chart-container { background-color: #FFF; width: 100%; text-align:center;}
    .orgchart { background: #fff; }
    .orgchart.edit-state .edge { display: none; }
    .orgchart .node .title {
		color: #FFF !important;
		height: 16px;
		padding: 0px 4px 0 2px;
		margin: 0;
	}

	.orgchart .node {
	  margin: 4px;
	  padding: 3px;
	  border: 1px solid #ddd;
	  border-radius: 4px;
	  -moz-border-radius: 4px;
	  -webkit-border-radius: 4px;
	  text-align: center;
	  color:#2C3E50;
	  background:#FFF !important;
	}
	.orgchart .node div{
	  font-size:12px !important;
	  border:0 !important;
	}
	.orgchart .node .title,
	.orgchart .node .title .fa-pen{
		color:#FFF !important;
	}
	.orgchart .node .title .fa-pen{
		margin-top:5px;
	}
	.orgchart .node .title .symbol{
		display:none;
	}
	.orgchart .node .title fieldset{
		color:#000 !important;
	}

	.orgchart table {
		border-spacing: 0;
		border-collapse: collapse;
	}
	
	
	.orgchart .lines .topLine {
	  border-top: 2px solid rgba(0, 0, 0, 0.8);
	}

	.orgchart .lines .rightLine {
	  border-right: 2px solid rgba(0, 0, 0, 0.8);
	}

	.orgchart .lines .leftLine {
	  border-left: 2px solid rgba(0, 0, 0, 0.8);
	}

	.orgchart .lines .downLine {
	  background-color: rgba(0, 0, 0, 0.8);
	}

  </style>

	<?php if ($msgRetorno <> '') { ?>	
	<div class="alert <?php echo $msgTipo; ?> alert-dismissible top30 bottom30" role="alert" id="msgRetorno">
	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	 <?php echo $msgRetorno; ?>
	</div>
	<?php } ?>	


	<form id="organog" data-toggle="validator" role="form2" method="post" id="formulario" action="<?php echo $cmdPage; ?>">

	<fieldset>
		<legend>Dados Gerais</legend> 
	
			<div class="row">
				<div class="col-md-2">
					<div class="form-group">
						<label for="inputName" class="control-label required">C&oacute;digo</label>
						<input type="text" class="form-control input-sm leitura" readonly="readonly" name="COD_ORGANOGRAMA" id="COD_ORGANOGRAMA" value="<?=$cod_organograma?>">
					</div>
				</div>

				<div class="col-md-3">
					<div class="form-group">
						<label for="inputName" class="control-label required">Empresa</label>
						<input type="text" class="form-control input-sm leitura" readonly="readonly" name="NOM_EMPRESA" id="NOM_EMPRESA" value="<?php echo $nom_empresa; ?>">
						<input type="hidden" class="form-control input-sm" name="COD_EMPRESA" id="COD_EMPRESA" value="<?php echo $cod_empresa; ?>">
					</div>														
				</div>
				
				<div class="col-md-4">
					<div class="form-group">
						<label for="inputName" class="control-label required">Nome</label>
						<input type="text" class="form-control input-sm leitura" name="NOM_ORGANOGRAMA" id="NOM_ORGANOGRAMA" maxlength="50" data-error="Campo obrigatório" value="<?=$nom_organograma?>">
						<div class="help-block with-errors"></div>
					</div>
				</div>
				
				<textarea style='display:none;' class="form-control input-sm" name="DES_ORGANOGRAMA" id="DES_ORGANOGRAMA" style="display:non;height:100px;"><?=$des_organograma?></textarea>
				
			</div>
			
	</fieldset>
	
	<input type="hidden" name="opcao" id="opcao" value="ALT">
	<input type="hidden" name="hashForm" id="hashForm" value="<?php echo $hashLocal; ?>" />	
	<input type="hidden" name="hHabilitado" id="hHabilitado" value="S">	
	</form>
	
  <div id="chart-container"></div>


	<div class="portlet portlet-bordered">
		<div class="row">
			<div class="form-group text-right col-md-12">
				<button type="button" id="btn-export-hier" class="btn btn-primary getBtn">
					<i class="fa fa-repeat" aria-hidden="true"></i>&nbsp; Salvar
				</button>
			</div>
		</div>
	</div>
	
	
  
  
  <div id="edit-panel" style="display:none;">
    <span id="chart-state-panel" class="radio-panel">
      <input type="radio" name="chart-state" id="rd-view" value="view"><label for="rd-view">View</label>
      <input type="radio" name="chart-state" id="rd-edit" value="edit" checked="true"><label for="rd-edit">Edit</label>
    </span>
    <label class="selected-node-group">selected node:</label>
    <input type="text" id="selected-node" class="selected-node-group">
    <label>new node:</label>
    <ul id="new-nodelist">
      <li><input type="text" class="new-node"></li>
    </ul>
    <i class="oci oci-plus-circle btn-inputs" id="btn-add-input"></i>
    <i class="oci oci-minus-circle btn-inputs" id="btn-remove-input"></i>
    <span id="node-type-panel" class="radio-panel">
      <input type="radio" name="node-type" id="rd-parent" value="parent"><label for="rd-parent">Parent(root)</label>
      <input type="radio" name="node-type" id="rd-child" value="children"><label for="rd-child">Child</label>
      <input type="radio" name="node-type" id="rd-sibling" value="siblings"><label for="rd-sibling">Sibling</label>
    </span>
    <button type="button" id="btn-add-nodes">Add</button>
    <button type="button" id="btn-delete-nodes">Delete</button>
    <button type="button" id="btn-reset">Reset</button>
  </div>
  
  
  
  <script type="text/javascript" src="js/OrgChart-master/src/js/jquery.min.js"></script>
  <script type="text/javascript" src="js/OrgChart-master/src/js/jquery.orgchart.js?v=2"></script>
  <script type="text/javascript">
    $(document).ready(function(){
		var data_oc = <?=$des_organograma?>;

		var getId = function() {
		  return (new Date().getTime()) * 1000 + Math.floor(Math.random() * 1001);
		};

		var oc = $('#chart-container').orgchart({
		  'data' : data_oc,
		  'chartClass': 'edit-state',
		  'exportButton': false,
		  'exportFilename': 'Organograma',
		  'draggable': true,
		  'createNode': function($node, data) {
			$node[0].id = getId();
		  }
		});

		oc.$chartContainer.on('click', '.node', function() {
		  var $this = $(this);
		  $('#selected-node').val($this.find('.title').text()).data('node', $this);
		});

		oc.$chartContainer.on('click', '.orgchart', function(event) {
		  if (!$(event.target).closest('.node').length) {
			$('#selected-node').val('');
		  }
		});

		$('input[name="chart-state"]').on('click', function() {
		  $('.orgchart').toggleClass('edit-state', this.value !== 'view');
		  $('#edit-panel').toggleClass('edit-state', this.value === 'view');
		  if ($(this).val() === 'edit') {
			$('.orgchart').find('tr').removeClass('hidden')
			  .find('td').removeClass('hidden')
			  .find('.node').removeClass('slide-up slide-down slide-right slide-left');
		  } else {
			$('#btn-reset').trigger('click');
		  }
		});

		$('input[name="node-type"]').on('click', function() {
		  var $this = $(this);
		  if ($this.val() === 'parent') {
			$('#edit-panel').addClass('edit-parent-node');
			$('#new-nodelist').children(':gt(0)').remove();
		  } else {
			$('#edit-panel').removeClass('edit-parent-node');
		  }
		});

		$('#btn-add-input').on('click', function() {
		  $('#new-nodelist').append('<li><input type="text" class="new-node"></li>');
		});

		$('#btn-remove-input').on('click', function() {
		  var inputs = $('#new-nodelist').children('li');
		  if (inputs.length > 1) {
			inputs.last().remove();
		  }
		});


		$('#btn-add-nodes').on('click', function() {
		  var $chartContainer = $('#chart-container');
		  var nodeVals = [];
		  $('#new-nodelist').find('.new-node').each(function(index, item) {
			var validVal = item.value.trim();
			if (validVal.length) {
			  nodeVals.push(validVal);
			}
		  });
		  var $node = $('#selected-node').data('node');
		  if (!nodeVals.length) {
			alert('Please input value for new node');
			return;
		  }
		  var nodeType = $('input[name="node-type"]:checked');
		  if (!nodeType.length) {
			alert('Please select a node type');
			return;
		  }
		  if (nodeType.val() !== 'parent' && !$('.orgchart').length) {
			alert('Please creat the root node firstly when you want to build up the orgchart from the scratch');
			return;
		  }
		  if (nodeType.val() !== 'parent' && !$node) {
			alert('Please select one node in orgchart');
			return;
		  }
		  if (nodeType.val() === 'parent') {
			if (!$chartContainer.children('.orgchart').length) {// if the original chart has been deleted
			  oc = $chartContainer.orgchart({
				'data' : { 'name': nodeVals[0] },
				'exportButton': true,
				'exportFilename': 'SportsChart',
				'createNode': function($node, data) {
				  $node[0].id = getId();
				}
			  });
			  oc.$chart.addClass('view-state');
			} else {
			  oc.addParent($chartContainer.find('.node:first'), { 'name': nodeVals[0], 'id': getId() });
			}
		  } else if (nodeType.val() === 'siblings') {
			if ($node[0].id === oc.$chart.find('.node:first')[0].id) {
			  alert('You are not allowed to directly add sibling nodes to root node');
			  return;
			}
			oc.addSiblings($node, nodeVals.map(function (item) {
				return { 'name': item, 'relationship': '110', 'id': getId() };
			  }));
		  } else {
			var hasChild = $node.parent().attr('colspan') > 0 ? true : false;
			if (!hasChild) {
			  var rel = nodeVals.length > 1 ? '110' : '100';
			  oc.addChildren($node, nodeVals.map(function (item) {
				  return { 'name': item, 'relationship': rel, 'id': getId() };
				}));
			} else {
			  oc.addSiblings($node.closest('tr').siblings('.nodes').find('.node:first'), nodeVals.map(function (item) {
				  return { 'name': item, 'relationship': '110', 'id': getId() };
				}));
			}
		  }
		  ini_oc();
		});

		$('#btn-delete-nodes').on('click', function() {
		  var $node = $('#selected-node').data('node');
		  if (!$node) {
			alert('Please select one node in orgchart');
			return;
		  } else if ($node[0] === $('.orgchart').find('.node:first')[0]) {
			//if (!window.confirm('Are you sure you want to delete the whole chart?')) {
			  return false;
			//}
		  }
		  oc.removeNodes($node);
		  $('#selected-node').val('').data('node', null);
		  ini_oc();
		});

		$('#btn-reset').on('click', function() {
		  $('.orgchart').find('.focused').removeClass('focused');
		  $('#selected-node').val('');
		  $('#new-nodelist').find('input:first').val('').parent().siblings().remove();
		  $('#node-type-panel').find('input').prop('checked', false);
		});
		
		$('#btn-export-hier').on('click', function() {
			if (!$('pre').length) {
				var hierarchy = oc.getHierarchy();
			}else{
				var hierarchy = {};
			}
			
			if (hierarchy == "Error: nodes do not exist"){
				$("#DES_ORGANOGRAMA").val("{}");
			}else{
				$("#DES_ORGANOGRAMA").val(JSON.stringify(hierarchy));
			}
			
			$("#organog").submit();
		});
		
		ini_oc();

  });
  
	function ini_oc(){
		$('.btn-add').off('click');
		$('.btn-delete').off('click');
		$('.btn-edit').off('click');
		$('.btn-add').on('click', function() {
			$('#selected-node').data('node',$(this).parent().parent());
			$("input.new-node").val("Novo");
			$("#rd-child").attr("checked",true);
			$("#btn-add-nodes").click();
		});
		$('.btn-delete').on('click', function() {
			$('#selected-node').data('node',$(this).parent().parent());
			$("#btn-delete-nodes").click();
		});
		$('.btn-edit').on('click', function() {
			//alert("d");
		});
	}
	
	
	window.addEventListener("message", function(e){

		var $node = $('#selected-node').data('node');
		$node.find("[name=NOM_USUARIO]").val(e.data.NOM_USUARIO);
		$node.find("[name=COD_USUARIO]").val(e.data.COD_USUARIO);
		atualizaOrg();

	});
	
	function atualizaOrg(){
		var $node = $('#selected-node').data('node');
		var id = $node.attr("id");
		
		
		$("#"+id+" .NOM_USUARIO").html($("#"+id+" [name=NOM_USUARIO]").val());
		$("#"+id+" .DES_FUNCAO").html($("#"+id+" [name=DES_FUNCAO]").val());
		$("#"+id+" .DES_OBJETIVO").html($("#"+id+" [name=DES_OBJETIVO]").val());

		$("#"+id).attr("cod_usuario",$("#"+id+" [name=COD_USUARIO]").val());
		$("#"+id).attr("nom_usuario",$("#"+id+" [name=NOM_USUARIO]").val());
		$("#"+id).attr("des_objetivo",$("#"+id+" [name=DES_OBJETIVO]").val());
		$("#"+id).attr("des_funcao",$("#"+id+" [name=DES_FUNCAO]").val());
		//alert($("#"+id+" [name=NOM_USUARIO]").val());
	}

  </script>
