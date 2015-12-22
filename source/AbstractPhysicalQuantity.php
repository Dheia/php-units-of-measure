<?php
namespace PhpUnitsOfMeasure;

abstract class AbstractPhysicalQuantity implements PhysicalQuantityInterface
{
    /**
     * The collection of units in which this quantity can be represented.
     *
     * Commented out to ensure that each child class defines its own instance of this static.
     *
     * @var UnitOfMeasureInterface[]
     */
    // protected static $registeredUnits;

    /**
     * Register a new unit of measure for all instances of this this physical quantity.
     *
     * @throws Exception\DuplicateUnitNameOrAlias If the unit name or any alias already exists
     *
     * @param UnitOfMeasureInterface $unit The new unit of measure
     */
    public static function addUnit(UnitOfMeasureInterface $unit)
    {
        if (static::unitNameOrAliasesAlreadyRegistered($unit)) {
            throw new Exception\DuplicateUnitNameOrAlias([
                ':labels' => implode(', ', array_merge([$unit->getName()], $unit->getAliases()))
            ]);
        }

        static::$registeredUnits[] = $unit;
    }

    /**
     * Get the unit of measure that matches the given name by either name or alias.
     *
     * @param string $unitName A name or alias by which the unit is known.
     *
     * @throws Exception\UnknownUnitOfMeasure when an unknown unit of measure is given
     *
     * @return UnitOfMeasureInterface
     */
    public static function getUnit($unitName)
    {
        // If this class hasn't been initialized yet, do so now
        if (!is_array(static::$registeredUnits)) {
            static::$registeredUnits = [];
            static::initialize();
        }

        foreach (static::$registeredUnits as $unitOfMeasure) {
            if ($unitName === $unitOfMeasure->getName() || $unitOfMeasure->isAliasOf($unitName)) {
                return $unitOfMeasure;
            }
        }

        throw new Exception\UnknownUnitOfMeasure([':unit' => $unitName]);
    }

    /**
     * Given a unit of measure, determine if its name or any of its aliases conflict
     * with the set of already-known unit names and aliases.
     *
     * @param UnitOfMeasureInterface $unit The unit in question
     *
     * @return boolean true if there is a conflict, false if there is not
     */
    protected static function unitNameOrAliasesAlreadyRegistered(UnitOfMeasureInterface $unit)
    {
        // If this class hasn't been initialized yet, do so now
        if (!is_array(static::$registeredUnits)) {
            static::$registeredUnits = [];
            static::initialize();
        }

        $currentUnitNamesAndAliases = [];
        foreach (static::$registeredUnits as $unitOfMeasure) {
            $currentUnitNamesAndAliases[] = $unitOfMeasure->getName();
            $currentUnitNamesAndAliases = array_merge($currentUnitNamesAndAliases, $unitOfMeasure->getAliases());
        }

        $newUnitNamesAndAliases = array_merge([$unit->getName()], $unit->getAliases());

        return count(array_intersect($currentUnitNamesAndAliases, $newUnitNamesAndAliases)) > 0;
    }

    /**
     * Initialize the static properties of this quantity class, such as the set of
     * default units of measure.
     */
    protected static function initialize()
    {
    }


    /**
     * The scalar value, in the original unit of measure.
     *
     * @var float
     */
    protected $originalValue;

    /**
     * The original unit of measure's string representation.
     *
     * @var string
     */
    protected $originalUnit;

    /**
     * Store the value and its original unit.
     *
     * @param float  $value The scalar value of the measurement
     * @param string $unit  The unit of measure in which this value is provided
     *
     * @throws Exception\NonNumericValue If the value is not numeric
     * @throws Exception\NonStringUnitName If the unit is not a string
     */
    public function __construct($value, $unit)
    {
        if (!is_numeric($value)) {
            throw new Exception\NonNumericValue([':value' => $value]);
        }

        if (!is_string($unit)) {
            throw new Exception\NonStringUnitName([':name' => $unit]);
        }

        $this->originalValue = $value;
        $this->originalUnit  = $unit;
    }

