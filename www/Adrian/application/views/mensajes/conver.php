<section>
	<script type="text/javascript">
                
		String.prototype.trim = function() { return this.replace(/^\s+|\s+$/g, ""); };

		//Objeto Comet encargado de hacer la llamada AJAX larga (long polling)
		var Comet = function (data_url) {

			this.url = data_url;  
			this.noerror = true;

			this.connect = function() {
			  var self = this;

			  $.ajax({
				type : 'get',
				url : this.url,
				data : {
					//Id del amigo con el que se esta hablando
					idAmi: $("#idAmigo").val(), 
					//Id del último mensaje recibido
					idMen: $("#ultimoMensaje").val()
				},
				success : function(response) {
					
				  self.handleResponse(response);
				  self.noerror = true;          
				},
				complete : function(response) {
				  //Nueva llamada cuando ha acabado la peticion
				  if (!self.noerror) {
					//Si ha ocurrido un error, intenta reconectar cada 5 segundos
					setTimeout(function(){ comet.connect(); }, 5000);           
				  }else {
					//Conexion permanente
					self.connect(); 
				  }
				  self.noerror = false; 
				}
			  });
			}

			this.disconnect = function() {}

			//cuando acaba la llamada, coloca el mensaje
			this.handleResponse = function(response) {

					$(response).appendTo(".interior");
					$(".interior").scrollTop(10000);
			}

			//Petición de envio del mensaje
			this.doRequest = function(texto, usu) {
				
				if(texto.trim().length !== 0){
					
					$.ajax({
					  type : 'get',
					  url : this.url,
					  data : {mensaje : texto.trim(), usuario : usu}
					});
					
					$("#texto_responder").val("");
					
				}else{
					
					console.log("Mensaje vacio");
					$("#texto_responder").val("");
				}
			}

		}//Fin comet
                
		$(function(){
                    $('#formu_mensaje').submit(function(evnt){
                        
                        //Evitamos que el evento submit recargue toda la página
                        evnt.preventDefault();
                        comet.doRequest($("#texto_responder").val(), $("#idAmigo").val());      
                    });        
		});
                
		$(function(){
                    
                    //evento: click a Intro cuando el foco este en el textarea
                    $("#texto_responder").keypress(function (tecla){
					
					shift = tecla.shiftKey;
                 
                        if(tecla.which === 13 && !shift){

                            tecla.preventDefault();
                            comet.doRequest($("#texto_responder").val(), $("#idAmigo").val()); 
                        }else{
					
						}
                    });
					
					$("#texto_responder").keyup(function (tecla){
			
						shift = false;
					});
		});
               
        $(document).ready(function(){
            
            parent.comet = new Comet('<?php echo base_url()."mensajes/recarga/"?>');
            comet.connect();
        });                
                
	</script>
 <div class="ContenedorDatos">
	<div class="interior">
		<?php
		$mensaje = "";
		$ultimo;
		foreach ($conversacion as $mens){

			/* * * * * Control de HORA/FECHA * * * * * * * * */
			$fechaTotal = explode(" ", $mens['fecha']);
			$fecha = $fechaTotal[0];
			$horaToda = explode(":", $fechaTotal[1]);
			$hora = $horaToda[0].':'.$horaToda[1];

			$mensaje .= '<div id="Conver'.$mens['id_ump'].'" class="mensaje" style="border: 1px solid black; width:300px;">';

			//Imprimir cada mensaje    
			if($fechaHoy == $fecha){

				$mensaje .= '<span class="letrita">[Hoy a las '.$hora.'] </span>';
			}else{
				$mensaje .= '<span class="letrita">['.$fecha.' - '.$hora.'] </span>';
			}

			if($mens['usu_remite'] == $this->session->userdata('id_usuario')){
			//Enviados
				$mensaje .= '<span class="letrita">Yo: </span>';
				$mensaje .= '<p>';
                                $mensaje .= $mens['texto'].'</p>';
                                $mensaje .= '</div><br/>';
			}else{
			//Recibidos
				$mensaje .= '<span class="letrita">'.$mens['Remite'].' '.$mens['Remite_apellido1'].' '.$mens['Remite_apellido2'].': </span>';
				$mensaje .= '<p>';
                                $mensaje .= $mens['texto'].'</p>';
                                $mensaje .= '</div><br/>';
			}
		$ultimo = $mens['id_ump'];
		}
		echo $mensaje;
		?>
	</div>
		
	<section id="conver_responder">
		<?php echo form_open('', 'id="formu_mensaje"')?>
		
		<!-- Campo oculto con el ID del ultimo mensaje leido -->
		<input id="ultimoMensaje" type="hidden" value="<?= $ultimo?>">
		
		<!-- Campo oculto con el ID del destinatario -->
		<input id="idAmigo" type="hidden" name="usu" value="<?= $amigo ?>"/>
		
		<!-- Contenedor del mensaje a enviar -->
		<textarea id="texto_responder" name="texto" ></textarea>
		<br/>
		<input  type="submit" value="Enviar" />
			
		<?php echo form_close()?>
	</section>
      </div>
</section>