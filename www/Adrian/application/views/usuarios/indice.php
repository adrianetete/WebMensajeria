<section>
    <script>

        //Array que va a guardar el HTML de cada fila (si se da a Modificar), para
        //despues recuperarle si se pulsa Cancelar
        var anteriorHTML = new Array();
	
		<?php
			echo "var propiedades = new Array();";
			foreach ($carpetas as $propiedad){ 
				echo "propiedades['ID".$propiedad['id_usuario']."'] = '".$propiedad['carpeta']."';";
			}
		?>
		
        function modificarUsuario(fila){
		
            //fila -> Toda la fila que activa el evento
            var idFila = $(fila).attr("id");
            //console.log("ID: "+idFila);

            //enlaces -> va a servir de control para evitar que se modifique
            // una fila que ya se está modificando, o que se seleccione una fila 
			// que no contenga datos de un usuario
            var enlaces = $("#"+idFila+" :last-child").attr("class");        
            //console.log(enlaces);

            if(enlaces === "Enl"){

                var nombre = $("#"+idFila+"Nom").text();
                var tipo = $("#"+idFila+"Tip").text();
                var apellido1 = $("#"+idFila+"Ape1").text();
                var apellido2 = $("#"+idFila+"Ape2").text();
                var provincia = $("#"+idFila+"Pro").text();
                var codigoPostal = $("#"+idFila+"Cod").text();
                var email = $("#"+idFila+"Ema").text();

                //Guarda en el Array el HTML que contenia antes de cambiarle                    
                anteriorHTML['ID'+idFila+''] = $("#"+idFila).html();

                //console.log(nombre);
                //console.log(apellidos);
                //console.log(enlaces);

                //Nuevo HTML con input para modificar los valores de la fila        
                var nuevoHTML = 
                  '<td class="centrado">'+idFila+'</td>'
                 +'<td class="centrado" id="'+idFila+'TipM">'+tipo+'</td>'
                 +'<td id="'+idFila+'NomM"><input type="text" value="'+nombre+'"></td>'
                 +'<td id="'+idFila+'Ape1M"><input type="text" value="'+apellido1+'"></td>'
                 +'<td id="'+idFila+'Ape2M"><input type="text" value="'+apellido2+'"></td>'
                 +'<td id="'+idFila+'ProM"><input type="text" value="'+provincia+'"></td>'
                 +'<td id="'+idFila+'CodM"><input type="text" value="'+codigoPostal+'"></td>'
                 +'<td id="'+idFila+'EmaM"><input class="inputGran" type="text" value="'+email+'"></td>'
                 +'<td class="EnlM">&nbsp;&nbsp;<a title="No modificar" onclick="restablecer(this.parentNode.parentNode)"><span>Cancelar</span></a>&nbsp;<a title="Guardar" onclick="guardar(this.parentNode.parentNode)">Guardar</a>&nbsp;</td>';

                $("#"+idFila).html(nuevoHTML);
            }
        }

        //Devuelve a la fila su contenido original(al cancelar)
        function restablecer(fila){

            var idFila = $(fila).attr("id");        
            $("#"+idFila).html(anteriorHTML['ID'+idFila+'']);       
        }

        //Guarda los datos del Usuario una vez modificados
        function guardar(fila){

            var idFila = $(fila).attr("id");

            var nombre = $("#"+idFila+"NomM input").val();
            var tipo = $("#"+idFila+"TipM").text();
            var apellido1 = $("#"+idFila+"Ape1M input").val();
            var apellido2 = $("#"+idFila+"Ape2M input").val();
            var provincia = $("#"+idFila+"ProM input").val();
            var codigoPostal = $("#"+idFila+"CodM input").val();
            var email = $("#"+idFila+"EmaM input").val();

            /*console.log("ID: "+idFila);
            console.log("Nombre: "+nombre);
            console.log("Tipo: "+tipo);
            console.log("Apellido 1: "+apellido1);
            console.log("Apellido 2: "+apellido2);
            console.log("Provincia: "+provincia);
            console.log("Codigo Postal: "+codigoPostal);
            console.log("Email: "+email);*/

            //Evitar enviar con datos vacios
            if(!(nombre === "" || codigoPostal === "" || email === "")){

                //Llamada que envia los datos para modificar el usuario
                $.post("<?php echo base_url()."usuarios/modificar/"?>",        
                    {
                        //Datos Usuario
                        id: idFila, 
                        nombre: nombre,
                        tipo: tipo,
                        apellido1: apellido1,
                        apellido2: apellido2,
                        provincia: provincia,
                        codigoPostal: codigoPostal,
                        email: email
                    },            
                    function(data){
                        //Funcion despues de realizar la llamada                
                        if(data === "si"){

                            //Usuario modificado con Exito
                            mensajeConsola("¡Modificado!");

                            var cambiar = "";
                            cambiar += "<td class='centrado'>"+idFila+"</td>";
                            cambiar += "<td class='centrado' id='"+idFila+"Tip'>"+tipo+"</td>";
                            cambiar += "<td id='"+idFila+"Nom'>"+nombre+"</td>";
                            cambiar += "<td id='"+idFila+"Ape1'>"+apellido1+"</td>";
                            cambiar += "<td id='"+idFila+"Ape2'>"+apellido2+"</td>";
                            cambiar += "<td id='"+idFila+"Pro'>"+provincia+"</td>";
                            cambiar += "<td id='"+idFila+"Cod'>"+codigoPostal+"</td>";
                            cambiar += "<td id='"+idFila+"Ema'>"+email+"</td>";

                            cambiar += "<td class='Enl' class='centrado'>";
                                //cambiar += "<a href='usuarios/"+idFila+"'>Ver</a>&nbsp;&nbsp;&nbsp;&nbsp;";
                                cambiar += "<a title='Borrar' onclick='borrar(this.parentNode.parentNode);'>Borrar</a>";
                            cambiar += "</td>";

                            $(fila).html(cambiar);
                        }else{
                            //Error al modificar, o no se ha modificado la fila
                            mensajeConsola("Error al Modificar");
                            restablecer(fila);
                        }
                    }
                );
            }else{
                //Error en la validacion de los datos
                mensajeConsola("Hay campos que no pueden estar en blanco");
            }
        }

        //Borra un usuario de la BD (al pulsar el boton borrar)
        function borrar(fila){

            //ID de la fila (o usuario) que se va a borrar
            var idFila = $(fila).attr("id");        
            //console.log("Borrar: "+idFila);

            //Recoger el campo nombre...
            var nombre;
            nombre = $("#"+idFila+"Nom").text();//...si esta como texto (no se esta modificando)      
            if(nombre === ""){
                nombre = $("#"+idFila+"NomM input").val();//... o si esta como input (se esta modificando)
            }

            //Confirmacion antes de borrar
            if(confirm("Se borrará "+nombre)){

                //El usuario pulso aceptar, en la confirmación
               console.log("Borrar");

               $.get("<?php echo base_url()."usuarios/borrar/"?>",           
                {
                    id_usu: idFila
                },
                function(data){
				
				/*
				
                    if(data === "si"){
                        //El servidor devolvió un SI
                        mensajeConsola("Usuario Borrado");
                        $(fila).remove();
                    }else{

                        mensajeConsola("Error al Borrar");
                    }*/
					switch(data){
					
						case "1": mensajeConsola("Usuario y carpeta borrados."); 
								  $(fila).remove();
								  propiedades['ID'+idFila] = undefined;
								  break;
								  
						case "2": mensajeConsola("Este usuario no se puede borrar."); break;
						
						default: mensajeConsola("Usuario borrado.");
								 $(fila).remove();
								 borrarCarpetaNoVacia(idFila, nombre);
								 propiedades['ID'+idFila] = undefined;
								 break;
						
					}
                });          
				
           }else{
               //El usuario pulso cancelar, en la confirmación
               console.log("No Borrar");

           }
        }
		
		function borrarCarpetaNoVacia(id, nombre){
			
			var carpeta = propiedades['ID'+id];
		
			if(confirm("¿Desea borrar tambien la carpeta de "+nombre+" ["+carpeta+"] y TODO su contenido?")){
			
				console.log("<?php echo base_url()."usuarios/borrarCarpetaEntera/"?>"+carpeta);
				$.get("<?php echo base_url()."usuarios/borrarCarpetaEntera/"?>"+carpeta);          
			}
		}

        //Crea una nueva fila, para rellenar los datos del nuevo usuario
        function nuevo(fila){

            var nuevoID =  calcularNuevoID();

           //console.log(fila);
           var nuevaFila = 
                   '<tr id="'+nuevoID+'">'
                    +'<td class="centrado">'+nuevoID+'</td>'
                    +'<td class="centrado" id="'+nuevoID+'TipN">'+'0'+'</td>'
                    +'<td id="'+nuevoID+'NomN"><input type="text" value=""></td>'
                    +'<td id="'+nuevoID+'Ape1N"><input type="text" value=""></td>'
                    +'<td id="'+nuevoID+'Ape2N"><input type="text" value=""></td>'
                    +'<td id="'+nuevoID+'ProN"><input type="text" value=""></td>'
                    +'<td id="'+nuevoID+'CodN"><input type="text" value=""></td>'
                    +'<td id="'+nuevoID+'EmaN"><input class="inputGran" type="text" value=""></td>'
                    +'<td class="EnlM"><a title="Añadir" onclick="crearRegistro(this.parentNode.parentNode)">Añadir</a></td>'
                 +'</tr>';

            var botonNuevo = 
                    '<tr>'
                        +'<td class="centrado"><button title="Nuevo" onclick="nuevo(this.parentNode.parentNode)">+</button></td>'
                        +'<td colspan="8" class="centrado nota">Por defecto, los usuarios añadidos tienen contraseña = 12345</td>'
                   +'</tr>';

            $('#tablaUsuarios table').append(nuevaFila+botonNuevo);
            //Borra la fila del boton, ya que crea otra nueva
            $(fila).remove();
        }    

        //Inserta el Registro en la base de datos
        function crearRegistro(fila){

            //console.log(fila);
            var idFila = $(fila).attr("id");

            var nombre = $("#"+idFila+"NomN input").val();
            var tipo = $("#"+idFila+"TipN").text();
            var apellido1 = $("#"+idFila+"Ape1N input").val();
            var apellido2 = $("#"+idFila+"Ape2N input").val();
            var provincia = $("#"+idFila+"ProN input").val();
            var codigoPostal = $("#"+idFila+"CodN input").val();
            var email = $("#"+idFila+"EmaN input").val();

            /*console.log("ID: "+idFila);
            console.log("Nombre: "+nombre);
            console.log("Tipo: "+tipo);
            console.log("Apellido 1: "+apellido1);
            console.log("Apellido 2: "+apellido2);
            console.log("Provincia: "+provincia);
            console.log("Codigo Postal: "+codigoPostal);
            console.log("Email: "+email);*/

            if(!(nombre === "" || codigoPostal === "" || email === "")){
                //Llamada que envia los datos para crear el usuario
                $.post("<?php echo base_url()."usuarios/crearDesdeTabla/"?>",        
                    {
                        //Datos Usuario
                        id: idFila, 
                        nombre: nombre,
                        tipo: tipo,
                        apellido1: apellido1,
                        apellido2: apellido2,
                        provincia: provincia,
                        codigoPostal: codigoPostal,
                        email: email
                    },            
                    function(data){
                        //Funcion despues de realizar la llamada                      
                        if(data !== "no"){

                            //Datos correctos
                            var nuevaFila;

                            nuevaFila += "<td class='centrado'>"+idFila+"</td>";
                            nuevaFila += "<td class='centrado' id='"+idFila+"Tip'>"+tipo+"</td>";
                            nuevaFila += "<td id='"+idFila+"Nom'>"+nombre+"</td>";
                            nuevaFila += "<td id='"+idFila+"Ape1'>"+apellido1+"</td>";
                            nuevaFila += "<td id='"+idFila+"Ape2'>"+apellido2+"</td>";
                            nuevaFila += "<td id='"+idFila+"Pro'>"+provincia+"</td>";
                            nuevaFila += "<td id='"+idFila+"Cod'>"+codigoPostal+"</td>";
                            nuevaFila += "<td id='"+idFila+"Ema'>"+email+"</td>";

                                nuevaFila += "<td class='Enl' class='centrado'>";
                                    //echo "<a href='usuarios/".$news_item['id_usuario']."'>Ver</a>&nbsp;&nbsp;&nbsp;&nbsp;";
                                    nuevaFila += "<a title='Borrar' onclick='borrar(this.parentNode.parentNode);'>Borrar</a>";
                                    //echo "<a onclick='modificarUsuario(this.parentNode.parentNode);'>Modificar</a>";
                                nuevaFila += "</td>";

                            $("#"+idFila).html(nuevaFila);
                            $("#"+idFila).addClass("si");
                            cargaEventos();
                            mensajeConsola("¡Usuario añadido!");
							
							propiedades['ID'+idFila] = data;
                        }else{
                            mensajeConsola("Se ha producido un Error al crear el Usuario");
                        }
                    }
                );
            }else{
                //Error en la validacion de los datos
                mensajeConsola("Hay campos que no pueden estar en blanco");
            }
        }

        //Se encarga de dar un ID al nuevo usuario que se va a insertar
        function calcularNuevoID(){

            var id = 0;
            $("#tablaUsuarios table tr").each(function(){

                if(parseInt($(this).attr("id")) > id){
                    id = $(this).attr("id");
                }
            });
            id++;
            return id;
        }

        //Funcion encargada de mostrar los mensajes en el div #consola
        function mensajeConsola(texto){

            $("#consola").html(texto);
            //Mostrar vacío tras 1500ms
            setTimeout(function(){$("#consola").html("&nbsp;");}, 2500);
        }

        /* Los eventos tienen que volver a cargarse al añadir una fila nueva */
        function cargaEventos(){
            //Evento al hacer click derecho en una fila
            $('tr').bind('contextmenu', function(e) {
								
                //Evitar ejecucion del evento
                e.preventDefault();
                //modificar la fila
                modificarUsuario($(this));
            });
        }

        $(document).ready(function(){      

            cargaEventos();        
        });
    </script>

    <?php
    /* Solo va a permitir la entrada en esta página a los Administradores
     * (deben de haber iniciado Sesion antes) */
    if($this->session->userdata('tipo') == 1){   ?>

    <div class="ContenedorDatos">
        <div id="consola">&nbsp;</div>
        <div id="tablaUsuarios">
        <span>* Boton derecho del ratón sobre una fila para Modificar.</span>

            <table>
                            <thead>
                                    <tr>        
                                            <td class="columnaPeq">ID</td>
                                            <td class="columnaPeq">Tipo</td>
                                            <td class="columna">Nombre</td>
                                            <td class="columna">Apellido 1</td>     
                                            <td class="columna">Apellido 2</td>
                                            <td class="columna">Provincia</td>    
                                            <td class="columna">Codigo Postal</td>
                                            <td class="columnaGran">Email</td>
                                            <td class="columnaOper">Operaciones</td>
                                    </tr>
                            </thead>
                <?php
                    foreach ($usuarios as $usu){ 

                    $id = $usu['id_usuario'];

                    echo "<tr id='".$id."' class='si'>";
                        echo "<td class='centrado'>".$id."</td>";
                        echo "<td class='centrado' id='".$id."Tip'>".$usu['tipo']."</td>";
                        echo "<td id='".$id."Nom'>".$usu['nombre']."</td>";
                        echo "<td id='".$id."Ape1'>".$usu['apellido1']."</td>";
                        echo "<td id='".$id."Ape2'>".$usu['apellido2']."</td>";
                        echo "<td id='".$id."Pro'>".$usu['provincia']."</td>";
                        echo "<td id='".$id."Cod'>".$usu['codigo_postal']."</td>";
                        echo "<td id='".$id."Ema'>".$usu['email']."</td>";

                        echo "<td class='Enl' class='centrado'>";
                            //echo "<a href='usuarios/".$news_item['id_usuario']."'>Ver</a>&nbsp;&nbsp;&nbsp;&nbsp;";
                            echo "<a title='Borrar' onclick='borrar(this.parentNode.parentNode);'>Borrar</a>";
                            //echo "<a onclick='modificarUsuario(this.parentNode.parentNode);'>Modificar</a>";
                        echo "</td>";
                    echo "</tr>";

                    }
                ?>
                <tr>
                    <td class="centrado"><button onclick="nuevo(this.parentNode.parentNode)" title="Nuevo">+</button></td>
                    <td colspan="8" class="centrado nota">Por defecto, los usuarios añadidos tienen contraseña = 12345</td>
                </tr>
            </table>

        </div>
    </div>
    <?php   }else{
           //Redireccionar a la pagina principal si no se tiene acceso
           header('Location: '.base_url());
    }  ?>
</section>