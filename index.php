<?php

require 'flight/Flight.php';
/**
 * Conexión con la base de datos
 */
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=api', 'root', ''));
/**Traer alimnos */
Flight::route('GET /alumnos', function () {
    /**Traer alimnos */
    $db = Flight::db();
    $sql = "SELECT * FROM alumnos";
    $stmt = $db->prepare($sql); //Preparar la consulta
    $stmt->execute(); //Ejecutar la consulta
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC); //Traer los datos
    Flight::json($alumnos); //Retornar los datos en formato JSON 
});
/**Crear alumno */
Flight::route('POST /alumnos', function () {
    $db = Flight::db();
    $request = Flight::request();
    $data = $request->data->getData();
    $sql = "INSERT INTO alumnos (nombres, apellidos, edad) VALUES (:nombres, :apellidos, :edad)";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':nombres', $data['nombres']);
    $stmt->bindParam(':apellidos', $data['apellidos']);
    $stmt->bindParam(':edad', $data['edad']);
    $stmt->execute();
    $id = $db->lastInsertId();
    Flight::jsonp(["Alumno creado con el id: (" => $id . ")"]);
});
/**Actualizar alumno */
Flight::route('PUT /alumnos', function () {
    $db = Flight::db();
    $request = Flight::request();
    $data = $request->data->getData();
    $sql = "UPDATE alumnos SET nombres = :nombres, apellidos = :apellidos, edad = :edad WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':nombres', $data['nombres']);
    $stmt->bindParam(':apellidos', $data['apellidos']);
    $stmt->bindParam(':edad', $data['edad']);
    $stmt->bindParam(':id', $data['id']);
    $stmt->execute();
    Flight::jsonp(["El alumno con el id: (" . $data['id'] . ") fue actualizado"]);
});
/**Eliminar alumno */
Flight::route('DELETE /alumnos', function () {
    $db = Flight::db();
    $request = Flight::request();
    $data = $request->data->getData();
    $sql = "DELETE FROM alumnos WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $data['id']);
    $stmt->execute();
    Flight::jsonp(["El alumno con el id: (" . $data['id'] . ") fue eliminado"]);
});
/**Mostrar alumno */
Flight::route('GET /alumnos/@id', function ($id) {
    $db = Flight::db();
    $sql = "SELECT * FROM alumnos WHERE id = :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
    Flight::json($alumno);
});

Flight::start();
