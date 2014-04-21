<?php
/*
 * 
 *          Controlador Mensajes
 * 
 */
class Mensajes extends CI_Controller{
    
    public function __construct() {
        
        //hay que llamar al constructor padre, para que el constructor local no le sustituya
        parent::__construct();
        $this->load->model('mensajes_modelo');
        $this->load->model('usuarios_modelo');
    }
    
    public function index(){
        
        $data['title'] = 'Mensajes';
        $data['listaAmigos'] = $this->usuarios_modelo->getAmigos();
        $this->load->view('templates/header', $data);
        $this->load->view('mensajes/indice');
        $this->load->view('templates/footer');
    }

    public function enviar(){
        
        //ID del usuario que envia
        $usu_remite = $this->session->userdata('id_usuario');
        //Id del Usuario destino
        $usu_destino = $this->input->post("usu");
        $texto = $this->input->post("texto");

        $this->mensajes_modelo->enviar($usu_remite, $usu_destino, $this->filtrarCodigo($texto));        
    }
    
    
    function enviarDentro($texto, $usu_destino){
        
        //ID del usuario que envia
        $usu_remite = $this->session->userdata('id_usuario');

        $this->mensajes_modelo->enviar($usu_remite, $usu_destino, $this->filtrarCodigo($texto));
        
    }
        
    /*********** Conversaciones  ****************/
    public function verConversacion($id_amigo){
        
        $data['conversacion'] = $this->mensajes_modelo->getConversacionYoTu($this->session->userdata('id_usuario'), $id_amigo);
        if (empty($data['conversacion']))
        {
            echo 'No hay conversaciones';
        }else{
            //echo var_dump($data['conversacion']);             
            $data['title'] = 'Conversacion';
            $data['amigo'] = $id_amigo;
            $data['fechaHoy'] = $this->fechaHoy();
            $this->load->view('templates/header', $data);
            $this->load->view('mensajes/conver', $data);
            $this->load->view('templates/footer');  
        }
   }
   
   /* Método que cada vez que se ejecuta va a realizar una consulta a la
    * BD y va a recuperar las conversaciones con ID superior al último mensaje
    * recibido por el usuario    */
   public function recarga(){
       
	   //Evita que salte la excepcion de tiempo limite de 300 segundos, con esto hacemos que sea infinita
	   set_time_limit(0);
	
       $mensaje = $this->input->get("mensaje");
       $usuario = $this->input->get("usuario");
       
       if($mensaje != null && $usuario != null){
           
           $this->enviarDentro($mensaje, $usuario);
           die();
       }
       
      //Va a devolver los mensajes nuevos
      $usuConver = $this->input->get("idAmi");
      $idUltimoMensaje = $this->input->get("idMen");
      
      $data['fechaHoy'] = $this->fechaHoy();      
      //Consulta
      $data['nuevos'] = $this->mensajes_modelo->getNuevos($this->session->userdata('id_usuario'), $usuConver, $idUltimoMensaje);
      
      while($data['nuevos'] == null){
 
          usleep(10000); // Descansa la CPU 10 milisegundos
          clearstatcache();
          $data['nuevos'] = $this->mensajes_modelo->getNuevos($this->session->userdata('id_usuario'), $usuConver, $idUltimoMensaje);
      }           
        
      //Carga la vista que trata el/los mensajes nuevo(s)
      $this->load->view('mensajes/mensajito', $data);
      flush();
   }
   
   public function cargaConver(){

        $data['IdUsuConver'] = $this->mensajes_modelo->usuariosConversacion($this->session->userdata('id_usuario'));

        //Carga la vista que trata el/los mensajes nuevo(s)
        $this->load->view('mensajes/conversaciones', $data);
    }
	
	/* Borrar las conversaciones */
	public function limpiarTodo(){
	
		if($this->session->userdata('tipo') == 1){
		
			$this->mensajes_modelo->limpiarTodo();
		}else{
			header('Location: '.base_url().'404');
		}
	}
    
   //Devuelve la fecha de hoy con formato [YYYY-mm-dd] 2013-12-01
   function fechaHoy(){
       
        $fechaActual = getdate();
    
        //Si el dia es menor de 10, se le añade un 0 delante
        if($fechaActual['mday'] < 10){
            $fechaActual['mday'] = '0'.$fechaActual['mday'];
        }
        
        //Si el mes es menor de 10, se le añade un 0 delante
        if($fechaActual['mon'] < 10){
            $fechaActual['mon'] = '0'.$fechaActual['mon'];
        }

        //Forma la cadena y la devuelve
        $fechaHoy = ''.$fechaActual['year'].'-'.$fechaActual['mon'].'-'.$fechaActual['mday'];
        return $fechaHoy;
   }
 
   function filtrarCodigo($texto){
       
       $texto = strip_tags($texto);
       
       return $texto;
   }   
}