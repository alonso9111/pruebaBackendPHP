<?php
/*************
**CRUD CURSO**
*************/
$app->group('/api', function () use ($app) {
    $app->group('/cursos', function () use ($app) {
        // GET ALL CURSOS
        $app->get('', function ($request, $response) {
            $sql="SELECT * FROM curso";
            try {
                $db=new db();
                $db=$db->conectDB();
                $resultado = $db->query($sql);
                if($resultado->rowCount()>0){
                    $cursos = $resultado->fetchAll(PDO::FETCH_OBJ);
                    return json_encode($cursos);
                }else{
                    return json_encode("No se encuentran cursos creados");
                }
            } catch (PDOException $e) {
                return '{"error":{"text":'.$e->getMessage().'}';
            }
            $resultado=null;
            $db=null;
        });
        //GET CURSO BY ID
        $app->get('/{id}', function ($request, $response, $args) {
            $idCurso = $args['id'];
            if(is_numeric($idCurso)){
                $sql="SELECT * FROM curso WHERE id=$idCurso";
                try {
                    $db=new db();
                    $db=$db->conectDB();
                    $resultado = $db->query($sql);
                    if($resultado->rowCount()>0){
                        $cursos = $resultado->fetchAll(PDO::FETCH_OBJ);
                        return json_encode($cursos);
                    }else{
                        return json_encode("No existe Curso con ID $idCurso");
                    }
                } catch (PDOException $e) {
                    return '{"error":{"text":'.$e->getMessage().'}';
                }
            }else{
                return json_encode("ID '$idCurso' no es valido");
            }
            $resultado=null;
            $db=null;
        });
        //POST ADD CURSO
        $app->post('/nuevo', function ($request, $response) {
            $tipoUsr= $request->getParam('tusuario');
            $idCursoPre = $request->getParam('prerrequisito');
            $nombre = $request->getParam('nombre') ;
            if((is_numeric($tipoUsr)) && ($tipoUsr==1)){
                $sql="INSERT INTO curso(id_curso_prerrequisito, nombre) VALUES (:idCursoPre, :nombre);";
                try {
                    $db=new db();
                    $db=$db->conectDB();
                    //Preparar query
                    $resultado = $db->prepare($sql);
                    //Asignar Parametros
                    $resultado->bindParam(':nombre',$nombre);
                    $resultado->bindParam(':idCursoPre',$idCursoPre);
                    //Ejecutar query
                    $resultado->execute();
                    return json_encode("Curso Agregado");
                    
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
            $idCurso = $args['id'];
            $data = $request->getParsedBody();
            if((is_numeric($data['tusuario'])) && ($data['tusuario']==1)){
                if(is_numeric($idCurso)){
                    $sql="UPDATE curso SET 
                            id_curso_prerrequisito=:idCursoPre,
                            nombre=:nombre 
                        WHERE id = $idCurso;";
                    try {
                        $db=new db();
                        $db=$db->conectDB();
                        //Preparar query
                        $resultado = $db->prepare($sql);
                        //Asignar Parametros
                        $resultado->bindParam(':nombre',$data['nombre']);
                        $resultado->bindParam(':idCursoPre',$data['prerrequisito']);
                        //Ejecutar query
                        $resultado->execute();
                        return json_encode("Curso Modificado");
                        
                    } catch (PDOException $e) {
                        return '{"error":{"text":'.$e->getMessage().'}';
                    }
                }else{
                    return json_encode("ID '$idCurso' no es valido");
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
            $idCurso = $args['id'];
            $data = $request->getParsedBody();
            if(is_numeric($tipoUsr) && ($tipoUsr=1)){
                if(is_numeric($idCurso)){
                    $sql="DELETE FROM curso WHERE id = $idCurso;";
                    try {
                        $db=new db();
                        $db=$db->conectDB();
                        //Preparar query
                        $resultado = $db->prepare($sql);
                        //Ejecutar query
                        $resultado->execute();
                        //Validacion de Ejecución
                        if($resultado->rowCount() >0){
                            return json_encode("Curso Eliminado");
                        }else{
                            return json_encode("No existe Curso con ID $idCurso");
                        }
                    } catch (PDOException $e) {
                        return '{"error":{"text":'.$e->getMessage().'}';
                    }
                }else{
                    return json_encode("ID '$idCurso' no es valido");
                }
            }else{
                return json_encode("Tipo de usuario incorrecto o no valido");
            }
            $resultado=null;
            $db=null;
        });
    });
});