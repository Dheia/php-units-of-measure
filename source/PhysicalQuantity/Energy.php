<?php
namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;
use PhpUnitsOfMeasure\HasSIUnitsTrait;

class Energy extends AbstractPhysicalQuantity
{
    use HasSIUnitsTrait;

    protected static $unitDefinitions;

    protected static function initialize()
    {
        $newUnit = UnitOfMeasure::nativeUnitFactory('J');
        $newUnit->addAlias('joule');
        $newUnit->addAlias('joules');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%pJ',
            [
                '%Pjoule',
                '%Pjoules',
            ]
        );

        $newUnit = UnitOfMeasure::linearUnitFactory('Wh', 3600);
        $newUnit->addAlias('watt hour');
        $newUnit->addAlias('watt hours');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%pWh',
            [
                '%Pwatt hour',
                '%Pwatt hours',
            ]
        );

    }
}
