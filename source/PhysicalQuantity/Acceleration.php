<?php
namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;

class Acceleration extends AbstractPhysicalQuantity
{
    protected static $unitDefinitions;

    protected static function initialize()
    {
        $newUnit = UnitOfMeasure::nativeUnitFactory('m/s^2');
        $newUnit->addAlias('m/s²');
        $newUnit->addAlias('meter per second squared');
        $newUnit->addAlias('meters per second squared');
        $newUnit->addAlias('metre per second squared');
        $newUnit->addAlias('metres per second squared');
        static::addUnit($newUnit);

    }
}
