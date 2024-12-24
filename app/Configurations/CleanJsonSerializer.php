<?php
namespace App\Configurations;

/**
 * Serialize a simple PHP object into json
 * Should be used for POPO that has getter methods for the relevant properties to serialize
 * A property can be simple or by itself another POPO object
 *
 * Class CleanJsonSerializer
 */
class CleanJsonSerializer {
    
    /**
     * Local cache of a property getters per class - optimize reflection code if the same object appears several times
     * @var array
     */
    private $classPropertyGetters = array();
    
    /**
     * @param mixed $object
     * @return string|false
     */
    public function Serialize($object)
    {
        return $this->SerializeInternal($object);
    }
    
    /**
     * @param $object
     * @return array
     */
    private function SerializeInternal($object)
    {
        if (is_array($object)) {
            $result = $this->SerializeArray($object);
        } elseif (is_object($object)) {
            $result = $this->SerializeObject($object);
        } else {
            $result = $object;
        }
        return $result;
    }
    
    /**
     * @param $object
     * @return \ReflectionClass
     */
    private function GetClassPropertyGetters($object)
    {
        $className = get_class($object);
        if (!isset($this->classPropertyGetters[$className])) {
            $reflector = new \ReflectionClass($className);
            $properties = $reflector->getProperties();
            $getters = array();
            foreach ($properties as $property)
            {
                $name = $property->getName();
                $getter = "get" . ucfirst($name);
                try {
                    $reflector->getMethod($getter);
                    $getters[$name] = $getter;
                } catch (\Exception $e) {
                    // if no getter for a specific property - ignore it
                }
            }
            $this->classPropertyGetters[$className] = $getters;
        }
        return $this->classPropertyGetters[$className];
    }
    
    /**
     * @param $object
     * @return array
     */
    private function SerializeObject($object) {
        $properties = $this->GetClassPropertyGetters($object);
        $data = array();
        foreach ($properties as $name => $property)
        {
            $data[$name] = $this->SerializeInternal($object->$property());
        }
        return $data;
    }
    
    /**
     * @param $array
     * @return array
     */
    private function SerializeArray($array)
    {
        $result = array();
        foreach ($array as $key => $value) {
            $result[$key] = $this->SerializeInternal($value);
        }
        return $result;
    }
} 