    /**
     * Get the original unit for this particular quantity object.
     *
     * @return UnitOfMeasureInterface The original unit of measure for this object.
     */
    public function getOriginalUnit()
    {
        return static::getUnit($this->originalUnit);
    }

    /**
     * Get the original scalar value for this particular quantity object.
     *
     * @return float
     */
    public function getOriginalValue()
    {
        return $this->originalValue;
    }

    /**
     * @see \PhpUnitsOfMeasure\PhysicalQuantityInterface::toUnit
     */
    public function toUnit($toUnit)
    {
        return $this->toUnitOfMeasure(static::getUnit($toUnit));
    }

    /**
     * Convert this quantity to the given unit of measure.
     *
     * @param UnitOfMeasureInterface $unit The object representing the target unit of measure.
     *
     * @return float This quantity's value in the given unit of measure.
     */
    protected function toUnitOfMeasure(UnitOfMeasureInterface $toUnit)
    {
        $thisValueInNativeUnit = $this->toNativeUnit();

        return $toUnit->convertValueFromNativeUnitOfMeasure($thisValueInNativeUnit);
    }

    /**
     * @see \PhpUnitsOfMeasure\PhysicalQuantityInterface::toNativeUnit
     */
    public function toNativeUnit()
    {
        return $this->getOriginalUnit()->convertValueToNativeUnitOfMeasure($this->getOriginalValue());
    }

    /**
     * @see \PhpUnitsOfMeasure\PhysicalQuantityInterface::__toString
     */
    public function __toString()
    {
        return trim($this->getOriginalValue() . ' ' . $this->getOriginalUnit()->getName());
    }

    /**
     * @see \PhpUnitsOfMeasure\PhysicalQuantityInterface::add
     */
    public function add(PhysicalQuantityInterface $quantity)
    {
        if (!$this->isEquivalentQuantity($quantity)) {
            throw new Exception\PhysicalQuantityMismatch([
                ':lhs' => (string) $this,
                ':rhs' => (string) $quantity
            ]);
        }

        $quantityValueInThisOriginalUnit = $quantity->toUnitOfMeasure($this->getOriginalUnit());
        $newValue = $this->getOriginalValue() + $quantityValueInThisOriginalUnit;

        return new static($newValue, $this->getOriginalUnit()->getName());
    }

    /**
     * @see \PhpUnitsOfMeasure\PhysicalQuantityInterface::subtract
     */
    public function subtract(PhysicalQuantityInterface $quantity)
    {
        if (!$this->isEquivalentQuantity($quantity)) {
            throw new Exception\PhysicalQuantityMismatch([
                ':lhs' => (string) $this,
                ':rhs' => (string) $quantity
            ]);
        }

        $quantityValueInThisOriginalUnit = $quantity->toUnitOfMeasure($this->getOriginalUnit());
        $newValue = $this->getOriginalValue() - $quantityValueInThisOriginalUnit;

        return new static($newValue, $this->getOriginalUnit()->getName());
    }

    /**
     * @see \PhpUnitsOfMeasure\PhysicalQuantityInterface::isEquivalentQuantity
     */
    public function isEquivalentQuantity(PhysicalQuantityInterface $testQuantity)
    {
        return get_class($this) === get_class($testQuantity);
    }

    /**
     * @see \PhpUnitsOfMeasure\PhysicalQuantityInterface::multiplyBy
     */
    public function multiplyBy(PhysicalQuantityInterface $quantity)
    {
        return DerivedPhysicalQuantityFactory::factory([$this, $quantity], []);
    }

    /**
     * @see \PhpUnitsOfMeasure\PhysicalQuantityInterface::divideBy
     */
    public function divideBy(PhysicalQuantityInterface $quantity)
    {
        return DerivedPhysicalQuantityFactory::factory([$this], [$quantity]);
    }
}
