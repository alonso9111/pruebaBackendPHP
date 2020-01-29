<?php
/*****************
**CRUD LECCIONES**
*****************/
$app->group('/api', function () use ($app) {
    $app->group('/lecciones', function () use ($app) {
    // GET ALL LECCIONES
        $app->get('', function ($request, $response) {
            $sql="SELECT * FROM leccion";
            try {
                $db=new db();
                $db=$db->conectDB();
                $resultado = $db->query($sql);
                if($resultado->rowCount()>0){
                    $lecciones = $resultado->fetchAll(PDO::FETCH_OBJ);
                    return json_encode($lecciones);
                }else{
                    return json_encode("No se encuentran lecciones creados");
                }
            } catch (PDOException $e) {
                return '{"error":{"text":'.$e->getMessage().'}';
            }
            $resultado=null;
            $db=null;
        });
        //GET LECCION BY ID
        $app->get('/{id}', function ($request, $response, $args) {
            $idLeccion = $args['id'];
            return getLeccion($idLeccion);
        });
        //GET LECCION BY ID ALUMNO
        $app->get('/lista/{id_alumno}', function ($request, $response, $args) {
            $idLeccion = $args['id_alumno'];
            return listaLecciones($idLeccion);
        });
        //POST ADD LECCION
        $app->post('/nuevo', function ($request, $response) {
            $tipoUsr= $request->getParam('tusuario');
            $idCurso = $request->getParam('id_curso');
            $idLeccionPre = $request->getParam('prerrequisito');
            $nombre = $request->getParam('nombre') ;
            $puntaje = $request->getParam('puntaje') ;
            if((is_numeric($tipoUsr)) && ($tipoUsr==1)){
                $sql="INSERT INTO leccion(id_curso, id_leccion_prerrequisito, nombre, puntaje_aprobacion) 
                    VALUES (  :idCurso, 
                                :idLeccionPre, 
                                :nombre, 
                                :puntaje);";
                try {
                    $db=new db();
                    $db=$db->conectDB();
                    //Preparar query
                    $resultado = $db->prepare($sql);
                    //Asignar Parametros
                    $resultado->bindParam(':idCurso',$idCurso);
                    $resultado->bindParam(':idLeccionPre',$idLeccionPre);
                    $resultado->bindParam(':nombre',$nombre);
                    $resultado->bindParam(':puntaje',$puntaje);
                    //Ejecutar query
                    $resultado->execute();
                    return json_encode("Leccion Agregada");
                    
                } catch (PDOException $e) {
                    return '{"error":{"text":'.$e->getMessage().'}';
                }
            }else{
                return json_encode("Tipo de usuario incorrecto o no valido");
            }
        
            $resultado=null;
            $db=null;
        });
        //PUT EDIT CURSO
        $app->put('/editar/{id}', function ($request, $response, $args) {
            $idLeccion = $args['id'];
            $data = $request->getParsedBody();
            if((is_numeric($data['tusuario'])) && ($data['tusuario']==1)){
                if(is_numeric($idLeccion)){
                    $sql="UPDATE leccion SET 
                            id_curso= :idCurso,
                            id_leccion_prerrequisito= :idLeccionPre,
                            nombre= :nombre,
                            puntaje_aprobacion= :puntaje
                        WHERE id = $idLeccion;";
                    try {
                        $db=new db();
                        $db=$db->conectDB();
                        //Preparar query
                        $resultado = $db->prepare($sql);
                        //Asignar Parametros
                        $resultado->bindParam(':idCurso',$data['id_curso']);
                        $resultado->bindParam(':idLeccionPre',$data['prerrequisito']);
                        $resultado->bindParam(':nombre',$data['nombre']);
                        $resultado->bindParam(':puntaje',$data['puntaje']);
                        //Ejecutar query
                        $resultado->execute();
                        return json_encode("Leccion Modificada");
                        
                    } catch (PDOException $e) {
                        return '{"error":{"text":'.$e->getMessage().'}';
                    }
                }else{
                    return json_encode("ID '$idLeccion' no es valido");
                }
            }else{
                return json_encode("Tipo de usuario incorrecto o no valido");
            }
        
            $resultado=null;
            $db=null;
        });
        //DELETE CURSO
        $app->delete('/eliminar/{tipo}/{id}', function ($request, $response, $args) {
            $tipoUsr = $args['tipo'];
            $idLeccion = $args['id'];
            if(is_numeric($tipoUsr) && ($tipoUsr=1)){
                if(is_numeric($idLeccion)){
                    $sql="DELETE FROM leccion WHERE id = $idLeccion;";
                    try {
                        $db=new db();
                        $db=$db->conectDB();
                        //Preparar query
                        $resultado = $db->prepare($sql);
                        //Ejecutar query
                        $resultado->execute();
                        //Validacion de Ejecución
                        if($resultado->rowCount() >0){
                            return json_encode("Leccion Eliminada");
                        }else{
                            return json_encode("No existe leccion con ID $idLeccion");
                        }
                    } catch (PDOException $e) {
                        return '{"error":{"text":'.$e->getMessage().'}';
                    }
                }else{
                    return json_encode("ID '$idLeccion' no es valido");
                }
            }else{
                return json_encode("Tipo de usuario incorrecto o no valido");
            }
            $resultado=null;
            $db=null;
        });
    });
});
function getLeccion($idLeccion){
    if(is_numeric($idLeccion)){
        $sql="SELECT * FROM leccion WHERE id=$idLeccion";
        try {
            $db=new db();
            $db=$db->conectDB();
            $resultado = $db->query($sql);
            if($resultado->rowCount()>0){
                $lecciones = $resultado->fetchAll(PDO::FETCH_OBJ);
                return json_encode($lecciones);
            }else{
                return json_encode("No existe Leccion con ID $idLeccion");
            }
        } catch (PDOException $e) {
            return '{"error":{"text":'.$e->getMessage().'}';
        }
    }else{
        return json_encode("ID '$idLeccion' no es valido");
    }
    $resultado=null;
    $db=null;
}
function listaLecciones($idAlumno){
    if(is_numeric($idAlumno)){
        $sql = "SELECT * FROM lista_leccion WHERE id_estudiante is null or id_estudiante = $idAlumno;";
        try {
            $db=new db();
            $db=$db->conectDB();
            $resultado = $db->query($sql);
            if($resultado->rowCount()>0){
                $lecciones = $resultado->fetchAll(PDO::FETCH_OBJ);
                return json_encode($lecciones);
            }else{
                return json_encode("No existe Lecciones en la BD o Alumno Invalido");
            }
        } catch (PDOException $e) {
            return '{"error":{"text":'.$e->getMessage().'}';
        }
    }else{
        return json_encode("ID '$idAlumno' no es valido");
    }
    
}