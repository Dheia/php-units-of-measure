<?php
// ============================================================================
// WARNING! - This file and others like it are auto-generated by the build
// script.  Please do not edit it directly.  See the physical_quantities.yml
// file in the project root, and the build tooling in the /build directory.
// ============================================================================

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
            '%{p}J',
            [
                '%{P}joule',
                '%{P}joules',
            ]
        );

        $newUnit = UnitOfMeasure::linearUnitFactory('Wh', 3600);
        $newUnit->addAlias('watt hour');
        $newUnit->addAlias('watt hours');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%{p}Wh',
            [
                '%{P}watt hour',
                '%{P}watt hours',
            ]
        );

    }
}
