<?php
class Mensajes_modelo extends CI_Model{
    
    public function __construct() {
        parent::__construct();
        //Carga la configuracion de conexion a la BD
        $this->load->database();
    }
	
	/* Limpia todos los historiales de conversaciones */
	public function limpiarTodo(){
		$sql = "DELETE FROM `mensaje_privado` WHERE 1;";
		$this->db->query($sql);
	}
	
    /* Busca en la tabla, los mensajes del usuario UNO al usuario DOS o viceversa, 
     * ya que puede haber mensajes del UNO al DOS que el otro no haya
     * respondido, o al revés.
     * 
     * $id_uno = ID del primer usuario
     * $id_dos = ID del segundo usuario
     * 
     * Devuelve un array con la conversacion entre dos personas       */
    public function getConversacionYoTu($id_uno, $id_dos){
        
        $sql = "SELECT DISTINCT
                u.`id_ump`,
                u.`usu_remite`,
                u.`usu_destinatario`,
                m.`id_mensaje`,
                m.`fecha`,
                a.nombre as \"Remite\",
                b.nombre as \"destinatario\",
                a.apellido1 as \"Remite_apellido1\",
                a.apellido2 as \"Remite_apellido2\",
                m.texto\n"
            . "FROM `u_mp` u, `usuario` a, `usuario` b, `mensaje_privado` m\n"
              
              //Sacar el nombre del Remite de la tabla USUARIO
            . "WHERE \n"
            . "a.nombre 
            in(select nombre from `usuario` where id_usuario = u.usu_remite)\n"
            
             //Sacar el nombre del Destinatario de la tabla usuarios
            . "AND\n"
            . "b.nombre 
            in(select nombre from `usuario` where id_usuario = u.usu_destinatario)\n"
              
             //Evitar el duplicado de los mensajes
            . "AND\n"
            . "m.id_mensaje
             = (select id_mensaje from `mensaje_privado` where id_mensaje = u.id_mensaje)"
                
            . "AND\n"
                . "(
                    (u.`usu_remite` = '".$id_uno."' 
                        AND 
                    u.`usu_destinatario` = '".$id_dos."') \n"
                . "OR\n"
                . "(u.`usu_destinatario` = '".$id_uno."' 
                        AND u.`usu_remite` = '".$id_dos."')
                   ) 
             ORDER BY m.`fecha` asc;";
            //echo $sql;
            $query = $this->db->query($sql);
            return $query->result_array();        
    }
    
/* Va a calcular el ID del mensaje, para insertarlo en la base de datos.
 * Se necesita calcularle antes, porque en las tablas U_MP y MENSAJE_PRIVADO
 * tienen que tener el mismo ID. No puede ser autonumérico.
 * 
 * Devuelve un número              */
public function getIdNuevoMensaje(){

    $query = $this->db->query('SELECT id_mensaje FROM mensaje_privado WHERE id_mensaje IN(SELECT MAX(id_mensaje) FROM mensaje_privado);');

    $fila = $query->row();
    return (double)($fila->id_mensaje + 1);
}
    
    
/* Accion de enviar un mensaje (guardarlo en la Base de datos)
 * 
 * $usu_remite = ID del usuario al que se le va a asignar como remite del mensaje
 * $usu_desti = ID del usuario al que se le va a asignar como destinatario del mensaje
 * $texto = contenido del mensaje
 * 
 * Devuelve, tras ejecutar la consulta, un mensaje de confirmacion     */
    public function enviar($usu_remite = 0, $usu_desti = 0, $texto = ''){
                        
        /* Pruebas */
//        echo 'Remite: '.$usu_remite.'<br/>';
//        echo 'Destinatario: '.$usu_desti.'<br/>';        
//        echo 'ID Mensaje: '.$this->getIdNuevoMensaje().'<br/>';
//        echo 'Mensaje: '.$texto.'<br/>';

        if($texto != "" && $texto != null){
            
            $query1 = 'INSERT INTO `mensaje_privado`(`id_mensaje`, `texto`) '
                .'VALUES('.$this->getIdNuevoMensaje().', "'.$texto.'");';

            $query2 = 'INSERT INTO `u_mp`(`usu_remite`, `usu_destinatario`, `id_mensaje`) '
                .' VALUES('.$usu_remite.', '.$usu_desti.', '.$this->getIdNuevoMensaje().');';

            if($this->db->query($query1) &&  $this->db->query($query2)){

                echo 'Mensaje Enviado';
            }else{

                echo 'Error al enviar';
            }
        }else{

            echo 'Mensaje no enviado';
            }
    }
    
    /* Devuelve un Array con los ID's de los usuarios 
     * con los que ha tenido algun tipo de conversacion
     */
    public function usuariosConversacion($id_usuario){
        
        $sql = "SELECT id_usuario, nombre, apellido1, apellido2 FROM USUARIO WHERE id_usuario in(SELECT DISTINCT usu_remite FROM U_MP where usu_destinatario = '".$id_usuario."') OR id_usuario in(SELECT DISTINCT usu_destinatario FROM U_MP where usu_remite = '".$id_usuario."')";
        
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    /* Busca en la tabla, la conversacion entre el usuario UNO y el usuario DOS
     * en la que el ID de dicha conversacion sea superior a ID ENVIADO
     * 
     * $id_uno = ID del primer usuario
     * $id_dos = ID del segundo usuario
     * $idCOnver = ID de la conver a partir de la que se quieren ver mensajes
     * 
     * Devuelve un array con la conversacion entre dos personas       */
    public function getNuevos($id_uno, $id_dos, $idConver){
        
        $consulta = 
            "SELECT DISTINCT    u.`id_ump`,
                                u.`usu_remite`,
                                u.`usu_destinatario`,
                                m.`id_mensaje`,
                                m.`fecha`,
                                a.nombre as \"Remite\",
                                b.nombre as \"destinatario\",
                                a.apellido1 as \"Remite_apellido1\",
                                a.apellido2 as \"Remite_apellido2\",
                                m.texto\n"
            ."FROM `u_mp` u, 
                    `usuario` a,
                    `usuario` b,
                    `mensaje_privado` m \n"

            ."WHERE \n"
                  . "a.nombre 
            in(select nombre from `usuario` where id_usuario = u.usu_remite)\n"
            
            . "AND\n"
                  . "b.nombre 
            in(select nombre from `usuario` where id_usuario = u.usu_destinatario)\n"
              
             //Evitar el duplicado de los mensajes
            . "AND\n"
                  . "m.id_mensaje
             = (select id_mensaje from `mensaje_privado` where id_mensaje = u.id_mensaje)"
            
            //Conversacion de UNO a DOS o de DOS a UNO
            . "AND\n"
                . "(
                    (u.`usu_remite` = '".$id_uno."' 
                        AND 
                    u.`usu_destinatario` = '".$id_dos."') \n"
             . "OR\n"
                . "(u.`usu_destinatario` = '".$id_uno."' 
                        AND u.`usu_remite` = '".$id_dos."')
                   )"
            
            . " AND\n"
            . "u.`id_ump` > '".$idConver."' \n"
             ."ORDER BY m.`fecha` asc;";
            //echo $consulta;
            $query = $this->db->query($consulta);
            return $query->result_array();   
    }
	
}
?>