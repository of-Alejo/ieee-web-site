<?php

/*
    ACTIVERECORD
    Este es el MODELO general, donde se contiene la DB, y de aquí se heredan los demás modelos,
    aquí se encuentra el CRUD
*/

namespace Model;
use PDO;
use PDOException;

class ActiveRecord {
    // Atributos
    protected $id;
    // Base de Datos (BD)
    protected static PDO $db;
    protected static string $table = '';
    protected static array $dbColumns = [];

    // alerts y Mensajes
    protected static array $alerts = [];
    
    /**
     * Definir la conexión a la BD - incluye/database.php
     *
     * @param PDO $database Instancia de PDO
     */
    public static function setDB($database) : void {
        self::$db = $database;
    }

    /**
     * Set un tipo de alert
     *
     * @param string $type Tipo de alert
     * @param string $message Mensaje de alert
     */
    public static function setAlert(string $type, string $message) : void {
        static::$alerts[$type][] = $message;
    }

    /**
     * Obtener las alerts
     *
     * @return array alerts almacenadas
     */
    public static function getAlerts() : array {
        return static::$alerts;
    }

    /**
     * Consulta SQL para crear un objeto en Memoria (Active Record)
     *
     * @param string $query Consulta SQL
     * @return array Resultados como objetos
     */
    private static function querySQL(string $query) : array {
        // Preparar la consulta SQL
        $stmt = self::$db->prepare($query);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener los resultados como un array asociativo
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Crear objetos a partir de los resultados
        $array = [];
        foreach ($results as $record) 
            $array[] = static::createObject($record);
        
        // Retornar los resultados
        return $array;
    }

    /**
     * Crea el objeto en memoria que es igual al de la BD
     *
     * @param mixed $record Registro de la base de datos
     * @return object Objeto creado a partir del registro
     */
    protected static function createObject(mixed $record) : object {
        $object = new static;

        foreach ($record as $key => $value ) 
            if (property_exists( $object, $key  )) 
                $object->$key = $value;
        
        return $object;
    }

