<?php
namespace Windsor\Rules;

use Tightenco\Collect\Support\Arr;

class GroupLocationRule
{

    /**
     * Process this rule
     *
     * @param FieldGroup $group
     * @param string $key
     * @param array $acf
     * @return array
     */
    public function process($group, $key, array $acf): array
    {
        $acf = $this->checkLocation($group, $acf);
        $acf = $this->checkCollapse($group, $acf);

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
        $collapsed = Arr::get($acf, 'collapsed');
        if (!$collapsed) {
            return $acf;
        }
        Arr::set($acf, 'collapsed', $group->makekey($collapsed));
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
        $locations = Arr::get($acf, 'location', false);
        if ($locations === false) {
            return $acf;
        }
        if (is_null($locations)) {
            $acf['location'] = $this->getHiddenLocationRule();
        }
        return $acf;
    }

    /**
     * Retrieve location rule which ensures the field is not shown
     *
     * @return array
     */
    public function getHiddenLocationRule()
    {
        return [
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
}
