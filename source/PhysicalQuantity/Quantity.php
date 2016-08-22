<?php
namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;
use PhpUnitsOfMeasure\HasSIUnitsTrait;

class Quantity extends AbstractPhysicalQuantity
{
    use HasSIUnitsTrait;

    protected static $unitDefinitions;

    protected static function initialize()
    {
        $newUnit = UnitOfMeasure::nativeUnitFactory('mol');
        $newUnit->addAlias('mole');
        $newUnit->addAlias('moles');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%pmol',
            [
                '%Pmole',
                '%Pmoles',
            ]
        );

    }
}
