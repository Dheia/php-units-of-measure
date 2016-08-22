namespace PhpUnitsOfMeasure\PhysicalQuantity;

use PhpUnitsOfMeasure\AbstractPhysicalQuantity;
use PhpUnitsOfMeasure\UnitOfMeasure;
<?php if (array_key_exists('metric_prefixes', $data)) {?>
use PhpUnitsOfMeasure\HasSIUnitsTrait;
<?php } ?>

class <?=$data['name']?> extends AbstractPhysicalQuantity
{
<?php if (array_key_exists('metric_prefixes', $data)) {?>
    use HasSIUnitsTrait;

<?php } ?>
    protected static $unitDefinitions;

    protected static function initialize()
    {
        $newUnit = UnitOfMeasure::nativeUnitFactory('<?=$data['si_unit'][0]?>');
<?php foreach ($data['si_unit'] as $index => $unit) {
    if ($index == 0) continue; ?>
        $newUnit->addAlias('<?=$unit?>');
<?php } ?>
        static::addUnit($newUnit);
<?php if (array_key_exists('metric_prefixes', $data)) {?>
        static::addMissingSIPrefixedUnits(
            $newUnit,
            <?=$data['metric_prefixes']['si_unit_scaling_factor']?>,
            '<?=$data['metric_prefixes']['patterns'][0]?>',
            [
<?php foreach ($data['metric_prefixes']['patterns'] as $index => $pattern) {
    if ($index == 0) continue; ?>
                '<?=$pattern?>',
<?php } ?>
            ]
        );
<?php } ?>

<?php foreach ($data['interpreted_conversion_factors'] as $factor) {
    if (array_key_exists('constant', $factor)) { ?>
        $newUnit = UnitOfMeasure::linearUnitFactory('<?=$factor['units'][0]?>', <?=$factor['constant']?>);
<?php } else { ?>
        $newUnit = new UnitOfMeasure(
            '<?=$factor['units'][0]?>',
            function ($x) {
                return <?=$factor['formula']?>;
            },
            function ($x) {
                return "something has to happen here";
            }
        );
<?php }
foreach ($factor['units'] as $index => $unit) {
    if ($index == 0) continue; ?>
        $newUnit->addAlias('<?=$unit?>');
<?php } ?>
        static::addUnit($newUnit);

<?php } ?>
    }
}
