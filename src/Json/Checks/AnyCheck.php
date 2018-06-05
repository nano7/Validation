<?php namespace Nano7\Validation\Json\Checks;

trait AnyCheck
{
    /**
     * Check type any.
     *
     * @param $entity
     * @param $schema
     * @param $entityName
     * @return bool
     */
    protected function checkTypeAny($entity, $schema, $entityName)
    {
        return true;
    }
}