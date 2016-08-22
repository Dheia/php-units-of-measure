<?php
namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;
use PhpUnitsOfMeasure\HasSIUnitsTrait;

class Time extends AbstractPhysicalQuantity
{
    use HasSIUnitsTrait;

    protected static $unitDefinitions;

    protected static function initialize()
    {
        $newUnit = UnitOfMeasure::nativeUnitFactory('s');
        $newUnit->addAlias('sec');
        $newUnit->addAlias('secs');
        $newUnit->addAlias('second');
        $newUnit->addAlias('seconds');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%ps',
            [
                '%Psec',
                '%Psecs',
                '%Psecond',
                '%Pseconds'
            ]
        );

        $newUnit = UnitOfMeasure::linearUnitFactory('m', 60);
        $newUnit->addAlias('min');
        $newUnit->addAlias('mins');
        $newUnit->addAlias('minute');
        $newUnit->addAlias('minutes');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('h', 3600);
        $newUnit->addAlias('hr');
        $newUnit->addAlias('hrs');
        $newUnit->addAlias('hour');
        $newUnit->addAlias('hours');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('d', 86400);
        $newUnit->addAlias('day');
        $newUnit->addAlias('days');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('w', 604800);
        $newUnit->addAlias('wk');
        $newUnit->addAlias('wks');
        $newUnit->addAlias('week');
        $newUnit->addAlias('weeks');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('yr', 31556952);
        $newUnit->addAlias('year');
        $newUnit->addAlias('years');
        $newUnit->addAlias('gregorian year');
        $newUnit->addAlias('gregorian years');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('decade', 315569520);
        $newUnit->addAlias('decades');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('century', 3155695200);
        $newUnit->addAlias('centuries');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('millennium', 31556952000);
        $newUnit->addAlias('millennia');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('jyr', 31557600);
        $newUnit->addAlias('julian year');
        $newUnit->addAlias('julian years');
        static::addUnit($newUnit);
    }
}
