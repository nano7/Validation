<?php namespace Nano7\Validation\Json\Checks;

trait NullCheck
{
    /**
     * Check type null.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkTypeNull($entity, $schema, $entityName)
    {
        // Verificar se eh um null
        if (! is_null($entity)) {
            $this->error($entityName, "Expected to be an null");
            return false;
        }

        return true;
    }
}