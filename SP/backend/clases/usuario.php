 <?php
    require_once './clases/AccesoDatos.php';
    require_once './clases/AutentificadorJWT.php';
    
    class Usuario{

        public $correo;
        public $clave;
        public $nombre;
        public $apellido;
        public $perfil;
        public $legajo;
        public $foto;

        public static function SubirFoto($foto)
        {
            $pathOrigen = $_FILES['foto']['tmp_name'];   
            $pathDestino = '/fotos/' . date('h-m-s') . '-' . $_FILES['foto']['name'];
        
            move_uploaded_file($pathOrigen, "." . $pathDestino);    

            return $pathDestino;
        }

        #region Consultas

        public function InsertarUsuario(){
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO usuarios (correo,clave,nombre,apellido,perfil,foto) VALUES (:correo,:clave,:nombre,:apellido,:perfil,:foto)");
            $consulta->bindValue(':correo',     $this->correo,      PDO::PARAM_STR);
            $consulta->bindValue(':clave',      $this->clave,       PDO::PARAM_STR);
            $consulta->bindValue(':nombre',     $this->nombre,      PDO::PARAM_STR);
            $consulta->bindValue(':apellido',   $this->apellido,    PDO::PARAM_STR);
            $consulta->bindValue(':perfil',     $this->perfil,      PDO::PARAM_STR);
            $consulta->bindValue(':foto',       $this->foto);

            return $consulta->execute();
        }

        public function TraerTodos()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM usuarios');
            $consulta->execute(); 
            return $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");
        }

        public function TraerUno($consulta='SELECT * FROM usuarios')
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta($consulta);
            $consulta->execute();
            $retorno = new stdClass();
            $retorno->usuario = $consulta->fetchAll(PDO::FETCH_CLASS, "Usuario");
            $retorno->row = $consulta->rowCount(); 
            return $retorno;
        }

        #endregion

        #region Api

        public function CargarUsuario($request, $response, $args)
        {    
            $respuesta= new stdclass();
            $respuesta->exito = false;
            $respuesta->mensaje = "No se pudo cargar el usuario";
            $respuesta->status = 418;

            $arrayDatos = $request->getParsedBody();
            $usuarioAux = json_decode($arrayDatos["usuario"]);
            $file = $_FILES['foto'];
            
            $usuario = new Usuario();
            $path = Usuario::SubirFoto($file);
            $usuario->nombre=$usuarioAux->nombre;
            $usuario->apellido=$usuarioAux->apellido;
            $usuario->correo=$usuarioAux->correo;
            $usuario->clave=$usuarioAux->clave;
            $usuario->perfil=$usuarioAux->perfil;
            $usuario->foto=$path;
            
            if($usuario->nombre != '' && $usuario->apellido != '' && $usuario->correo != '' && $usuario->clave != '' && $usuario->perfil != '' && $usuario->foto != '')
            {
                try 
                {
                    if($usuario->InsertarUsuario())
                    {
                        $respuesta->exito = true;   
                        $respuesta->mensaje = "Se pudo cargar al usuario";   
                        $respuesta->status = 200;   
                    }  
                } 
                catch (Exception $e) {}
            }
            
            return $response->withJson($respuesta);
        }

        public function Lista($request, $response, $args)
        {
            $respuesta= new stdclass();
            $respuesta->exito = false;
            $respuesta->mensaje = "No se pudo cargar la lista";
            $respuesta->tabla = null;
            $respuesta->status = 424;

            try 
            {
                $lista = Usuario::TraerTodos();
            } 
            catch (Exception $e) {}
         
            if($lista != null)
            {
                $respuesta->exito = true;
                $respuesta->mensaje = "Se pudo cargar la lista";
                $respuesta->tabla = $lista;
                $respuesta->status = 200;
            }

            return  $response->withJson($respuesta);
        }

        public function Validar($request, $response, $args){
            $correo = $_POST['correo'];
            $respuesta = new stdClass();
            $respuesta->exito = false;

            try 
            {
                $dato = Usuario::TraerUno("SELECT * FROM usuarios WHERE (correo='$correo')");    
            } 
            catch (Exception $e) {}
        

            if($dato->row > 0)
            {
                $respuesta->exito = true;             
            }
           
            return $response->withJson($respuesta);
        }

        public function Login($request, $response, $args)
        {
            $respuesta = new stdClass();
            $respuesta->exito = false;
            $respuesta->jwt = null;
            $respuesta->status = 403;

            $arrayDatos = $request->getParsedBody();
            $usuario = $arrayDatos["user"];
            $correo = $usuario["correo"];
            $clave = $usuario["clave"];

            try 
            {
                $dato = Usuario::TraerUno("SELECT * FROM usuarios WHERE (correo='$correo' AND clave='$clave')");  
            } 
            catch (Exception $e) {}

            if($dato->row > 0)
            {
                $usuario = $dato->usuario;          
                $json = new stdClass();
                $json->correo = $usuario[0]->correo;
                $json->nombre = $usuario[0]->nombre;
                $json->apellido = $usuario[0]->apellido;
                $json->perfil = $usuario[0]->perfil;
                
                $jwt = AutentificadorJWT::CrearToken($json);
                $respuesta->jwt = $jwt;
                $respuesta->exito = true;
                $respuesta->status = 200;
            }
            
            return $response->withJson($respuesta);
        }

        public static function VerificarToken($request, $response, $args)
        {
            $arrayConToken = $request->getHeader('token');
            $token=$arrayConToken[0];	
            $respuesta = new stdClass();
            $respuesta->exito = false;
                    
            try 
            {
                AutentificadorJWT::VerificarToken($token);
                $respuesta->exito = true;
                $usuario = AutentificadorJWT::ObtenerData($token);
                $respuesta->perfil = $usuario->perfil;
                $respuesta->mensaje = "El token es valido";
            } 
            catch (Exception $e) 
            {
                $respuesta->mensaje = $e->getMessage();           
            }

            return $response->withJson($respuesta);
        }

        #endregion
    }
    