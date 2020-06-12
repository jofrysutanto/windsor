<?php
class ChangeInstructionsToFoo
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
        $acf['instructions'] = 'foo';
        return $acf;
    }
}
