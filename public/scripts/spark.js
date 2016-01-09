var spark = {
	accessToken: null,
	deviceID: null,
	debug: true,
	cadastroDigitalAtivo: 0,
	idUsuario: null,
	RFID: null,
	url: null,
	mensagensBiometricas: {
		ED: null,
		FINGERPRINT_FLASHERR : null,
		FINGERPRINT_BADLOCATION : null,
		FINGERPRINT_PACKETRECIEVEERR : null,
		FINGERPRINT_ENROLLMISMATCH : null,
		FINGERPRINT_INVALIDIMAGE : null,
		FINGERPRINT_FEATUREFAIL : null,
		FINGERPRINT_IMAGEMESS : null
	},
		
	//eventos RFID
	carregaRFIDSource: function(){
		var eventSource = new EventSource(this.url + this.deviceID + "/events/?access_token=" + this.accessToken);

		eventSource.addEventListener('open', function(e) {
			if(spark.debug) console.log("Opened!"); 
        }, false);

        eventSource.addEventListener('error', function(e) {
        	if(spark.debug) console.log("Errored!"); 
        },false);

        eventSource.addEventListener('RFID', function(e) {
        	$('#msgColocarRFID').modal('show');
            var parsedData = JSON.parse(e.data);
            spark.RFID = parsedData.data; 
            if(spark.debug) console.log(parsedData.data);
            
        }, false);
	},
	
	//eventos Biometricos
	eventoRemoverDedo: function(el){
		var eventSource = new EventSource(this.url + this.deviceID + "/events/?access_token=" + this.accessToken);

		eventSource.addEventListener('open', function(e) {
			if(spark.debug) console.log("Opened!"); 
        }, false);

        eventSource.addEventListener('error', function(e) {
        	if(spark.debug) console.log("Errored!"); 
        },false);

        eventSource.addEventListener('removerDedo', function(e) {
        	$('#msgColocarDedo').modal('hide');
        	setTimeout(function() { 
        		$('#msgRemoverDedo').modal('show'); 
        	}, 1000);
        	
        }, false);
	},
	
	eventoErroDigital: function(el) {
		var eventSource = new EventSource(this.url + this.deviceID + "/events/?access_token=" + this.accessToken);

		eventSource.addEventListener('open', function(e) {
			if(spark.debug) console.log("Opened!"); 
        }, false);

        eventSource.addEventListener('error', function(e) {
        	if(spark.debug) console.log("Errored!"); 
        },false);

        eventSource.addEventListener('erroDigital', function(e) {
        	if(spark.debug) console.log(e);
        	var parsedData = JSON.parse(e.data);
        	$('#text-error').html(spark.mensagensBiometricas[parsedData.data]);
        	$('.modal').modal('hide');
    		$('#msgErro').modal('show'); 
        }, false);
	},
	
	//retorna o ID do leitor biometrico
	eventoCadastroBiometricoFinalizado: function(){
		var eventSource = new EventSource(this.url + this.deviceID + "/events/?access_token=" + this.accessToken);

		eventSource.addEventListener('open', function(e) {
			if(spark.debug) console.log("Opened!"); 
        }, false);

        eventSource.addEventListener('error', function(e) {
        	if(spark.debug) console.log("Errored!"); 
        },false);

        eventSource.addEventListener('fimBiometrico', function(e) {
        	$('#msgRemoverDedo').modal('hide');
        	var parsedData = JSON.parse(e.data);
            if(spark.debug) console.log(e);
            
            //faz o vinculo no banco
            spark.gerenciarDigitais(spark.idUsuario, parsedData.data, 'inserir');
        	
        }, false);
	},
	
	//retorna o RFID do spark e faz o vínculo no banco de dados
	eventoCadastroRFIDFinalizado: function(){
		var eventSource = new EventSource(this.url + this.deviceID + "/events/?access_token=" + this.accessToken);

		eventSource.addEventListener('open', function(e) {
			if(spark.debug) console.log("Opened!"); 
        }, false);

        eventSource.addEventListener('error', function(e) {
        	if(spark.debug) console.log("Errored!"); 
        },false);

        eventSource.addEventListener('fimRFID', function(e) {
        	$('#msgColocarRFID').modal('hide');
        	var parsedData = JSON.parse(e.data);
            if(spark.debug) console.log(e);
            
            spark.vincularRFID(parsedData.data);
        }, false);
	},
	
	//Ativa a captura da impressão digital no spark
	setCadastroDigital: function(status, idUsuario) {
		this.idUsuario = idUsuario;
		
		if(status) { 
			//acessa o banco pra ver qual codigo da digital
			$.ajax({
				url: "sistema_usuarios/gerenciar-digitais",
				type: 'post',
				data:{
					id: idUsuario,
					acao: 'contar',
					idFingerPrint: true
				},
				dataType: 'json'
			}).done(function(data) {
				if(spark.debug) console.log(data);
				
				//se deu certo, envia informações para spark
				if(data.success) {
					idFingerPrint = data.ref;
					params = "0" + status + '-' + idFingerPrint;
					spark.controlaCadastroDigitalParticle(params, status);
				} else {
					$('#text-error').html(data.message);
	        		$('#msgErro').modal('show'); 
				}
			});
		} else {
			spark.controlaCadastroDigitalParticle("00-0");
		}
	},
	
	controlaCadastroDigitalParticle: function(params, status){
		$.ajax({
			url: spark.url + spark.deviceID + "/cPorta",
			type: 'post',
			data: {
				access_token: spark.accessToken,
				params: params
			}
		}).done(function(data) {
			spark.cadastroDigitalAtivo = status > 0 ? 1 : 0;
			if(spark.debug) console.log(data);
			
			if(spark.cadastroDigitalAtivo) {
				$('#msgColocarDedo').modal('show');
			}
		}).error(function(){
			$('#text-error').html('Houve um problema ao processar a operação.');
    		$('#msgErro').modal('show'); 
		});
	},
	
	//Ativa a captura do cartão RFID no spark
	setCadastroRFID: function(idUsuario, status) {
		this.idUsuario = idUsuario;
		$.ajax({
			url: this.url + this.deviceID + "/cPorta",
			type: 'post',
			data: {
				access_token: this.accessToken,
				params: "1" + status
			}
		}).done(function(data) {
			if(spark.debug) console.log(data);
			
			if(status) {
				$('#msgColocarRFID').modal('show');
				console.log('Coloque o dedo no leitor biometrico');
			}
		}).error(function(){
			$('#text-error').html('Houve um problema ao processar a operação.');
    		$('#msgErro').modal('show'); 
		});
	},
	
	resetEventos: function(){
		if(this.idusuario) {
			spark.setCadastroDigital(0, 0, 0);
			spark.setCadastroRFID(0, 0);
		}
	},
	
	//faz o controle das digitias no cadastro do usuario
	gerenciarDigitais(idUsuario, idFingerPrint, acao){
		$.ajax({
			url: "sistema_usuarios/gerenciar-digitais",
			type: 'post',
			data: {
				idFingerPrint: idFingerPrint,
				id: idUsuario,
				acao: acao
			},
			dataType: 'json'
		}).done(function(data) {
			if(spark.debug) console.log(data);
			
			if(data.success) {
				if(acao == 'inserir') {
					$('#msgFimBiometrico').modal('show'); 
					
					//se a chamada veio do form atualiza a digital
					if($('#usuarios-digitais').length) {
						var html = '';
						html += "<tr idFinger='" + data.dados.usr_dig_idFingerPrint + "'>";
						html += "<td>" + data.dados.usr_dig_dataCadastro + "</td>";
						html += "<td><a href='#' data-name='idFingerPrint' data-type='select' data-pk='" + data.dados.usr_dig_idFingerPrint + "' data-value='" + data.dados.usr_dig_panico + "' class='editable editable-click'>" + (data.dados.usr_dig_panico == 1 ? 'Sim' : 'N�o') + "</a></td>";
						html += '<td style="text-align: center;"><a href="javascript:void(0);" class="btn-remove-digital table-link danger" title="Remover digital"><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-trash-o fa-stack-1x fa-inverse"></i></span></a></td>';
						$('#usuarios-digitais').find('tbody').append(html);
						
					}
				}
	        } else {
	        	setTimeout(function() { 
	        		$('#text-error').html(data.message);
	        		$('#msgErro').modal('show'); 
	        	}, 1000);
	        }
		});
	},
	
	//vincula RFID no cadastro do usuário
	vincularRFID(){
		$.ajax({
			url: "sistema_usuarios/insert-rfid",
			type: 'post',
			data: {
				rfid: this.RFID,
				id: this.idUsuario
			},
			dataType: 'json'
		}).complete(function(data) {
			if(spark.debug) console.log(data);
			
			if(data.success) {
				setTimeout(function() { 
					$('#msgFimRFID').modal('show'); 
            	}, 1000);
            } else {
            	setTimeout(function() { 
            		$('#text-error').html(data.message);
            		$('#msgErro').modal('show'); 
            	}, 1000);
            }
		});
	},
	
	//Envia comando para spark abrir a porta
	abrirPorta: function() {
		//acessa o banco pra ver qual codigo da digital
		$.ajax({
			url: "spark/abrir-porta",
			type: 'post',
			dataType: 'json'
		}).done(function(data) {
			if(spark.debug) console.log(data);
			
			if(data.success) {
				console.log('ok');
			} else {
				$('#text-error').html(data.message);
        		$('#msgErro').modal('show'); 
			}
		});
	},
	
	//Envia comando para spark remover todas as digitais
	removerDigitais: function() {
		$.ajax({
			url: "spark/remover-digitais",
			type: 'post',
			dataType: 'json'
		}).done(function(data) {
			if(spark.debug) console.log(data);
			
			if(data.success) {
				$('#digitaisRemovidas').modal('show'); 
			} else {
				$('#text-error').html(data.message);
        		$('#msgErro').modal('show'); 
			}
		});
	},
	
	//mensagem de erro referente a permissao
	semAcesso: function() {
		$('#text-error').html('Voc� n�o tem acesso para acessar esta funcionalidade.');
   		$('#msgErro').modal('show'); 
	}
	
}
