<?php namespace Nano7\Validation\Json\Checks;

use Nano7\Support\Arr;

trait ObjectCheck
{
    /**
     * Check type object.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkTypeObject($entity, $schema, $entityName)
    {
        // Verificar se deve converter array em object
        if (is_array($entity) && Arr::get($this->options, 'array_equal_object', false)) {
            $first_key = (count($entity) > 0) ? array_keys($entity)[0] : null;
            if ((! is_null($first_key)) && (! is_numeric($first_key)) && (! is_int($first_key))) {
                $entity = (object) $entity;
            }
        }

        // Verificar se eh um objeto
        if (! is_object($entity)) {
            $this->error($entityName, "Expected to be an object");
            return false;
        }

        return $this->validateProperties($entity, $schema, $entityName);
    }
}