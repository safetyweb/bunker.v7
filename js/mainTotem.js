$(document).ready(function(){

	//choosen
	$(".chosen-select-deselect").chosen({allow_single_deselect:true});
	
	//máscaras
	$('.money').mask("#.##0,00", {reverse: true});
	$('.int').mask("##0", {reverse: true});
	$('.data').mask('00/00/0000');
	//$('.hora').mask('00:00:00');
	$('.hora').mask('00:00');
	$('.cep').mask('00000-000');
	$('.fone').mask('(00) 00000-0000');	
	$('.celular').mask('(00) 00000-0000');
	$('.cpf').mask('000.000.000-00', {reverse: true});
        
        $(".fone").click(function() {
            $( "#target" ).blur();
        });  
        
        $(".fone, .sp_celphones, .celular").blur(function() {
            if($(this).val().length != 15){
                $.alert({
                    title: 'Atenção!',
                    content: 'Telefone incompleto, digite novamente por favor!',                  
                });
            }
        });          
	
	if($('.cpfcnpj').val() != undefined){
		mascaraCpfCnpj($('.cpfcnpj'));
	}

	var SPMaskBehavior = function (val) {
	  return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
	},
	spOptions = {
	  onKeyPress: function(val, e, field, options) {
		  field.mask(SPMaskBehavior.apply({}, arguments), options);
		}
	};

	$('.cel').mask(SPMaskBehavior, spOptions);


	
	//criticas de envio
	var btnValue = "";
	$(document).on('click', '.getBtn', function(){ 
	   btnValue = $(this).attr("name");
	});		
			
	$('#formulario').validator().on('submit', function (e) {
	  if (e.isDefaultPrevented()) {
		// handle the invalid form...
		//alert("Preencha o campos obrigatórios");
	  } else {
		// everything looks good!
		e.preventDefault(); 
		$("#formulario #opcao").val(btnValue);	
		
		if (btnValue == 'CAD'){
			$("#CAD, #ALT, #EXC").prop('disabled', true);
			try {$("#blocker").show();} catch(err) {};
			$("#formulario")[0].submit();
			//alert("entrou cad");
		}		
		
		if (btnValue == 'ALT') {
			if ($("#formulario #hHabilitado").val()=='S') {
				$("#CAD, #ALT, #EXC").prop('disabled', true);
				try {$("#blocker").show();} catch(err) {};
				$("#formulario")[0].submit();
				$("#hHabilitado").val('N');
			} else{
				alert("Para alteração selecione um registro na lista");
			}						
		}
		
		if (btnValue == 'EXC') {
			if ($("#formulario #hHabilitado").val()=='S') {
				$.confirm({
					title: 'Atenção!',
					animation: 'opacity',
					closeAnimation: 'opacity',
					content: 'Deseja realmente excluir esse registro?',
					buttons: {
						confirmar: function () {
							$("#CAD, #ALT, #EXC").prop('disabled', true);
							$("#formulario")[0].submit();
							$("#hHabilitado").val('N');
						},
						cancelar: function () {
							
						}
					}
				});	 
			} else{
				$.alert('Para exclusão selecione um registro na lista');
			}						
		}
		
		if (btnValue == 'ADD'){			
			$("#BUS, #ADD").prop('disabled', true);		
			$('#formulario').attr('action', 'action.php?mod='+$("#dUrl").val()+'&id='+$("#dKey").val()+'&idC='+$("#dId").val()+' ');					
			$('#formulario').validator('validate');	
			$("#formulario")[0].submit();
		}

		if (btnValue == 'BUS'){
			$("#BUS, #ADD").prop('disabled', true);
			$("#formulario #hHabilitado").val('S');
			$('#formulario').validator('validate');			
			$("#formulario")[0].submit();
		}		

	  }
	})


	//fecha alert automático
	$("#msgRetorno").fadeTo(15000, 500).fadeToggle(500, function(){
		$("#msgRetorno").alert('close');
	});
	
	
	//tooltip
	$('[data-toggle="tooltip"]').tooltip(); 
	
	//modal
	$("body").on("click", ".addBox", function() {												
		var popLink = $(this).attr("data-url");
		var popTitle = $(this).attr("data-title");
		//alert(popLink);	
		setIframe(popLink, popTitle);
		$('.modal').appendTo("body").modal('show');
	});	
	
	$(".nav-tabs li").on("click", function(e) {
	  if ($(this).hasClass("disabled")) {
		e.preventDefault();
		return false;
	  }
	});

	// $('.validaCPF').click(function(e){
	// 	if(!valida_cpf_cnpj($('.cpfcnpj').val())){
	// 		e.preventDefault();
	// 		$.alert({
	// 			title: 'Atenção!',
	// 			content: 'CPF/CNPJ digitado é inválido!',
	// 		});			
	// 	}
	// });
	
	// $(".cpfcnpj, .cpf, .cnpj").change(function() {
	// 	if(!valida_cpf_cnpj($(this).val())){
	// 		$.alert({
	// 			title: 'Atenção!',
	// 			content: 'CPF/CNPJ digitado é inválido!',
	// 		});			
	// 	}
	// });	
});	
	//Funções
	
	function mascaraCpfCnpj(cpfCnpj){
		var optionsCpfCnpj = {
			onKeyPress: function (cpf, ev, el, op) {
				var masks = ['000.000.000-000', '00.000.000/0000-00'],
					mask = (cpf.length >= 15) ? masks[1] : masks[0];
				cpfCnpj.mask(mask, op);
			}
		}	

		var masks = ['000.000.000-000', '00.000.000/0000-00'];
		mask = (cpfCnpj.val().length >= 14) ? masks[1] : masks[0];
			
		cpfCnpj.mask(mask, optionsCpfCnpj);		
	}	
	
	//call modal
	function setIframe(src, title) {
		$(".modal iframe").attr({
			'src': src
		});
		if (title) {
			$(".modal-title").text(title);
		} else {
			$(".modal-title").text("");
		}
	}	
	
	function limpaValor(valor){
		var limpaPonto = valor.replace('.','');
		var valorSql = eval(limpaPonto.replace(',','.'));
		return valorSql
	}	
	
	function verifica_cpf_cnpj ( valor ) {

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');

		// Verifica CPF
		if ( valor.length === 11 ) {
			return 'CPF';
		} 
		
		// Verifica CNPJ
		else if ( valor.length === 14 ) {
			return 'CNPJ';
		} 
		
		// Não retorna nada
		else {
			return false;
		}
		
	}

	function valida_cpf_cnpj ( valor ) {

		// Verifica se é CPF ou CNPJ
		var valida = verifica_cpf_cnpj( valor );

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');


		// Valida CPF
		if ( valida === 'CPF' ) {
			// Retorna true para cpf válido
			return valida_cpf( valor );
		} 
		
		// Valida CNPJ
		else if ( valida === 'CNPJ' ) {
			// Retorna true para CNPJ válido
			return valida_cnpj( valor );
		} 
		
		// Não retorna nada
		else {
			return false;
		}
	}	
	
	function valida_cpf( valor ) {

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');


		// Captura os 9 primeiros dígitos do CPF
		// Ex.: 02546288423 = 025462884
		var digitos = valor.substr(0, 9);

		// Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
		var novo_cpf = calc_digitos_posicoes( digitos );

		// Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
		var novo_cpf = calc_digitos_posicoes( novo_cpf, 11 );

		// Verifica se o novo CPF gerado é idêntico ao CPF enviado
		if ( novo_cpf === valor ) {
			// CPF válido
			return true;
		} else {
			// CPF inválido
			return false;
		}
		
	} // valida_cpf

	/*
	 valida_cnpj
	 
	 Valida se for um CNPJ
	 
	 @param string cnpj
	 @return bool true para CNPJ correto
	*/
	function valida_cnpj ( valor ) {

		// Garante que o valor é uma string
		valor = valor.toString();
		
		// Remove caracteres inválidos do valor
		valor = valor.replace(/[^0-9]/g, '');

		
		// O valor original
		var cnpj_original = valor;

		// Captura os primeiros 12 números do CNPJ
		var primeiros_numeros_cnpj = valor.substr( 0, 12 );

		// Faz o primeiro cálculo
		var primeiro_calculo = calc_digitos_posicoes( primeiros_numeros_cnpj, 5 );

		// O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
		var segundo_calculo = calc_digitos_posicoes( primeiro_calculo, 6 );

		// Concatena o segundo dígito ao CNPJ
		var cnpj = segundo_calculo;

		// Verifica se o CNPJ gerado é idêntico ao enviado
		if ( cnpj === cnpj_original ) {
			return true;
		}
		
		// Retorna falso por padrão
		return false;
		
	} // valida_cnpj	
	
	function calc_digitos_posicoes( digitos, posicoes = 10, soma_digitos = 0 ) {

		// Garante que o valor é uma string
		digitos = digitos.toString();

		// Faz a soma dos dígitos com a posição
		// Ex. para 10 posições:
		//   0    2    5    4    6    2    8    8   4
		// x10   x9   x8   x7   x6   x5   x4   x3  x2
		//   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
		for ( var i = 0; i < digitos.length; i++  ) {
			// Preenche a soma com o dígito vezes a posição
			soma_digitos = soma_digitos + ( digitos[i] * posicoes );

			// Subtrai 1 da posição
			posicoes--;

			// Parte específica para CNPJ
			// Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
			if ( posicoes < 2 ) {
				// Retorno a posição para 9
				posicoes = 9;
			}
		}

		// Captura o resto da divisão entre soma_digitos dividido por 11
		// Ex.: 196 % 11 = 9
		soma_digitos = soma_digitos % 11;

		// Verifica se soma_digitos é menor que 2
		if ( soma_digitos < 2 ) {
			// soma_digitos agora será zero
			soma_digitos = 0;
		} else {
			// Se for maior que 2, o resultado é 11 menos soma_digitos
			// Ex.: 11 - 9 = 2
			// Nosso dígito procurado é 2
			soma_digitos = 11 - soma_digitos;
		}

		// Concatena mais um dígito aos primeiro nove dígitos
		// Ex.: 025462884 + 2 = 0254628842
		var cpf = digitos + soma_digitos;

		// Retorna
		return cpf;
		
	} // calc_digitos_posicoes		