<?php

/*********************************************
 * 
 *          Controlador Usuarios
 * 
 *********************************************/
 
class Usuarios extends CI_Controller {
    
    public function __construct(){
        
        parent::__construct();
        $this->load->model('usuarios_modelo');
    }
    
    public function index(){
        $data['usuarios'] = $this->usuarios_modelo->getUsuarios();
		$data['carpetas'] = $this->usuarios_modelo->getPropiedades();
		
        $data['title'] = 'Archivo de usuarios';
        $this->load->view('templates/header', $data);
        $this->load->view('usuarios/indice', $data);
        $this->load->view('templates/footer');
    }
    
    public function formCrear(){
        $data['title'] = 'Nuevo usuario';
            
        $this->load->view('templates/header', $data);
        $this->load->view('usuarios/crear');
        $this->load->view('templates/footer');
    }
    
    /* La unica diferencia entre este método y crear() es que en la consulta
     * damos el ID que hemos calculado anteriormente, en vez de usar
     *  el autonumerico     */
    public function crearDesdeTabla(){
        		
        $id = ($this->input->post("id") != null) ? $this->input->post("id") : $this->calculaIDUsuario();
        $nombre = $this->input->post("nombre");
        $tipo = $this->input->post("tipo");
        $apellido1 = $this->input->post("apellido1");
        $apellido2 = $this->input->post("apellido2");
        $provincia = $this->input->post("provincia");
        $codigoPostal = $this->input->post("codigoPostal");
        $email = $this->input->post("email");
        
        //echo "Crear: <br/>";
        //echo $id." ".$nombre." ".$tipo." ".$apellido1." ".$apellido2." ....";

        if($this->usuarios_modelo->crearDesdeTabla(
                $id,
                $nombre,
                $tipo, 
                $apellido1,
                $apellido2,
                $provincia, 
                $codigoPostal,
                $email
       )){
	   
			$carpeta = $this->crearCarpeta();						
			$this->usuarios_modelo->nuevaPropiedad($id, $carpeta);
            echo $carpeta;	
			
        }else{
            echo "no";
        }    
    }
    
    function calculaIDUsuario(){
        
        $idNuevo = $this->usuarios_modelo->calculaIDUsuario();
        
        return $idNuevo;
    }
    
    public function borrar(){
        
        $id_usuario = $this->input->get("id_usu");
        echo $this->usuarios_modelo->borrar($id_usuario);

    }
	
    public function borrarCarpetaEntera($carpUsuario){

        echo $this->usuarios_modelo->borrarCarpetaEntera($carpUsuario);
    }
    
    public function modificar(){
        
        $id = $this->input->post("id");
        $nombre = $this->input->post("nombre");
        $tipo = $this->input->post("tipo");
        $apellido1 = $this->input->post("apellido1");
        $apellido2 = $this->input->post("apellido2");
        $provincia = $this->input->post("provincia");
        $codigoPostal = $this->input->post("codigoPostal");
        $email = $this->input->post("email");
        
        //echo "Modificar: <br/>";
        //echo $id." ".$nombre." ".$tipo." ".$apellido1." ".$apellido2." ....";
        
        if($this->usuarios_modelo->modificar(
                $id,
                $nombre,
                $tipo, 
                $apellido1,
                $apellido2,
                $provincia, 
                $codigoPostal,
                $email
       )){
            echo "si";
        }else{
            echo "no";
        }
    }
	
    /* * Crea una carpeta y devuelve el nombre * */
    function crearCarpeta(){

            $imagenesUsuarios = "./images/usuarios/";			
            $nombreCarpeta = rand(100000, 999999);

                    //Si no existe, la crea
                    if(!file_exists($imagenesUsuarios.$nombreCarpeta)){

                            mkdir($imagenesUsuarios.$nombreCarpeta);				
                            //echo $nombreCarpeta;
                    }else{
                    //Si existe, vuelve a intentarlo
                            $this->crearCarpeta();
                    }

            return $nombreCarpeta;
    }
    
}