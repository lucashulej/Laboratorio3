<?php
    require_once './clases/AccesoDatos.php';
    require_once './clases/AutentificadorJWT.php';

    class Barbijo
    {
        public $id;
        public $color;
        public $tipo;
        public $precio;

        #region Consultas

        public function InsertarBarbijo()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("INSERT INTO barbijos (color,tipo,precio) VALUES (:color,:tipo,:precio)");
            $consulta->bindValue(':color',  $this->color,   PDO::PARAM_STR);
            $consulta->bindValue(':tipo', $this->tipo,  PDO::PARAM_STR);
            $consulta->bindValue(':precio', $this->precio,  PDO::PARAM_INT);

            return $consulta->execute();
        }

        public function TraerTodos()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta('SELECT * FROM barbijos');
            $consulta->execute(); 
            return $consulta->fetchAll(PDO::FETCH_CLASS, "Barbijo");
        }

        public function TraerUno($consulta='SELECT * FROM barbijos') //agregado
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $consulta = $objetoAccesoDato->RetornarConsulta($consulta);
            $consulta->execute();
            $retorno = new stdClass();
            $retorno->barbijo = $consulta->fetchAll(PDO::FETCH_CLASS, "Barbijo");
            $retorno->row = $consulta->rowCount(); 
            return $retorno;
        }

        public function BorrarBarbijo()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
			$consulta =$objetoAccesoDato->RetornarConsulta("DELETE FROM barbijos WHERE id=:id");	
			$consulta->bindValue(':id',$this->id, PDO::PARAM_INT);		
			$consulta->execute();
			return $consulta->rowCount();
        }

        public function ModificarBarbijo()
        {
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE barbijos SET color=:color, tipo=:tipo, precio=:precio WHERE id=:id");
            $consulta->bindValue(':id',         $this->id,      PDO::PARAM_INT);		
            $consulta->bindValue(':color',      $this->color,   PDO::PARAM_STR);
            $consulta->bindValue(':tipo',      $this->tipo,   PDO::PARAM_STR);
            $consulta->bindValue(':precio',     $this->precio,  PDO::PARAM_INT);
            $consulta->execute();
            
            return $consulta->rowCount();
        } 
        
        #endregion

        #region Api

        public function CargarBarbijo($request, $response, $args)
        {    
            $respuesta= new stdclass();
            $respuesta->exito = false;
            $respuesta->mensaje = "No se pudo cargar el barbijo";
            $respuesta->status = 418;

            $arrayDatos = $request->getParsedBody();
            $barbijoAux = json_decode($arrayDatos["barbijo"]);
        
            $barbijo = new Barbijo();
            $barbijo->color=$barbijoAux->color;
            $barbijo->tipo=$barbijoAux->tipo;
            $barbijo->precio=$barbijoAux->precio;

            if($barbijo->color != ' '&& $barbijo->tipo != '' && $barbijo->precio != '')
            {
                if($barbijo->tipo == 'liso' || $barbijo->tipo == 'estampado' || $barbijo->tipo == 'transparente')
                {
                    try 
                    {
                        if($barbijo->InsertarBarbijo())
                        {
                            $respuesta->exito = true;   
                            $respuesta->mensaje = "Se pudo cargar el barbijo";   
                            $respuesta->status = 200;   
                        }  
                    } 
                    catch (Exception $e) {}
                }
            }
            
            return $response->withJson($respuesta);
        }

        public function ObtenerData($request, $response, $args)
        {
            $respuesta = new stdClass();
            $respuesta->exito = false;
            $respuesta->barbijo = null;

            $id = $request->getHeader('id');
     
            $respuesta->id=$id;
            
            try 
            {
                $dato = Barbijo::TraerUno("SELECT * FROM barbijos WHERE (id='$id[0]')");    
            } 
            catch (Exception $e) {}
        
            if($dato->row > 0)
            {
                $respuesta->barbijo = $dato->barbijo;             
            }
            
            return $response->withJson($respuesta);
        }
        
        public function Lista($request, $response, $args)
        {
            $respuesta = new stdclass();
            $respuesta->exito = false;
            $respuesta->mensaje = "No se pudo cargar la lista";
            $respuesta->tabla = null;
            $respuesta->status = 424;
        
            try 
            {
                $lista = Barbijo::TraerTodos();
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
        
        public function BorrarUno($request, $response, $args) 
        {
            $respuesta= new stdclass();
            $respuesta->exito = false;
            $respuesta->mensaje = "No se pudo eliminar el barbijo";
            $respuesta->status = 418;
            
            $arrayDatos = $request->getParsedBody();
            $id = $arrayDatos["id_barbijo"];
            $jwt = $request->getHeader('token');
            $datosUsuario = AutentificadorJWT::ObtenerData($jwt[0]);
            
          
                $barbijo= new Barbijo();
                $barbijo->id=$id;

                try 
                {
                    $rowCount=$barbijo->BorrarBarbijo();
                } 
                catch (Exception $e) {}

                if($rowCount > 0)
                {
                    $respuesta->exito = true;
                    $respuesta->mensaje = "Se pudo eliminar el barbijo";
                    $respuesta->status = 200;
                }
          
            
            return $response->withJson($respuesta);
        }
    

        public function ModificarUno($request, $response, $args) 
        {
            $respuesta= new stdclass();
            $respuesta->exito = false;
            $respuesta->mensaje = "No se pudo modificar el barbijo";
            $respuesta->status = 418;

            $arrayDatos = $request->getParsedBody();
            $barbijoAux = json_decode($arrayDatos["barbijo"]);
            
            $jwt = $request->getHeader('jwt');
            $datosUsuario = AutentificadorJWT::ObtenerData($jwt[0]);
      
            if($barbijoAux->id != '' && $barbijoAux->color != ''&& $barbijoAux->tipo != '' && $barbijoAux->precio != '')
            {
                if($barbijoAux->tipo == 'liso' || $barbijoAux->tipo == 'estampado' || $barbijoAux->tipo == 'transparente')
                {
                    $barbijo = new Barbijo();
                    $barbijo->id=$barbijoAux->id;
                    $barbijo->color=$barbijoAux->color;
                    $barbijo->tipo=$barbijoAux->tipo;
                    $barbijo->precio=$barbijoAux->precio;
    
                    try 
                    {
                        $rowCount=$barbijo->ModificarBarbijo();
                    } 
                    catch (Exception $e) {}
    
                    if($rowCount > 0)
                    {
                        $respuesta->exito = true;
                        $respuesta->mensaje = "Se pudo modificar el barbijo";
                        $respuesta->status = 200;
                    }
                }
            }
            
            return $response->withJson($respuesta);
        }

        #endregion
    }