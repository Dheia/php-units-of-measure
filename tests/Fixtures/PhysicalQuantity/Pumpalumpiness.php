<?php
namespace PhpUnitsOfMeasureTest\Fixtures\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractDerivedPhysicalQuantity;
use PhpUnitsOfMeasure\DerivedUnitOfMeasure;
use PhpUnitsOfMeasure\HasSIUnitsTrait;

class Pumpalumpiness extends AbstractDerivedPhysicalQuantity
{
    use HasSIUnitsTrait;

    protected static $unitDefinitions;
    protected static $componentQuantities;

    protected static function initialize()
    {
        static::setComponentQuantities(
            [Woogosity::class, Wigginess::class, Wigginess::class],
            [Wonkicity::class, Wonkicity::class]
        );

        $native = new DerivedUnitOfMeasure(
            'fl',
            [Woogosity::getUnit('l'), Wigginess::getUnit('s'), Wigginess::getUnit('s')],
            [Wonkicity::getUnit('v'), Wonkicity::getUnit('vorp')]
        );
        $native->addAlias('floop');
        $native->addAlias('floops');
        static::addUnit($native);
        static::addMissingSIPrefixedUnits(
            $native,
            1,
            '%pfl',
            [
                '%Pfloop',
                '%Pfloops',
            ]
        );

        $unit = new DerivedUnitOfMeasure(
            'gl',
            [Woogosity::getUnit('p'), Wigginess::getUnit('t'), Wigginess::getUnit('t')],
            [Wonkicity::getUnit('u'), Wonkicity::getUnit('uvees')]
        );
        $unit->addAlias('glerg');
        $unit->addAlias('glergs');
        static::addUnit($unit);
    }
}
