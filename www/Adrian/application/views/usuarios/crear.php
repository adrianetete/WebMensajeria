<section>
    <script type="text/javascript">
        $(function(){
            $('#formu_nuevoUsuario').submit(function(evnt){
                
                //Evitamos que el evento submit recargue toda la página
                evnt.preventDefault(); 

                //Va a llamar a http://localhost/Adrian/usuarios/crear
                $.post("<?php echo base_url()?>usuarios/crear",
                
                    //Codificamos todo el formulario en formato de URL
                    $("#formu_nuevoUsuario").serialize(),

                    function (data) {
                        //$("#respuestaFormCrear").html(data);
                        
                        if(data === "si"){
                            
                            $("#respuestaFormCrear").html("¡Gracias por Registrate!");
                            location.href="<?= base_url() ?>";
							}else{
                            $("#respuestaFormCrear").html("¡Algo ha fallado!");
                            
                        }                    
                    }
                );
            });        
        });
        
        $(document).ready(function() {
            $("#formu_nuevoUsuario").validate({

                debug: true,

                rules: {

                   nombre: {
                        required: true,
                        minlength: 3,
                        maxlength: 15
                    },

                    email: {
                        required: true,
                        email: true,
                        minlength: 5,
                        maxlength: 30
                    },

                    pass: {
                        required: true
                    },

                    pass2: {                        
                        equalTo: "#crear_pass"		    	
                    }
                },

                messages: {

                    nombre: {
                        required: "Escribe tu nombre",                        
                        minlength: "Minimo 3 caracteres",                        
                        maxlength: "Maximo 15 caracteres"

                    },
                    email:{
                        required: "Dame tu Correo",
                        email: "Correo no valido",
                        minlength: "Minimo 5 caracteres",
                        maxlength: "Maximo 30 caracteres"
                        
                    },
                    pass: {
                        required: "Es necesaria contraseña"
                    },
                    pass2: {                        
                        equalTo: "No coinciden"		    	
                    }
                }

            });
        });
    </script>
    
    <div id="respuestaFormCrear"></div>
    
    <?php //echo "<a href='".base_url()."'>Volver</a>" ?>
    
    <div class="ContenedorTitulo">
        <h2>Reg&iacute;strate</h2>
    </div>
    
    <?php echo validation_errors(); ?>
    <?php echo form_open('', 'id="formu_nuevoUsuario"') ?>

    <div id="formCrearNombre">
            <label for="crear_nombre"><span class="obligatorio">*</span>Nombre</label>
            <br/>
            <input type="text" id="crear_nombre" name="nombre" />
    </div>

    <div id="formCrearApellidos">
            <label for="crear_apellido1">1&ordm; Apellido</label>
            <br />
            <input type="text" id="crear_apellido1" name="apellido1">
            <br />

            <label for="crear_apellido2">2&ordm; Apellido</label>
            <br />
            <input type="text" id="crear_apellido2" name="apellido2">
            <br />
    </div>

    <div id="formCrearEmail">
            <label for="crear_email"><span class="obligatorio">*</span>E-mail</label>
            <br />
            <input type="text" id="crear_email" name="email">
            <br />
    </div>

    <div id="formCrearPass1">
            <label for="crear_pass"><span class="obligatorio">*</span>Contrase&ntilde;a</label>
            <br />
            <input type="text" id="crear_pass" name="pass">
    </div>

    <div id="formCrearPass2">
            <label for="crear_pass2"><span class="obligatorio">*</span>Repetir contrase&ntilde;a</label>
            <br />
            <input type="text" id="crear_pass2" name="pass2">
    </div>

    <div id="formCrearProvincia">
            <label for="crear_provincia">Provincia</label>
            <br />
            <input type="text" id="crear_provincia" name="provincia">
    </div>

    <div id="formCrearCP">
            <label for="crear_cp">C&oacute;digo postal</label>
            <br />
            <input type="text" id="crear_cp" name="codigoPostal">
    </div>

    <input type="submit" name="submit" value="Unirme" />

    <?php echo form_close() ?>
</section>