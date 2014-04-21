<?php
class Usuarios_modelo extends CI_Model {
    	
    public function __construct(){
		
        $this->load->database();
    }
	
    public function getUsuarios($id_usuario = FALSE){

        if ($id_usuario === FALSE){
        
            //$this->db->get('nombre_tabla', numero_filas)
            $query = $this->db->get('usuario');
            return $query->result_array();
        }
        
        $query = $this->db->get_where('usuario', array('id_usuario' => $id_usuario));
        return $query->row_array();
    }
    
    public function getAmigos(){

        $query = $this->db->query("SELECT * FROM usuario WHERE NOT id_usuario = '".$this->session->userdata('id_usuario')."';");
        return $query;
    }
    
    public function calculaIDUsuario(){

        $query = $this->db->query('SELECT id_usuario FROM usuario WHERE id_usuario IN(SELECT MAX(id_usuario) FROM usuario);');

        $fila = $query->row();
        
    return (int)($fila->id_usuario + 1);
}
    
    public function crearDesdeTabla(
            $id, $nombre, $tipo, $apellido1, $apellido2,
            $provincia, $codigoPostal, $email){
        
        $sql = "INSERT INTO usuario ".
                "(`id_usuario`, `pass`, `tipo`, `nombre`, `apellido1`, `apellido2`, `provincia`, `codigo_postal`, `email`) ".
                "VALUES ".
                "(".
                    "'".$id."', ".
                    "'12345', ".
                    "'".$tipo."', ".
                    "'".$nombre."', ".
                    "'".$apellido1."', ".
                    "'".$apellido2."', ".
                    "'".$provincia."', ".
                    "'".$codigoPostal."', ".
                    "'".$email."'
                 )".
        ";";
        
        //echo $sql;
        $this->db->query($sql);
        		
        //Devuelve True si se ha creado la fila
        return $this->db->affected_rows(); 
    }
    
    public function borrar($id_usuario){
        			
		//Recoger la carpeta del usuario, antes de borrarle			
		$sqlCar = 'SELECT '.
			'carpeta '.
			'FROM propiedades '.
			'INNER JOIN usuario ON propiedades.id_usuario = usuario.id_usuario '.
			'WHERE propiedades.id_usuario = "'.$id_usuario.'" '.
		';';
		$queryCar = $this->db->query($sqlCar);
		$carpetaObjeto = $queryCar->row();
		
		if($carpetaObjeto != null) {
			$carpeta = $carpetaObjeto->carpeta;
		}else{
			$carpeta = null;
		}
		
		$this->db->query("DELETE FROM usuario WHERE id_usuario = '".$id_usuario."' AND NOT TIPO = 1;");
		
		if($this->db->affected_rows()){
			
			if($this->borrarCarpeta($carpeta) == true){
				
				return 1;
			}else{
				$carpUsuario = $carpeta;
				return 3;
			}
		}else{
			
			return 2;
		}
		
    }
	
	//Intenta borrar una carpeta
	function borrarCarpeta($carpeta){

		return rmdir("./images/usuarios/".$carpeta."/");
	}
    
	public function borrarCarpetaEntera($carpUsuario){
	
		if($carpUsuario != null && $carpUsuario != ""){
		
			$ruta = "./images/usuarios/".$carpUsuario;
			
			if($ruta != "./images/usuarios/")
			$this->borrarTodoCarpeta($ruta);
		}
	}
	
	//Borra una carpeta y todo su contenido
	//$this->borrarTodoCarpeta("./images/usuarios/aaa/");
	function borrarTodoCarpeta($carpeta){
	
		foreach(glob($carpeta . "/*") as $archivos_carpeta){
		
			if (is_dir($archivos_carpeta)){
			
				$this->borrarTodoCarpeta($archivos_carpeta);
			}
			else{
				unlink($archivos_carpeta);
			}
		}
		rmdir($carpeta);
	}
	
	//Modifica los datos de un usuario
    public function modificar(
            $id, $nombre, $tipo, $apellido1, $apellido2,
            $provincia, $codigoPostal, $email){
        
        $sql = "UPDATE usuario ".
                    "SET nombre       = '".$nombre."', ".
                    "apellido1        = '".$apellido1."', ".
                    "apellido2        = '".$apellido2."', ".
                    "provincia        = '".$provincia."', ".
                    "codigo_postal    = '".$codigoPostal."', ".
                    "email            = '".$email."' ".
                
               "WHERE id_usuario = '".$id."' ".
        ";";
        //echo $sql;
        $this->db->query($sql);
        //Devuelve True si se ha modificado la fila
        return $this->db->affected_rows();        
    }
    
    //Comprobar si el usuario esta en la base de datos
    public function comprobarUsuario($correo, $pass){
        
        $consultaComprobar = 'SELECT * '.
            'FROM usuario'.
           ' WHERE'.
                ' email = '.$this->db->escape($correo).
                ' AND pass = '.$this->db->escape($pass).';';
        
        $usuario = $this->db->query($consultaComprobar);
        
        if ($usuario->num_rows() > 0){
            // devolvemos TRUE
            return $usuario;
          // si el resultado de la query no es positivo
        } else {
            // devolvemos FALSE
            return null;
        }
    }
    
	public function nuevaPropiedad($id, $carpeta){
		
		$sql = 'INSERT INTO propiedades VALUES('.	
		
					'NULL, '.
					'"'.$id.'", '.
					'"'.$carpeta.'", '.
					'"defecto.png"'.
				')'.
		';';
		
		$this->db->query($sql);
	}
		
    public function fotoPerfil($usuario){
        
        $sql = "SELECT perfil FROM propiedades WHERE ".
                "propiedades.id_usuario = '".$usuario."'".
        ";";
        //echo $sql; 
        $resultado = $this->db->query($sql);
        
        $foto = $resultado->row();
		return $foto->perfil;
    }
	
	public function getPropiedades($id_usuario = null){
	
		if($id_usuario == null){
			$sql = "SELECT * FROM propiedades ".
			";";
		}else{
			$sql = "SELECT * FROM propiedades ".
					"WHERE id_usuario = '".$id_usuario."'".
			";";		
		}
        $resultado = $this->db->query($sql);
		
		return $resultado->result_array();
	}
}
