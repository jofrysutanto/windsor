<?php
namespace Windsor\Rules;

use Windsor\Rules\Utilities\TransformConditionalLogic;

class FieldConditionRule
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
        $prefixer = new TransformConditionalLogic();
        return $prefixer->run($acf, function ($keyReference) use ($group) {
            return $group->makeKey($keyReference);
        });
    }
}
