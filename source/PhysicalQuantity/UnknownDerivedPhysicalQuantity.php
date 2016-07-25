<?php
namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractDerivedPhysicalQuantity;
use PhpUnitsOfMeasure\PhysicalQuantityInterface;
use PhpUnitsOfMeasure\DerivedUnitOfMeasure;

/**
 * This is a special case of AbstractDerivedPhysicalQuantity, and
 * represents derived (ie, composite) physical quantities who's
 * component quantites were not found to match a known AbstractDerivedPhysicalQuantity
 * class.  Because of this, objects of this derived quantity type are looser in their
 * unit requirements.
 */
class UnknownDerivedPhysicalQuantity extends AbstractDerivedPhysicalQuantity
{
    protected static $unitDefinitions;
    protected static $componentQuantities;

    static public function initialize()
    {
    }

    public function __construct(array $numerators, array $denominators)
    {
        $this->componentQuantities = [$numerators, $denominators];
    }

    public function getOriginalValue()
    {
        $value = 1;

        $value = array_reduce(
            $this->componentQuantities[0],
            function($carry, $element) {
                return $carry * $element->getOriginalValue();
            },
            $value
        );

        $value = array_reduce(
            $this->componentQuantities[1],
            function($carry, $element) {
                return $carry / $element->getOriginalValue();
            },
            $value
        );

        return $value;
    }

    public function getOriginalUnit()
    {
        $numeratorNativeUnits = array_map(
            function ($element) {
                return $element->getOriginalUnit();
            },
            $this->componentQuantities[0]
        );

        $denominatorNativeUnits = array_map(
            function ($element) {
                return $element->getOriginalUnit();
            },
            $this->componentQuantities[1]
        );

        $numeratorUnitNames = array_map(function ($e) {return $e->getName();}, $numeratorNativeUnits);
        $numeratorUnitNames = array_filter($numeratorUnitNames);
        $denominatorUnitNames = array_map(function ($e) {return $e->getName();}, $denominatorNativeUnits);
        $denominatorUnitNames = array_filter($denominatorUnitNames);

        $numeratorNameFragment = implode(' * ', $numeratorUnitNames);
        $denominatorNameFragment = implode(' / ', $denominatorUnitNames);
        $name = $numeratorNameFragment;
        if ($denominatorNameFragment) {
            $name .= (' / ' . $denominatorNameFragment);
        }

        return new DerivedUnitOfMeasure(
            $name,
            $numeratorNativeUnits,
            $denominatorNativeUnits
        );
    }
}
