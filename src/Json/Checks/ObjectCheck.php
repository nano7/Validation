<?php namespace Nano7\Validation\Json\Checks;

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
        // Verificar se eh um objeto
        if (! is_object($entity)) {
            $this->error($entityName, "Expected to be an object");
            return false;
        }

        return $this->validateProperties($entity, $schema, $entityName);
    }
}