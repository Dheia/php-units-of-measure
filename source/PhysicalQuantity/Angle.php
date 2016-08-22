<?php
namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;
use PhpUnitsOfMeasure\HasSIUnitsTrait;

class Angle extends AbstractPhysicalQuantity
{
    use HasSIUnitsTrait;

    protected static $unitDefinitions;

    protected static function initialize()
    {
        $newUnit = UnitOfMeasure::nativeUnitFactory('rad');
        $newUnit->addAlias('radian');
        $newUnit->addAlias('radians');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%prad',
            [
                '%Pradian',
                '%Pradians',
            ]
        );

        $newUnit = UnitOfMeasure::linearUnitFactory('deg', M_PI / 180);
        $newUnit->addAlias('°');
        $newUnit->addAlias('degree');
        $newUnit->addAlias('degrees');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%pdeg',
            [
                '%Pdegree',
                '%Pdegrees',
            ]
        );

        $newUnit = UnitOfMeasure::linearUnitFactory('arcmin', M_PI / 180 / 60);
        $newUnit->addAlias('′');
        $newUnit->addAlias('arcminute');
        $newUnit->addAlias('arcminutes');
        $newUnit->addAlias('amin');
        $newUnit->addAlias('am');
        $newUnit->addAlias('MOA');
        static::addUnit($newUnit);

        $newUnit = UnitOfMeasure::linearUnitFactory('arcsec', M_PI / 180 / 3600);
        $newUnit->addAlias('″');
        $newUnit->addAlias('arcsecond');
        $newUnit->addAlias('arcseconds');
        $newUnit->addAlias('asec');
        $newUnit->addAlias('as');
        static::addUnit($newUnit);
        static::addMissingSIPrefixedUnits(
            $newUnit,
            1,
            '%Parcsec',
            [
                '%Parcsecond',
                '%Parcseconds',
                '%pasec',
                '%pas',
            ]
        );
    }
}
