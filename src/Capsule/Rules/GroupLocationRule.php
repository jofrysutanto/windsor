<?php

namespace AcfYaml\Capsule\Rules;

class GroupLocationRule
{
    /**
     * Process this rule
     *
     * @param FieldGroup  $group
     * @param string $key
     * @param array $acf
     * @return array
     */
    public function process($group, $key, array $acf): array
    {
        $acf = $this->checkCollapse($group, $acf);
        $acf = $this->checkLocation($group, $acf);

        return $acf;
    }

    /**
     * Check referred collapsible property for repeater of flexible content
     * to apply the right key namespace prefix
     *
     * @param FieldGroup $group
     * @param array $acf
     * @return array
     */
    protected function checkCollapse($group, $acf)
    {
        $collapsed = array_get($acf, 'collapsed');
        if (!$collapsed) {
            return $acf;
        }
        array_set($acf, 'collapsed', $group->makekey($collapsed));
        return $acf;
    }

    /**
     * Check and validate location value
     *
     * @param FieldGroup $group
     * @param array $acf
     * @return array
     */
    protected function checkLocation($group, $acf)
    {
        if (!isset($acf['location'])) {
            return $acf;
        }
        $locations = array_get($acf, 'location');
        if (is_null($locations)) {
            $acf['location'] = [
                [
                    [
                        'param'    => 'options_page',
                        'operator' => '==',
                        'value'    => 'acf-options-global-options'
                    ],
                    [
                        'param'    => 'options_page',
                        'operator' => '!=',
                        'value'    => 'acf-options-global-options'
                    ]
                ]
            ];
        }
        return $acf;
    }
}
