<?php
namespace PhpUnitsOfMeasureTest\Fixtures\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractDerivedPhysicalQuantity;
use PhpUnitsOfMeasure\DerivedUnitOfMeasure;

class Plooposity extends AbstractDerivedPhysicalQuantity
{
    protected static $unitDefinitions;
    protected static $componentQuantities;

    protected static function initialize()
    {
        static::setComponentQuantities(
            [Wigginess::class],
            [Wonkicity::class]
        );

        $native = new DerivedUnitOfMeasure(
            'ho',
            [Wigginess::getUnit('s')],
            [Wonkicity::getUnit('vorp')]
        );
        $native->addAlias('horp');
        $native->addAlias('horps');
        static::addUnit($native);

        $unit = new DerivedUnitOfMeasure(
            'je',
            [Wigginess::getUnit('t')],
            [Wonkicity::getUnit('u')]
        );
        $unit->addAlias('jerg');
        $unit->addAlias('jergs');
        static::addUnit($unit);
    }
}
