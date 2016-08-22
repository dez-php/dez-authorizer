<?php

namespace Dez\Authorizer\Models;

use Dez\ORM\Model\Table as ORMTable;

/**
 * @property string $table
 */
class AbstractIntermediateModel extends ORMTable {

    public static function setTableName($name)
    {
        static::$table = $name;
    }

}