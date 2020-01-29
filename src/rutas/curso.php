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
            return getCurso($idCurso);
            $resultado=null;
            $db=null;
        });
        //GET CURSO BY ID_ALUMNO
        $app->get('/lista/{id_alumno}', function ($request, $response, $args) {
            $idCurso = $args['id_alumno'];
            return listaCurso($idCurso);
            $resultado=null;
            $db=null;
        });
        //POST ADD CURSO
        $app->post('/nuevo', function ($requlistaest, $response) {
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
        $app->post('/asigna', function ($request, $response) {
            $tipoUsr= $request->getParam('tusuario');
            $idCurso = $request->getParam('id_curso');
            $idAlumno = $request->getParam('id_alumno') ;
            
            return asignaCurso($idAlumno,$idCurso);
        
            $resultado=null;
            $db=null;
        });
    });
});
//Funciones
function asignaCurso($idAlumno,$idCurso){
    $curso = json_decode(getCurso($idCurso));
    $x = $curso[0]->{"id_curso_prerrequisito"};
    if(is_numeric($x) && $x>0){
        $pre = json_decode(getCurso($x));
        $asignado = getCursoEstudiante($idAlumno,$idCurso);
        if($asignado->rowCount()>0){
            $asignado = $asignado->fetchAll(PDO::FETCH_OBJ);
            if($asignado[0]->{"estado_curso"}==1){
                $sql = "INSERT INTO cursoestudiante(id_estudiante, id_curso) VALUES (:idAlumno, :idCurso);";
                try {
                    $db=new db();
                    $db=$db->conectDB();
                    //Preparar query
                    $resultado = $db->prepare($sql);
                    //Asignar Parametros
                    $resultado->bindParam(':idAlumno',$idAlumno);
                    $resultado->bindParam(':idCurso',$idCurso);
                    //Ejecutar query
                    $resultado->execute();
                    return json_encode("Curso Asignado");
                    
                } catch (PDOException $e) {
                    return '{"error":{"text":'.$e->getMessage().'}';
                }
            }else{
                return json_encode("Prerrequisito pendiente");
            }
            var_dump($asignado);die();
        }else{
            return json_encode("Falta cumplir prerequisito");
        }
        
    }
}
function getCursoEstudiante($idAlumno,$idCurso){
    $sql="SELECT * FROM cursoestudiante WHERE id_estudiante=$idAlumno AND id_curso=$idCurso;";
    try {
        $db=new db();
        $db=$db->conectDB();
        $resultado = $db->query($sql);
        return $resultado;
    } catch (PDOException $e) {
        return '{"error":{"text":'.$e->getMessage().'}';
    }
}
function updateCursoEstudiante($idCurso, $idAlumno, $estado){
    $sql="UPDATE `cursoestudiante` 
          SET 
            estado_curso = :estado
          WHERE 
            id_estudiante=$idAlumno AND id_curso=$idCurso";
    try {
        $db=new db();
        $db=$db->conectDB();
        //Preparar query
        $resultado = $db->prepare($sql);
        //Asignar Parametros
        $resultado->bindParam(':estado',$estado);
        //Ejecutar query
        $resultado->execute();
        return json_encode("Asignacion Actualizada");
        
    } catch (PDOException $e) {
        return '{"error":{"text":'.$e->getMessage().'}';
    }
}
function getCurso($idCurso){
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
}
function listaCurso($idAlumno){
    if(is_numeric($idAlumno)){
        $sql = "SELECT * FROM educdb.lista_curso WHERE id_estudiante is null or id_estudiante = $idAlumno;";
        try {
            $db=new db();
            $db=$db->conectDB();
            $resultado = $db->query($sql);
            if($resultado->rowCount()>0){
                $cursos = $resultado->fetchAll(PDO::FETCH_OBJ);
                return json_encode($cursos);
            }else{
                return json_encode("No existe Curso en la BD o Alumno Invalido");
            }
        } catch (PDOException $e) {
            return '{"error":{"text":'.$e->getMessage().'}';
        }
    }else{
        return json_encode("ID '$idAlumno' no es valido");
    }
    
}