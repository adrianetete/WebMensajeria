<?php
/*
 * 
 *          Controlador Principal
 * 
 */
class Principal extends CI_Controller{
    
    public function __construct() {
        
        parent::__construct(); 
        $this->load->model("usuarios_modelo");
    }
    
    public function index(){        
              
        $this->load->library('form_validation');
        
		if($this->session->userdata('nombre') == NULL){
		
		    $data['title'] = 'Iniciar Sesion';
		}else{
		    $data['title'] = $this->session->userdata('nombre').' '.$this->session->userdata('apellido1');
		}
        
        if($this->session->userdata('nombre') != NULL){ 
			$suPerfil = $this->cargarPerfil();        
			if($suPerfil != null)
			$data['rutaPerfil'] = base_url().'images/usuarios/'.$suPerfil;
        }
        
        $this->load->view('templates/header', $data);
        $this->load->view('paginas/iniciarSesion', $data);
        $this->load->view('templates/footer');
    }
    
    public function comprobar(){
            
            $correo = $this->input->post("email");
            $pass = $this->input->post("pass");
            
            //1º [Busca el usuario en la base de datos]
            $usu = $this->usuarios_modelo->comprobarUsuario($correo, $pass);
            
            if($usu != null){
                //[Si los datos son correctos, crea la variable de sesion]                                
                foreach ($usu->result() as $fila){
                    
                     $datasession = array(
                        'id_usuario'  => $fila->id_usuario,
                        'nombre'  => $fila->nombre,
                        'tipo'  => $fila->tipo,
                        'apellido1' => $fila->apellido1,
                        'apellido2' => $fila->apellido2,
                        'provincia' => $fila->provincia,
                        'codigo_postal' => $fila->codigo_postal,
                        'email' => $fila->email
                    );                    
                    $this->session->set_userdata($datasession);
                }
            }
            else{
                echo "Datos incorrectos";
            }
            
    }
    
    public function destruir(){
        
        $this->session->sess_destroy();
        redirect('../', 'refresh');
    }

    function cargarPerfil(){
        
        $perfil = $this->usuarios_modelo->fotoPerfil($this->session->userdata('id_usuario'));
        
        return $perfil;
    }
    
    public function cambiarDatos(){
        
        $data['title'] = 'Mis Datos';
        
        $suPerfil = $this->cargarPerfil();
        
        if($suPerfil != null)
        $data['rutaPerfil'] = base_url().'images/usuarios/'.$suPerfil;
        
        $this->load->view('templates/header', $data);
        $this->load->view('paginas/misDatos', $data);
        $this->load->view('templates/footer');    
    }	
}
?>
