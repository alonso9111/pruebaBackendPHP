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
        $app->get('/{tipo}', function ($request, $response,$args) {
            $tipoUsr = $args['tipo'];
            if((is_numeric($tipoUsr))&&($tipoUsr==1)){
                $sql="SELECT * FROM pregunta";
            }else if((is_numeric($tipoUsr))&&($tipoUsr==2)){
                $sql="SELECT 
                        id,
                        id_leccion,
                        id_tipo,
                        pregunta,
                        puntaje FROM pregunta;";
            }else{
                $sql="";
            }

            if($sql!=""){
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
            }else{
                return json_encode("Tipo de usuario incorrecto o no valido"); 
            }
            
            $resultado=null;
            $db=null;
        });
        //GET PREGUNTA BY ID
        $app->get('/{tipo}/{id}', function ($request, $response, $args) {
            $tipoUsr = $args['tipo'];
            $idPregunta = $args['id'];
            return getPregunta($tipoUsr,$idPregunta);
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
                            id_leccion= :idLeccion,
                            id_tipo= :idTipoPreg,
                            pregunta= :pregunta,
                            respuesta= :respuesta,
                            puntaje= :puntaje
                          WHERE id=$idPregunta;";
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
            $idPregunta = $args['id'];
            if(is_numeric($tipoUsr) && ($tipoUsr=1)){
                if(is_numeric($idPregunta)){
                    $sql="DELETE FROM pregunta WHERE id = $idPregunta;";
                    try {
                        $db=new db();
                        $db=$db->conectDB();
                        //Preparar query
                        $resultado = $db->prepare($sql);
                        //Ejecutar query
                        $resultado->execute();
                        //Validacion de Ejecución
                        if($resultado->rowCount() >0){
                            return json_encode("Pregunta Eliminada");
                        }else{
                            return json_encode("No existe Pregunta con ID $idPregunta");
                        }
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
        $app->post('/evaluar',function($request, $response){
            //CAPTURA DATOS
            $idAlumno = $request->getParam('id_alumno');
            $idLeccion = $request->getParam('id_leccion');
            $leccion = json_decode(getLeccion($idLeccion));
            $preguntas = $request->getParam('preguntas');
            $puntaje=0;
            $estado = false;
            //CALCULO
            foreach($preguntas as $examen){
                $pregunta=json_decode(getPregunta($examen["id_pregunta"],1));
                $x=calificaPregunta($pregunta[0]->{"id_tipo"},$pregunta[0]->{"respuesta"}, $examen["respuesta"]);
                if($x==1){
                    $puntaje +=$pregunta[0]->{"puntaje"};
                }
            }
            if($puntaje>=$leccion[0]->{"puntaje_aprobacion"}){
                $estado = true;                
            }
            //REGISTRO
            $registro = getEvaluacion($idLeccion,$idAlumno);
            if($registro->rowCount()>0){
                $registro=$registro->fetchAll(PDO::FETCH_OBJ);                
                $sql="UPDATE evaluacion SET 
                        id_estudiante=:idAlumno,
                        id_leccion=:idLeccion,
                        puntaje_evaluacion=:puntaje,
                        estado=:estado 
                      WHERE id=".$registro[0]->{"id"};
            }else{
                $sql="INSERT INTO evaluacion(id_estudiante, id_leccion, puntaje_evaluacion, estado) 
                      VALUES (
                          :idAlumno,
                          :idLeccion,
                          :puntaje,
                          :estado
                      );";
            }

            try {
                $db=new db();
                $db=$db->conectDB();
                //Preparar query
                $resultado = $db->prepare($sql);
                //Asignar Parametros
                $resultado->bindParam(':idAlumno',$idAlumno);
                $resultado->bindParam(':idLeccion',$idLeccion);
                $resultado->bindParam(':puntaje',$puntaje);
                $resultado->bindParam(':estado',$estado);
                //Ejecutar query
                $resultado->execute();
                $x=validaCurso($idAlumno,$leccion[0]->{"id_curso"});
                return json_encode("Evaluacion Actualizada");
            } catch (PDOException $e) {
                return '{"error":{"text":'.$e->getMessage().'}';
            }
        });
    });
});
//Funciones
function getPregunta($idPregunta,$tipoUsr){
    if((is_numeric($tipoUsr))&&($tipoUsr==1)&&(is_numeric($idPregunta))){
        $sql="SELECT * FROM pregunta WHERE id=$idPregunta";
    }else if((is_numeric($tipoUsr))&&($tipoUsr==2)&&(is_numeric($idPregunta))){
        $sql="SELECT 
                id,
                id_leccion,
                id_tipo,
                pregunta,
                puntaje FROM pregunta
               WHERE id=$idPregunta;";
    }else{
        $sql="";
    }
    if($sql!=""){
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
        if(is_numeric($idPregunta)){
            return json_encode("Tipo de usuario incorrecto o no valido"); 
        }else{
            return json_encode("ID '$idPregunta' no es valido");
        }
    }
    $resultado=null;
    $db=null;
}
function calificaPregunta($idTipo,$respuesta,$respuestaAl){
    if(($idTipo==1)||($idTipo==2)||($idTipo==4)){
        if($respuestaAl==$respuesta){
            return 1;
        }else{
            return 0;
        }
    }else if($idTipo==3){
        if(strstr($respuesta, $respuestaAl)){
            return 1;
        }else{
            return 0;
        }
    }else{
        return -1;
    }
}
function getEvaluacion($idLeccion,$idAlumno){
    $sql="SELECT * FROM evaluacion WHERE id_estudiante=$idAlumno AND id_leccion=$idLeccion;";
    try {
        $db=new db();
        $db=$db->conectDB();
        $resultado = $db->query($sql);
        return $resultado ;
    } catch (PDOException $e) {
        return '{"error":{"text":'.$e->getMessage().'}';
    }
    $resultado=null;
    $db=null;
}
function validaCurso($idAlumno,$idCurso){
    $sql="SELECT * FROM valida_curso WHERE id_estudiante=$idAlumno AND id_curso=$idCurso;";
    $estado = false;
    try {
        $db=new db();
        $db=$db->conectDB();
        $resultado = $db->query($sql);
        if($resultado->rowCount()>0){
            $valida = $resultado->fetchAll(PDO::FETCH_OBJ);
            if($valida[0]->{"aprobados"}>=$valida[0]->{"lecciones"}){
                $estado = true;
            }
            return updateCursoEstudiante($idCurso,$idAlumno,$estado);
        }
    } catch (PDOException $e) {
        return '{"error":{"text":'.$e->getMessage().'}';
    }
}