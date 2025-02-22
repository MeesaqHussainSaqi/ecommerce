<?php

namespace App\Response;

class BaseRepositoryResponse
{
    private $values;
    private $permissions;
    private $parameters;

    /**
     *
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     *
     * @param array $values
     */
    public function setValues($values)
    {
        $this->values = $values;
    }

    /**
     *
     * @return array
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     *
     * @param array $permission
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
    }

    /**
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     *
     * @param array $permission
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
    }
}
