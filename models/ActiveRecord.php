<?php

/*
    ACTIVERECORD
    Este es el MODELO general, donde se contiene la DB, y de aquí se heredan los demás modelos,
    aquí se encuentra el CRUD
*/

// Eliminación de namespaces y uso directo de clases para compatibilidad con PHP 5.3
require_once __DIR__ . '/../includes/database.php';

class ActiveRecord {
    // Atributos
    protected $id;
    // Base de Datos (BD)
    protected static $db;
    protected static $table = '';
    protected static $dbColumns = array();

    // alerts y Mensajes
    protected static $alerts = array();
    
    /**
     * Definir la conexión a la BD - incluye/database.php
     *
     * @param PDO $database Instancia de PDO
     */
    public static function setDB($database) {
        self::$db = $database;
    }

    /**
     * Set un tipo de alert
     *
     * @param string $type Tipo de alert
     * @param string $message Mensaje de alert
     */
    public static function setAlert($type, $message) {
        static::$alerts[$type][] = $message;
    }

    /**
     * Obtener las alerts
     *
     * @return array alerts almacenadas
     */
    public static function getAlerts() {
        return static::$alerts;
    }

    /**
     * Consulta SQL para crear un objeto en Memoria (Active Record)
     *
     * @param string $query Consulta SQL
     * @return array Resultados como objetos
     */
    private static function querySQL($query) {
        // Preparar la consulta SQL
        $stmt = self::$db->prepare($query);
        
        // Ejecutar la consulta
        $stmt->execute();
        
        // Obtener los resultados como un array asociativo
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Crear objetos a partir de los resultados
        $array = array();
        foreach ($results as $record) {
            $array[] = static::createObject($record);
        }
        
        // Retornar los resultados
        return $array;
    }

    /**
     * Crea el objeto en memoria que es igual al de la BD
     *
     * @param array $record Registro de la base de datos
     * @return object Objeto creado a partir del registro
     */
    protected static function createObject($record) {
        $object = new static;

        foreach ($record as $key => $value) {
            if (property_exists($object, $key)) {
                $object->$key = $value;
            }
        }
        
        return $object;
    }

    /**
     * Identificar y unir los atributos de la BD
     *
     * @return array Atributos del objeto
     */
    private function attributes() {
        $attributes = array();
        foreach (static::$dbColumns as $column) {
            if ($column === 'id') continue;
            $attributes[$column] = $this->$column;
        }
        return $attributes;
    }

    /**
     * Sanitizar los datos antes de guardarlos en la BD
     *
     * @return array Atributos a sanitizar
     */
    private function sanitizeAttributes() {
        $attributes = $this->attributes();
        $sanitized = array();
        foreach ($attributes as $key => $value) {
            $sanitized[$key] = $this->sanitizeValue($value);
        }
        return $sanitized;
    }
    
    /**
     * Sanitizar un valor según su tipo
     *
     * @param mixed $value Valor a sanitizar
     * @return mixed Valor sanitizado
     */
    private function sanitizeValue($value) {
        if (is_string($value)) {
            return trim($value); // Elimina espacios en blanco al inicio y al final
        } elseif (is_bool($value)) {
            return $value ? 1 : 0;
        } elseif (is_null($value)) {
            return null; 
        } else {
            return $value;
        }
    }
    
    /**
     * Actualiza las propiedades del objeto con los valores proporcionados.
     *
     * @param array $args Datos para actualizar las propiedades del objeto
     */
    public function updateProperties($args = array()) { 
        foreach ($args as $key => $value) {
            if (property_exists($this, $key) && !is_null($value)) {
                $this->$key = $value;
            }
        }
    }

    // Registros - CRUD
    /**
     * Guardar Registro. Si ya cuenta con un id se actualiza, de lo contrario se crea
     *
     * @return mixed Resultado de la operación de guardar
     */
    public function save() {
        return !is_null($this->id) ? $this->update() : $this->create();
    }

    /**
     * CRUD - Crea un nuevo registro
     *
     * @return mixed Resultado de la operación de creación
     */
    private function create() {
        $attributes = $this->sanitizeAttributes();
    
        // Convertir los atributos en una cadena de nombres de columnas y marcadores de posición
        $columns = implode(', ', array_keys($attributes));
        $placeholders = ':' . implode(', :', array_keys($attributes));
    
        // Preparar la sentencia INSERT con sentencia preparada
        $query = "INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})";
        $stmt = self::$db->prepare($query);
    
        // Asignar los valores de los atributos a los marcadores de posición
        foreach ($attributes as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        
        try {
            $stmt->execute();
            $this->id = self::$db->lastInsertId();
            return true;
        } catch (PDOException $error) {
            static::setAlert('error', 'Hubo un problema al crear');
            return false;
        }
    }

    /**
     * CRUD - Actualizar el registro
     *
     * @return bool Resultado de la operación de actualización
     */
    public function update() {
        $attributes = $this->sanitizeAttributes();
        $updates = array();
        foreach ($attributes as $key => $value) {
            $updates[] = "{$key} = :{$key}";
        }
        $updatesStr = implode(', ', $updates);
        $query = "UPDATE " . static::$table . " SET {$updatesStr} WHERE id = :id LIMIT 1";
        $stmt = self::$db->prepare($query);
        foreach ($attributes as $key => $value) {
            $stmt->bindValue(":{$key}", $value);
        }
        $stmt->bindValue(":id", $this->id);
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $error) {
            return false;
        }
    }

    /**
     * Eliminar un Registro por su ID
     *
     * @return bool Resultado de la operación de eliminación
     */
    public function delete() {
        $query = "DELETE FROM " . static::$table . " WHERE id = :id LIMIT 1";
        $stmt = self::$db->prepare($query);
        $stmt->bindValue(':id', $this->id);
        try {
            $stmt->execute();
            return true;
        } catch (PDOException $error) {
            return false;
        }
    }
}
