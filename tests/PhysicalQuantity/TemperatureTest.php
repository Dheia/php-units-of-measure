<?php

namespace PhpUnitsOfMeasureTest\PhysicalQuantity;

use PhpUnitsOfMeasure\PhysicalQuantity\Temperature;

class TemperatureTest extends AbstractPhysicalQuantityTestCase
{
    protected $supportedUnitsWithAliases = [
        'K',
        '°K',
        'kelvin',
        'YK',
        'yottakelvin',
        'ZK',
        'zettakelvin',
        'EK',
        'exakelvin',
        'PK',
        'petakelvin',
        'TK',
        'terakelvin',
        'GK',
        'gigakelvin',
        'MK',
        'megakelvin',
        'kK',
        'kilokelvin',
        'hK',
        'hectokelvin',
        'daK',
        'decakelvin',
        'dK',
        'decikelvin',
        'cK',
        'centikelvin',
        'mK',
        'millikelvin',
        'µK',
        'microkelvin',
        'nK',
        'nanokelvin',
        'pK',
        'picokelvin',
        'fK',
        'femtokelvin',
        'aK',
        'attokelvin',
        'zK',
        'zeptokelvin',
        'yK',
        'yoctokelvin',
        '°C',
        'C',
        'celsius',
        '°F',
        'F',
        'fahrenheit',
        '°R',
        'R',
        'rankine',
        '°De',
        'De',
        'delisle',
        '°N',
        'N',
        'newton',
        '°Ré',
        '°Re',
        'Ré',
        'Re',
        'réaumur',
        'reaumur',
        '°Rø',
        '°Ro',
        'Rø',
        'Ro',
        'rømer',
        'romer',
    ];

    protected function instantiateTestQuantity()
    {
        return new Temperature(1, 'K');
    }

    public function testToCelsius()
    {
        $unit = new Temperature(100, 'K');
        $this->assertEquals(-173.15, $unit->toUnit('celsius'));
    }

    public function testToFahrenheit()
    {
        $unit = new Temperature(100, 'K');
        $this->assertEquals(-279.67, $unit->toUnit('fahrenheit'));
    }
}
