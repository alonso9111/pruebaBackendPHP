<?php
/****************
**CRUD PREGUNTA**
*****************/
/*
    TIPO PREGUNTA
        1 - Booleana
        2 - Opción múltiple donde solo una respuesta es correcta
        3 - Opción múltiple donde más de una respuesta es correcta
        4 - Opción múltiple donde más de una respuesta es correcta y 
            todas deben responderse correctamente
*/
$app->group('/api', function () use ($app) {
    $app->group('/preguntas', function () use ($app) {
        // GET ALL PREGUNTAS
        $app->get('', function ($request, $response) {
            $sql="SELECT * FROM pregunta";
            try {
                $db=new db();
                $db=$db->conectDB();
                $resultado = $db->query($sql);
                if($resultado->rowCount()>0){
                    $preguntas = $resultado->fetchAll(PDO::FETCH_OBJ);
                    return json_encode($preguntas);
                }else{
                    return json_encode("No se encuentran preguntas creadas");
                }
            } catch (PDOException $e) {
                return '{"error":{"text":'.$e->getMessage().'}';
            }
            $resultado=null;
            $db=null;
        });
        //GET PREGUNTA BY ID
        $app->get('/{id}', function ($request, $response, $args) {
            $idPregunta = $args['id'];
            if(is_numeric($idPregunta)){
                $sql="SELECT * FROM pregunta WHERE id=$idPregunta";
                try {
                    $db=new db();
                    $db=$db->conectDB();
                    $resultado = $db->query($sql);
                    if($resultado->rowCount()>0){
                        $preguntas = $resultado->fetchAll(PDO::FETCH_OBJ);
                        return json_encode($preguntas);
                    }else{
                        return json_encode("No existe Pregunta con ID $idPregunta");
                    }
                } catch (PDOException $e) {
                    return '{"error":{"text":'.$e->getMessage().'}';
                }
            }else{
                return json_encode("ID '$idPregunta' no es valido");
            }
            $resultado=null;
            $db=null;
        });
        //POST ADD PREGUNTA
        $app->post('/nuevo', function ($request, $response) {

            $tipoUsr= $request->getParam('tusuario');
            $idLeccion = $request->getParam('id_leccion');
            $idTipoPreg = $request->getParam('tipo') ;
            $pregunta = $request->getParam('pregunta') ;
            $respuesta = $request->getParam('respuesta') ;
            $puntaje = $request->getParam('puntaje') ;

            if((is_numeric($tipoUsr)) && ($tipoUsr==1)){
                $sql="INSERT INTO pregunta(id_leccion, id_tipo, pregunta, respuesta, puntaje) 
                      VALUES (
                          :idLeccion, 
                          :idTipoPreg, 
                          :pregunta, 
                          :respuesta, 
                          :puntaje
                      );";
                try {
                    $db=new db();
                    $db=$db->conectDB();
                    //Preparar query
                    $resultado = $db->prepare($sql);
                    //Asignar Parametros
                    $resultado->bindParam(':idLeccion',$idLeccion);
                    $resultado->bindParam(':idTipoPreg',$idTipoPreg);
                    $resultado->bindParam(':pregunta',$pregunta);
                    $resultado->bindParam(':respuesta',$respuesta);
                    $resultado->bindParam(':puntaje',$puntaje);
                    //Ejecutar query
                    $resultado->execute();
                    return json_encode("Pregunta Agregada");
                    
                } catch (PDOException $e) {
                    return '{"error":{"text":'.$e->getMessage().'}';
                }
            }else{
                return json_encode("Tipo de usuario incorrecto o no valido");
            }
        
            $resultado=null;
            $db=null;
        });
        //PUT EDIT PREGUNTA
        $app->put('/editar/{id}', function ($request, $response, $args) {
            $idPregunta = $args['id'];
            $data = $request->getParsedBody();
            if((is_numeric($data['tusuario'])) && ($data['tusuario']==1)){
                if(is_numeric($idPregunta)){
                    $sql="UPDATE pregunta SET 
                            id_leccion=:idPregunta,
                            id_tipo=:idTipoPreg,
                            pregunta=:pregunta,
                            respuesta=:respuesta,
                            puntaje=:puntaje
                        WHERE id = $idPregunta;";
                    try {
                        $db=new db();
                        $db=$db->conectDB();
                        //Preparar query
                        $resultado = $db->prepare($sql);
                        //Asignar Parametros
                        $resultado->bindParam(':idLeccion',$data['id_leccion']);
                        $resultado->bindParam(':idTipoPreg',$data['tipo']);
                        $resultado->bindParam(':pregunta',$data['pregunta']);
                        $resultado->bindParam(':respuesta',$data['respuesta']);
                        $resultado->bindParam(':puntaje',$data['puntaje']);
                        //Ejecutar query
                        $resultado->execute();
                        return json_encode("Pregunta Modificada");
                        
                    } catch (PDOException $e) {
                        return '{"error":{"text":'.$e->getMessage().'}';
                    }
                }else{
                    return json_encode("ID '$idPregunta' no es valido");
                }
            }else{
                return json_encode("Tipo de usuario incorrecto o no valido");
            }
        
            $resultado=null;
            $db=null;
        });
        //DELETE PREGUNTA
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