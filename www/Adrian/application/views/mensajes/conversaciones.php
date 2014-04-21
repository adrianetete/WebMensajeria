<?php
    
//Bucle que genera las conversaciones con los distintos usuarios
foreach($IdUsuConver as $amigo){

    echo '<div style="border: 1px solid green; width:300px; height:30px; margin-bottom:10px">';    
    echo '<a href="'.base_url().'mensajes/verConversacion/'.$amigo['id_usuario'].'">'.$amigo['nombre'].' '.$amigo['apellido1'].' '.$amigo['apellido2'].'</a>';    
    echo '</div>';
}
?>