    /**
     * Identificar y unir los atributos de la BD
     *
     * @return array Atributos del objeto
     */
    private function attributes() : array {
        $attributes = [];
        foreach (static::$dbColumns as $column) {
            if($column === 'id') continue;
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    /**
     * Sanitizar los datos antes de guardarlos en la BD
     *
     * @return array Atributos a sanitizar
     */
    private function sanitizeAttributes() : array {
        $attributes = $this->attributes();
        $sanitized = [];
        foreach ($attributes as $key => $value)
            $sanitized[$key] = $this->sanitizeValue($value);
        
        return $sanitized;
    }
    
    /**
     * Sanitizar un valor según su tipo
     *
     * @param mixed $value Valor a sanitizar
     * @return mixed Valor sanitizado
     */
    private function sanitizeValue($value) : mixed {
        if (is_string($value))
            return trim($value); // Elimina espacios en blanco al inicio y al final
        elseif (is_bool($value))
            return $value ? 1 : 0;
        elseif (is_null($value))
            return null; 
        else
            return $value;
    }
    
    /**
     * Actualiza las propiedades del objeto con los valores proporcionados.
     *
     * @param array $args Datos para actualizar las propiedades del objeto
     */
    public function updateProperties(array $args=[]) : void { 
        foreach ($args as $key => $value)
            if (property_exists($this, $key) && !is_null($value)) 
                $this->$key = $value;
    }


    // Registros - CRUD
    /**
     * Guardar Registro. SI ya cuenta con un id se actualiza de lo contrario se crea
     *
     * @return mixed Resultado de la operación de guardar
     */
    public function save() : mixed{
        $result = '';
        // Actualizar
        $result = !is_null($this->id) ? $this->update() : $this->create();
        
        return $result;
    }

    // CRUD
    /**
     * CRUD - Crea un nuevo registro
     *
     * @return mixed Resultado de la operación de creación
     */
    private function create() : mixed {
        $attributes = $this->sanitizeAttributes();
    
        // Convertir los atributos en una cadena de nombres de columnas y marcadores de posición
        $columns = implode(', ', array_keys($attributes));
        $placeholders = ':' . implode(', :', array_keys($attributes));
    
        // Preparar la sentencia INSERT con sentencia preparada
        $query = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
        $stmt = self::$db->prepare($query);
    
        // Asignar los valores de los atributos a los marcadores de posición
        foreach ($attributes as $key => $value) 
            $stmt->bindValue(":{$key}", $value);
        
        try {
            $stmt->execute();
            $id = self::$db->lastInsertId();
            return ['result' => true, 'id' => $id];
        } catch (PDOException $error) {
            self::setAlert('error', 'Hubo un problema al crear');
            return ['result' => false, 'error' => $error->getMessage()];
        }
    }
    
    /**
     * CRUD - Actualizar el registro
     *
     * @return mixed Resultado de la operación de actualización
     */
    public function update() : mixed {
        // Sanitizar los datos
        $attributes = $this->sanitizeAttributes();
    
        // Convertir los atributos en una cadena de actualización con marcadores de posición
        $updates = [];
        foreach ($attributes as $key => $value) 
            $updates[] = "{$key} = :{$key}";
        
        $updatesStr = implode(', ', $updates);
    
        // Preparar la sentencia UPDATE con sentencia preparada
        $query = "UPDATE " . static::$table . " SET {$updatesStr} WHERE id = :id LIMIT 1";
        $stmt = self::$db->prepare($query);
    
        // Asignar los valores de los atributos y el ID a los marcadores de posición
        foreach ($attributes as $key => $value) 
            $stmt->bindValue(":{$key}", $value);
        
        $stmt->bindValue(":id", $this->id);
    
        // Ejecutar la consulta
        try {
            $stmt->execute();
            return ['result' => true];
        } catch (PDOException $error) {
            return ['result' => false, 'error' => $error->getMessage()];
        }
    }

    /**
     * Eliminar un Registro por su ID
     *
     * @return bool Resultado de la operación de eliminación
     */
    public function delete() : bool {
        // Preparar la consulta SQL
        $query = "DELETE FROM " . static::$table . " WHERE id = :id LIMIT 1";
        $stmt = self::$db->prepare($query);
    
        // Asignar el valor del atributo "id" al marcador de posición ":id"
        $stmt->bindValue(':id', $this->id);
    
        // Ejecutar la consulta
        try {
            $result = $stmt->execute();
        } catch (PDOException $error) {
            $result = $error->getMessage();
        }
    
        // Retornar el resultado de la ejecución (true o false)
        return $result;
    }
        
    /**
     * Obtener todos los Registros
     *
     * @param string $order Orden de los resultados
     * @return array Todos los registros
     */
    public static function all(string $order = 'DESC') : array {
        $query = "SELECT * FROM " . static::$table . " ORDER BY id {$order}";
        $result = self::querySQL($query);
        return $result;
    }

    /**
     * Busca un registro por su id
     *
     * @param string $id ID del registro a buscar
     * @return object Objeto encontrado
     */
    public static function find(string $id) : object {
        $query = "SELECT * FROM " . static::$table  ." WHERE id = {$id}";
        $result = self::querySQL($query);
        return array_shift( $result ) ;
    }

    /**
     * Obtener registros con cierta cantidad
     *
     * @param string $limit Límite de registros a obtener
     * @return array Registros obtenidos
     */
    
    public static function get(string $limit) : array {
        $query = "SELECT * FROM " . static::$table . " ORDER BY id DESC LIMIT {$limit}";
        $result = self::querySQL($query);
        return $result;
    }

    /**
     * Búsqueda Where con Columna
     *
     * @param string $column Columna para la condición WHERE
     * @param string $value Valor de la columna
     * @return mixed Objeto encontrado o null
     */
    public static function where(string $column, string $value) : mixed {
        $query = "SELECT * FROM " . static::$table . " WHERE {$column} = '{$value}'";
        $result = self::querySQL($query);
        return array_shift($result);
    }

    /**
     * Búsqueda personalizada con múltiples condiciones
     *
     * @param array $conditions Condiciones para la búsqueda
     * @param string $order Orden de los resultados
     * @return array Registros encontrados
     */
    public static function findByConditions(array $conditions, string $order = 'DESC') : array {
        $query = "SELECT * FROM " . static::$table . " WHERE ";
        $conditionsArr = [];
        foreach ($conditions as $key => $value) {
            $conditionsArr[] = "{$key} = '{$value}'";
        }
        $query .= implode(' AND ', $conditionsArr) . " ORDER BY id {$order}";
        return self::querySQL($query);
    }
}
