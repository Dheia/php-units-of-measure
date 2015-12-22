<?php
namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;

/**
 * Objects of this type represent a special dimensionless
 * quantity.  It's used, as a coefficient placeholder when cancelling
 * values in derived quantities.  It would also be the
 * resulting type of division operation in which all units cancel,
 * for instance, Length / Length.
 */
class DimensionlessCoefficient extends AbstractPhysicalQuantity
{
    protected static $unitDefinitions;

    protected static function initialize()
    {
        $coefficient = UnitOfMeasure::nativeUnitFactory('');
        static::addUnit($coefficient);
    }

    /**
     * This class has a unique constructor, in that there
     * is never a unit name specified.  Dimensionless coefficients
     * only have a scalar value.
     *
     * @param float $value The dimensionless scalar value.
     */
    public function __construct($value)
    {
        parent::__construct($value, '');
    }
}
