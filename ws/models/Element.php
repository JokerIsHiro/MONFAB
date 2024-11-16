<?php

require_once "../ws/interfaces/ItoJson.php";
require_once "../ws/funciones.php";

class Element implements ItoJson
{
    public $fullname;
    public $desc;
    public $serial_number;
    public $status;
    public $priority;

    public function __construct($fullname = null, $desc = null, $serial_number = null, $status = null, $priority = null)
    {
        $this->fullname = $fullname;
        $this->desc = $desc;
        $this->serial_number = $serial_number;
        $this->status = $status;
        $this->priority = $priority;
    }

    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }

    public function setDesc($desc)
    {
        $this->desc = $desc;
    }

    public function setSerial_number($serial_number)
    {
        $this->serial_number = $serial_number;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function getFullName()
    {
        return $this->fullname;
    }
    public function getDesc()
    {
        return $this->desc;
    }
    public function getSerial_number()
    {
        return $this->serial_number;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getPriority()
    {
        return $this->priority;
    }

    public function toJson()
    {


        $file = file_get_contents("datos.txt");

        $array = unserialize($file);

        $output = array(
            'content' => $array
        );

        print_r(json_decode(json_encode($output, JSON_PRETTY_PRINT)));
    }

    public function get($dbcon)
    {
        try {
            if (isset($_GET['id'])) {
                $stmt = $dbcon->prepare("SELECT * FROM elementos WHERE id = :id");
                $stmt->bindParam(":id", $_GET['id']);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $response = createResponse(true, 'Elemento encontrado', $result);
                } else {
                    $response = createResponse(false, 'Elemento no encontrado');
                }
            } else {
                $stmt = $dbcon->query("SELECT * FROM elementos");

                $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $response = createResponse(true, 'Elemento especificado no encontrado, devolviendo todos los elementos', $results);
            }
        } catch (PDOException $e) {
            $response = createResponse(false, 'Error al intentar ejecutar ' . $e->getMessage());
        }
        return json_encode($response);
    }

    public function insert($dbcon)
    {
        try {

            $errors = [];

            if (isset($_POST['nombre']) || !is_string($_POST['nombre']) || trim($_POST['nombre']) === '') {
                $errors[] = 'El nombre es obligatorio y ha de ser un string';
            }
            if (isset($_POST['descripcion']) || !is_string($_POST['descripcion'])) {
                $errors[] = 'La descripcion es obligatoria y ha de ser un string';
            }
            if (isset($_POST['nserie']) || !filter_Var($_POST['nserie'], FILTER_VALIDATE_INT)) { //Comprobar que el numero de serie es un int
                $errors[] = 'El nserie es obligatorio y ha de ser un string';
            }
            if (isset($_POST['estado']) || !in_array($_POST['estado'], ['activo', 'inactivo'], true)) { //Comprobar que el estado es activo o inactivo
                $errors[] = 'El estado es obligatorio y ha de ser activo o inactivo';
            }
            if (isset($_POST['prioridad']) || !in_array($_POST['prioridad'], ['alta', 'media', 'baja'], true)) { //Comprobar que la prioridad es alta, media o baja
                $errors[] = 'La prioridad es obligatoria y ha de ser alta, media o baja';
            }

            if (!empty($errors)) {
                return json_encode(createResponse(false, 'Error de validación', $errors));
            }

            $stmt = $dbcon->prepare("INSERT INTO elementos (nombre, descripcion, nserie, estado, prioridad) VALUES (:fullname, :desc, :serial_number, :status, :priority)");
            $stmt->bindParam(":fullname", $_POST['nombre']);
            $stmt->bindParam(":desc", $_POST['descripcion']);
            $stmt->bindParam(":serial_number", $_POST['nserie']);
            $stmt->bindParam(":status", $_POST['estado']);
            $stmt->bindParam(":priority", $_POST['prioridad']);
            $stmt->execute();

            $stmt->execute();

            $response = createResponse(true, 'Elemento creado', [
                'id' => $dbcon->lastInsertId(),
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'nserie' => $_POST['nserie'],
                'estado' => $_POST['estado'],
                'prioridad' => $_POST['prioridad']
            ]);
        } catch (PDOException $e) {
            $response = createResponse(false, 'Error al intentar crear el elemento ' . $e->getMessage());
        }
        return json_encode($response);
    }

    public function delete($dbcon)
    {
        try {
            if (isset($_GET['id'])) {
                $stmt = $dbcon->prepare("SELECT * FROM elementos WHERE id = :id");
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {
                    $deleteStmt = $dbcon->prepare("DELETE FROM elementos WHERE id = :id");
                    $deleteStmt->bindParam(':id', $_GET['id']);
                    $deleteStmt->execute();

                    if ($deleteStmt->rowCount() > 0) {
                        $response = createResponse(true, 'Elemento eliminado', $result);
                    } else {
                        $response = createResponse(false, 'Error al intentar eliminar el elemento');
                    }
                } else {
                    $response = createResponse(false, 'Elemento con el ID ' . $_GET['id'] . ' no ha sido encontrado');
                }
            } else {
                $response = createResponse(false, 'Falta el parámetro ID');
            }
        } catch (PDOException $e) {
            $response = createResponse(false, 'Error al intentar eliminar el elemento ' . $e->getMessage());
        }
        return json_encode($response);
    }

    public function update($dbcon)
    {
        try {

            if (isset($_GET['id']) && !empty($_GET['id'])) {

                $id = intval($_GET['id']);

                $stmt = $dbcon->prepare("SELECT * FROM elementos WHERE id = :id"); //Comprobar que el elemento existe
                $stmt->bindParam(":id", $id, PDO::PARAM_INT);
                $stmt->execute();

                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {

                    $updates = []; //Array para almacenar los valores que se van a actualizar
                    $params = [':id' => $id]; //Parametros para el update

                    if (isset($_POST['nombre']) && is_string($_POST['nombre'])) { //Comprobar que es un string el dato que se ha introducido para modificar
                        $updates[] = "nombre = :nombre";
                        $params[':nombre'] = $_POST['nombre'];
                    }

                    if (isset($_POST['descripcion']) && is_string($_POST['descripcion'])) { //Comprobar que es un string el dato que se ha introducido para modificar
                        $updates[] = "descripcion = :descripcion";
                        $params[':descripcion'] = $_POST['descripcion'];
                    }

                    if (isset($_POST['nserie']) && filter_Var($_POST['nserie'], FILTER_VALIDATE_INT)) { //Comprobar que es un int el dato que se ha introducido para modificar
                        $updates[] = "nserie = :nserie";
                        $params[':nserie'] = $_POST['nserie'];
                    }

                    if (isset($_POST['estado']) && is_string($_POST['estado']) && in_array($_POST['estado'], ['activo', 'inactivo'], true)) { //Comprobar que es un string y que es activo o inactivo el dato que se ha introducido para modificar
                        $updates[] = "estado = :estado";
                        $params[':estado'] = $_POST['estado'];
                    }

                    if (isset($_POST['prioridad']) && is_string($_POST['prioridad']) && in_array($_POST['prioridad'], ['alta', 'media', 'baja'], true)) { //Comprobar que es un string y que es alta, media o baja el dato que se ha introducido para modificar
                        $updates[] = "prioridad = :prioridad";
                        $params[':prioridad'] = $_POST['prioridad'];
                    }

                    if (!empty($updates)) {
                        $sql = "UPDATE elementos SET " . implode(", ", $updates) . " WHERE id = :id"; // Actualizar solo los campos que se han enviado
                        $stmt = $dbcon->prepare($sql);
                        $stmt->execute($params);

                        if ($stmt->rowCount() > 0) {
                            $response = createResponse(true, 'Elemento modificado', [
                                'id' => $id,
                                'cambios' => $params //Devolver parámetros modificados
                            ]);
                        } else {
                            $response = createResponse(false, 'Error al intentar modificar el elemento, ningun campo ha sido modificado');
                        }
                    } else {
                        $response = createResponse(false, 'Error al intentar modificar el elemento');
                    }
                } else {
                    $response = createResponse(false, 'Elemento con el ID ' . $_GET['id'] . ' no ha sido encontrado');
                }
            } else {
                $response = createResponse(false, 'Falta el parámetro ID');
            }
        } catch (PDOException $e) {
            $response = createResponse(false, 'Error al intentar modificar el elemento ' . $e->getMessage());
        }
        return json_encode($response);
    }
}
