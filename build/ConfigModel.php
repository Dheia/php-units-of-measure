<?php
use Symfony\Component\Yaml\Yaml;

class ConfigModel
{
    protected $raw_config = [];

    protected $intermediate_rep = [];

    public function __construct($file_path)
    {
        $this->raw_config = Yaml::parse(file_get_contents($file_path));
    }

    /**
     * Convert the literal config from the yaml document into a more
     * regular intermediate representation
     */
    public function parse_intermediate_representation()
    {
        $this->intermediate_rep = [
            'base_quantities'    => [],
            'derived_quantities' => [],
        ];

        foreach (array_keys($this->intermediate_rep) as $type) {
            foreach ($this->raw_config[$type] as $quantity) {

                // Generate a new skeleton quantity
                $quantity_ir = $this->get_empty_quantity_intermediate_representation();

                // Copy over the name of the quantity
                $quantity_ir['name'] = $quantity['name'];

                // Normalize the unit names and metric prefix patterns for the
                // SI unit
                $si_unit = (array_key_exists('names', $quantity['si_unit'])) ?
                    $quantity['si_unit'] :
                    ['names' => $quantity['si_unit']];
                $names_and_patterns = $this->normalize_names_and_patterns($si_unit);
                $quantity_ir['si_unit']['names'] = $names_and_patterns['names'];
                $quantity_ir['si_unit']['metric_prefixes']['patterns'] = $names_and_patterns['patterns'];

                // If the metric_prefixes -> base_metric_unit_scaling_factor value is present, copy it over.
                if (array_key_exists('metric_prefixes', $quantity['si_unit']) && array_key_exists('base_metric_unit_scaling_factor', $quantity['si_unit']['metric_prefixes'])) {
                    $quantity_ir['si_unit']['metric_prefixes']['base_metric_unit_scaling_factor'] = $quantity['si_unit']['metric_prefixes']['base_metric_unit_scaling_factor'];
                }

                // Normalize each additional unit for this quantity
                $additional_units = (array_key_exists('additional_units', $quantity)) ?
                    $quantity['additional_units'] :
                    [];
                $quantity_ir['additional_units'] = $this->normalize_additional_units($additional_units);

                // Determine whether any of the units in this quantity require
                // the ability to handle metric prefix expansion
                $quantity_ir['has_metric_prefixes'] = $this->quantity_needs_metric_prefixes($quantity_ir);

                $this->intermediate_rep[$type][] = $quantity_ir;
            }
        }
    }

    public function get_intermediate_representation()
    {
        return $this->intermediate_rep;
    }

    protected function get_empty_quantity_intermediate_representation()
    {
        return [
            'name' => '',
            'has_metric_prefixes' => false,
            'si_unit' => [
                'names' => [],
                'metric_prefixes' => [
                    'patterns' => [],
                    'base_metric_unit_scaling_factor' => 1
                ],
            ],
            'additional_units' => [],
        ];
    }

    protected function get_empty_additional_unit_intermediate_representation($with_conversion_factor)
    {
        $rep = [
            'names' => [],
            'metric_prefixes' => [
                'patterns' => [],
                'base_metric_unit_scaling_factor' => 1
            ],
        ];
        if ($with_conversion_factor) {
            $rep['conversion_factor'] = 1;
        } else {
            $rep['from_si_unit_expression'] = '';
            $rep['to_si_unit_expression']   = '';
        }
        return $rep;
    }

    /**
     * the "additional_units" field in the configuration file can have a very
     * terse string form.  Expand that form into the equivalent optional object
     * form, and then parse that form into the intermediate representation.
     */
    protected function normalize_additional_units($additional_units)
    {
        $normalized_units = [];
        foreach ($additional_units as $unit_definition) {

            // Convert the compact string form into an equivalent object, if
            // necessary
            if (is_string($unit_definition)) {
                list($expression, $names) = explode('=', $unit_definition, 2);
                $unit_definition = [
                    'names'      => $this->explode_trim(',', $names),
                    'expression' => trim($expression),
                ];
            }

            // If the conversion expression has no variable "x", it's a scaling
            // factor with x* implied.  Copy it over.
            if (!preg_match('/x/', $unit_definition['expression'])) {
                $unit_ir = $this->get_empty_additional_unit_intermediate_representation(true);
                $unit_ir['conversion_factor'] = $unit_definition['expression'];
            } else {
                // Else, the conversion expression is in terms of "x"
                $unit_ir = $this->get_empty_additional_unit_intermediate_representation(false);

                // Copy over the conversion expression, and generate its inverse
                $unit_ir['from_si_unit_expression'] = $unit_definition['expression'];
                $unit_ir['to_si_unit_expression'] = '"TODO - do the CAS and get an inverse expression"'; # TODO - do the CAS and get an inverse function

                // "PHP-ify" the functions - any variables in expressions like 'x' need to be '$x'
                $unit_ir['from_si_unit_expression'] = preg_replace('/([^\$]*)x/', '${1}$x', $unit_ir['from_si_unit_expression']);
                $unit_ir['to_si_unit_expression'] = preg_replace('/([^\$]*)x/', '${1}$x', $unit_ir['to_si_unit_expression']);
            }

            // Normalize the unit names and metric prefix patterns
            $names_and_patterns = $this->normalize_names_and_patterns($unit_definition);
            $unit_ir['names'] = $names_and_patterns['names'];
            $unit_ir['metric_prefixes']['patterns'] = $names_and_patterns['patterns'];

            // If the metric_prefixes -> base_metric_unit_scaling_factor value is present, copy it over.
            if (array_key_exists('metric_prefixes', $unit_definition) && array_key_exists('base_metric_unit_scaling_factor', $unit_definition['metric_prefixes'])) {
                $unit_ir['metric_prefixes']['base_metric_unit_scaling_factor'] = $unit_definition['metric_prefixes']['base_metric_unit_scaling_factor'];
            }

            $normalized_units[] = $unit_ir;
        }

        return $normalized_units;
    }

    /**
     * Given a target element with a "names" and optional "metric_prefixes" elements
     * Return a array with a "names" and "patterns" pair of keys, that properly
     * sort the metric prefix token having elements from the static names.
     */
    protected function normalize_names_and_patterns($target)
    {
        $names = [];
        $patterns = [];

        // If any of the names have SI unit prefix replacement symbols, then
        // add it to a running list of prefixed units, and strip that replacement
        // symbol from the name
        foreach ($target['names'] as $name) {
            if (preg_match('/%{.*}/', $name)) {
                $patterns[] = $name;
                $names[] = preg_replace('/%{.*}/', '', $name);
            } else {
                $names[] = $name;
            }
        }

        // Include any pre-existing metric prefix patterns
        if (array_key_exists('metric_prefixes', $target)) {
            $patterns = $patterns + $target['metric_prefixes']['patterns'];
        }

        return [
            'names'    => array_unique($names),
            'patterns' => array_unique($patterns),
        ];
    }

    /**
     * For a given physical quantity intermediate representation, do the
     * si_unit or any of the additional units require the ability to
     * expand metric prefix tokens?
     */
    protected function quantity_needs_metric_prefixes($quantity_ir)
    {
        if ($quantity_ir['si_unit']['metric_prefixes']['patterns'] !== []) {
            return true;
        }
        foreach ($quantity_ir['additional_units'] as $unit_definition) {
            if ($unit_definition['metric_prefixes']['patterns'] !== []) {
                return true;
            }
        }
        return false;
    }

    /**
     * convenience combination of explode and trim.
     */
    protected function explode_trim($explode_on, $string)
    {
        $elements = explode($explode_on, $string);
        return array_map('trim', $elements);
    }
}
