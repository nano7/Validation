<?php namespace Nano7\Validation\Json\Checks;

trait RequiredCheck
{
    /**
     * Check required.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkTypeRequired($entity, $schema, $ruleValue)
    {
        return ($ruleValue === true);
    }

    /**
     * Check required_with.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkTypeRequiredWith($entity, $schema, $ruleValue)
    {
        $ruleValue = (array) $ruleValue;

        foreach ($ruleValue as $attr) {
            if (isset($entity->{$attr})) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check required_with_all.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkTypeRequiredWithAll($entity, $schema, $ruleValue)
    {
        $ruleValue = (array) $ruleValue;

        foreach ($ruleValue as $attr) {
            if (! isset($entity->{$attr})) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check required_without.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkRequiredWithout($entity, $schema, $ruleValue)
    {
        $ruleValue = (array) $ruleValue;

        foreach ($ruleValue as $attr) {
            if (! isset($entity->{$attr})) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check required_without_all.
     *
     * @param $entity
     * @param $schema
     * @param $ruleValue
     * @return bool
     */
    protected function checkRequiredWithoutAll($entity, $schema, $ruleValue)
    {
        $ruleValue = (array) $ruleValue;

        foreach ($ruleValue as $attr) {
            if (isset($entity->{$attr})) {
                return false;
            }
        }

        return true;
    }
}