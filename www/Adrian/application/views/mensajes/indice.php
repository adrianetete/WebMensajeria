<section>
    <script>
        var shift = false;
		
        $(function(){
                    
            //evento: click a Intro cuando el foco este en el textarea
            $("#texto_responder").keypress(function (tecla){

			shift = tecla.shiftKey;
			
                if(tecla.which === 13 && !shift){

                    tecla.preventDefault();
                    
                    $.post("<?php echo base_url()?>mensajes/enviar", 
                        //Codificamos todo el formulario en formato de URL
                        $("#formu_mensaje").serialize(), 
                        function () {
                            cargaConver();
                        }
                    );
                    $("#texto_responder").val("");
                }else{
					
				}
            });
			
			$("#texto_responder").keyup(function (tecla){
			
				shift = false;
			});
        });
        
        $(function(){
            $('#formu_mensaje').submit(function(evnt){
            evnt.preventDefault(); 

                $.post("<?php echo base_url()?>mensajes/enviar", 
                    //Codificamos todo el formulario en formato de URL
                    $("#formu_mensaje").serialize(), 
                    function () {
                        cargaConver();
                    }
                );
                $("#texto_responder").val("");
            });        
        });

        /* Funcion que carga en un DIV las conversaciones de los usuarios, se hace por 
         * AJAX para evitar el recargo de la página */    
        function cargaConver(){
            $.post("<?php echo base_url()?>mensajes/cargaConver",

                function(data){

                    $("#conversaciones").html(data);
                }
            );
        }

        //Cuando se cargue el DOM, se rellena el DIV  con las conversaciones.
        $(document).ready(function(){

            //Cargar conversaciones al inicio
            cargaConver();
        });
    </script>

    <?php
    /*  Se debe de haber iniciado Sesion antes */
    if($this->session->userdata('nombre') != NULL){   ?>

    <div class="ContenedorDatos">
        Nuevo mensaje:
        <?php echo form_open('mensajes/enviar', 'id="formu_mensaje"')?>    
        <select name="usu">
                <?php 
                    foreach ($listaAmigos->result_array() as $amigo){
                        echo '<option value="'.$amigo['id_usuario'].'">'.$amigo['nombre'].' '.$amigo['apellido1'].' '.$amigo['apellido2'].'</option>';
                    }
                ?>
            </select>
       <!-- Cuerpo del mensaje -->
        <textarea id="texto_responder" name="texto" ></textarea>
        <br/>
        <input style="float: right;" type="submit" value="Enviar" />

        <?php echo form_close()?>
        
    </div>

    <div class="ContenedorDatos">
        
        <h3>Conversaciones</h3>
        <div id="conversaciones"></div>
        
    </div>
    <?php }else{
        //Redireccionar a la pagina principal si no se tiene acceso
        header('Location: '.base_url());    
    }
    ?>
</section>