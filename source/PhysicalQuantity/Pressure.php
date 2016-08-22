<?php
namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;
use PhpUnitsOfMeasure\HasSIUnitsTrait;

class Pressure extends AbstractPhysicalQuantity
{
    use HasSIUnitsTrait;

    protected static $unitDefinitions;

    protected static function initialize()
    {
        $newUnit = UnitOfMeasure::nativeUnitFactory('Pa');
        $newUnit->addAlias('pascal');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%pPa',
            [
                '%Ppascal',
            ]
        );

        $newUnit = UnitOfMeasure::linearUnitFactory('atm', 101325);
        $newUnit->addAlias('atmosphere');
        $newUnit->addAlias('atmospheres');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('bar', 100000);
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%pbar',
            [
            ]
        );

        $newUnit = UnitOfMeasure::linearUnitFactory('inHg', 3.386389e3);
        $newUnit->addAlias('inches of mercury');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('mmHg', 133.3224);
        $newUnit->addAlias('millimeters of mercury');
        $newUnit->addAlias('millimetres of mercury');
        $newUnit->addAlias('torr');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('psi', 6.894757e3);
        $newUnit->addAlias('pounds per square inch');
        static::addUnit($newUnit);

    }
}
