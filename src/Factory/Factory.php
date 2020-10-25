<?php

namespace App\Factory;

class Factory
{
    /**
     * Create class instance
     * @param $nameSpace
     * @param $className
     *
     * @return object
     */
    public function classInstance($nameSpace,$className)
    {
        $object = 'App\\' . $nameSpace . "\\" . $className . $nameSpace;

        return new $object();
    }
}