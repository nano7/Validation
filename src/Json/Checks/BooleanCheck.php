<?php namespace Nano7\Validation\Json\Checks;

trait BooleanCheck
{
    /**
     * Check type boolean.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkTypeBoolean($entity, $schema, $entityName)
    {
        // Verificar se eh um boolean
        if (! is_bool($entity)) {
            $this->error($entityName, "Expected to be an boolean");
            return false;
        }

        return true;
    }
}