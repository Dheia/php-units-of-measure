<?php
namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;
use PhpUnitsOfMeasure\HasSIUnitsTrait;

class LuminousIntensity extends AbstractPhysicalQuantity
{
    use HasSIUnitsTrait;

    protected static $unitDefinitions;

    protected static function initialize()
    {
        $newUnit = UnitOfMeasure::nativeUnitFactory('cd');
        $newUnit->addAlias('candela');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%pcd',
            [
                '%Pcandela',
            ]
        );
    }
}
