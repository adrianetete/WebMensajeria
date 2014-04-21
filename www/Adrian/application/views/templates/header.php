<html>
    <head>
        <title><?php echo $title ?> - Red Social</title>
        <meta charset="UTF-8">
		
        <? echo '<link rel="stylesheet" type="text/css" href="'.base_url().'css/estilo.css" />'; ?>
        <? echo '<link rel="stylesheet" type="text/css" href="'.base_url().'css/colores.css" />'; ?> 
		
        <? echo '<script src="'.base_url().'js/jquery.js"></script>'; ?>
        <? echo '<script src="'.base_url().'js/jquery-validate.js"></script>'; ?>
		
		<? echo '<script src="'.base_url().'js/interfaz.js"></script>'; ?>
    </head>
    <body>
        <header></header>
        
        <?php if($this->session->userdata('nombre') != null){   ?>
            <nav id="navegacionPrincipal">
                <ul>
                    <li><?php echo"<a href='".base_url()."'>Inicio</a>";?></li>
                    <li><?php echo"<a href='".base_url()."mensajes'>Mensajes</a>";?></li>                                        
                <?php if($this->session->userdata('tipo') == 1){ ?>
                    
                    <li><?php echo "<a href='".base_url()."usuarios'>Administrar</a>"; ?></li>					
					<li><?php echo "<a href='".base_url()."nuevoUsuario'>Crear</a>"; ?></li> 
					
                <?php } ?>
                        

					
				<?php if($this->session->userdata('tipo') != null){ ?>
					<li>
                                            <a>
                                                <?php echo "<img width='18' src='".base_url()."images/avatar2.png'/>"; ?>
                                                <?= $this->session->userdata('nombre') ?> 
                                                <?= $this->session->userdata('apellido1') ?> 
                                                <?= $this->session->userdata('apellido2') ?>	
                                            </a>	
                                                <ul class="navegacionSecundario">
                                                    <li><?php echo "<a href='".base_url()."miPerfil'>Mis Datos</a>"; ?></li>
                                                    <li><a>Ayuda</a></li>
                                                    <li>
                                                            <?php echo "<a href='".base_url()."principal/destruir'>Cerrar Sesion</a>"; ?>
                                                    </li>
                                                </ul>							
					</li>
				<?php } ?>
                </ul>
            </nav>
        <?php }   ?>