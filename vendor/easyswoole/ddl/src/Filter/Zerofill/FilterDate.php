<?php
/**
 * Created by PhpStorm.
 * User: xcg
 * Date: 2019/10/16
 * Time: 16:50
 */

namespace EasySwoole\DDL\Filter\Zerofill;


use EasySwoole\DDL\Blueprint\Column;
use EasySwoole\DDL\Contracts\FilterInterface;

class FilterDate implements FilterInterface
{
    public static function run(Column $column)
    {
        if ($column->getZeroFill()) {
            throw new \InvalidArgumentException('col ' . $column->getColumnName() . ' type date no require zerofill ');
        }
    }
}