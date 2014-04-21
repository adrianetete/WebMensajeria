<!--

    ******* Pagina principal y de inicio de sesión ********

-->

<!-- Si la sesion NO se ha creado -->
<?php   if($this->session->userdata('nombre') == NULL){   ?>

<script type="text/javascript">
    $(function(){
        $('#formu_inicio').submit(function(evnt){
            //Evitamos que el evento submit recargue toda la página
            evnt.preventDefault(); 
            
            //Va a llamar a http://localhost/Adrian/principal/comprobar
            $.post("<?php echo base_url()?>principal/comprobar", 
                //Codificamos todo el formulario en formato de URL
                $("#formu_inicio").serialize(),
                function (data) {
                    
                    $("#respuesta").html(data);                    
                    if(data === ""){
                        //Si es correcto recarga la página
                        location.reload();
                    }
                }
            );
        });        
    });    
</script>
<section>
	<section id="formInicioSesion">
		
		<?php echo validation_errors();                    ?>
		<?php echo form_open('', 'id="formu_inicio"')      ?>    
			<ul>
				<li><label for="email">Email: </label></li>
				<li><label for="pass">Contrase&ntilde;a: </label></li>
				<li><input type="text" id="email" name="email"/></li>           
				<li><input type="password" id="pass" name="pass" /></li>        
			</ul>
			<input type="submit" name="submit" value="Entrar"/>
		<?php echo form_close()                            ?>
		
		<div id="inicioMas">
			<?php echo "<a href='".base_url()."nuevoUsuario'>¿Aún no eres usuario?</a>"; ?>
			<br/>
			<a>He olvidado la contraseña</a>    
		</div>        
		<div id="respuesta" style="color:red;"></div>
		
	</section>
</section>
<!-- Si la sesion SI se ha creado -->
<?php    }else{     ?>

<section id="perfilUsuario">
    
    <?php 
    if(isset($rutaPerfil))
        echo '<img id="fotoPrinci" height="180" src="'.$rutaPerfil.'"/>';
    else
        echo '<div id="fotoPrinci" style="width:180px; height:180px;"></div>';
    ?>
    
    <div class="ContenedorDatos">
        <article class="datos">
            <header>
                <h1><?= $this->session->userdata('nombre')?>&nbsp;<?= $this->session->userdata('apellido1') ?>&nbsp;<?= $this->session->userdata('apellido2') ?>&nbsp;</h1>
            </header>

            <span class="spanEstado">¿Qué piensas hoy?</span>
            <br/>
            <textarea>Escribe algo...</textarea>

        </article>
    </div>
    
    <article class="colizquierda ContenedorDatos">

        columna izquierda

    </article>
    
    <article class="cuerpoPrincipal ContenedorCuerpo">

        cuerpo principal

    </article>
    
</section>
<?php 
        
    }
?>