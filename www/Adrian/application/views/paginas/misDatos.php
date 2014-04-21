<?php
    if($this->session->userdata('nombre') != NULL){  
?>

<section id="misDatos">
    <br/>
    
    <div class="ContenedorDatos" style="height:100%">
    <article class="colizquierda">
        <ul>
            <li>
                <a onclick="$('.misDatosPersonal').show();$('.misDatosFoto').hide();">Datos Personales</a>
            </li>
            <li>
                <a onclick="$('.misDatosFoto').show();$('.misDatosPersonal').hide();">Cambiar Foto</a>
            </li>
        </ul>
    </article>
    
    <article class="cuerpoCentro">
        
        <section class="misDatosPersonal">
            
            Nombre:         <input type="text" value="<?= $this->session->userdata('nombre') ?>"/>
            <br/>
            Apellidos:      <input type="text" value="<?= $this->session->userdata('apellido1') ?>"/>
                            <input type="text" value="<?= $this->session->userdata('apellido2') ?>"/>
            <br/>
            Provincia:      <input type="text" value="<?= $this->session->userdata('provincia') ?>"/>
            <br/>
            Codigo Postal:  <input type="text" value="<?= $this->session->userdata('codigo_postal') ?>"/>
			<br/>
            Contrase&ntilde;a Actual:     <input type="text" value=""/>
            <br/>
            Contrase&ntilde;a Nueva:     <input type="text" value=""/>
            <br/>
            Repetir contrase&ntilde;a Nueva:<input type="text" value=""/>
        </section>
        <section class="misDatosFoto" style="display: none">
            Foto actual:<br/>
            <?php 
                if(isset($rutaPerfil))
                    echo '<img id="fotoPrinci" height="180" src="'.$rutaPerfil.'"/>';
                else
                    echo '<div id="fotoPrinci" style="width:180px; height:180px;"></div>';
            ?>
            <br/>
            Cambiar:
            <?php echo form_open(base_url()); ?>
                <input name="foto" type="file"/>
                
                <input type="submit" value="Guardar"/>
            <?php echo form_close(); ?>
        </section>
    </article>
    </div>
</section>
<?php
    }else{
        //Redireccionar a la pagina principal si no se tiene acceso
        header('Location: '.base_url());
    }
?>