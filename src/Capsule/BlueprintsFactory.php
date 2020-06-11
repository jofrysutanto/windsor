<?php
namespace Windsor\Capsule;

use Windsor\Support\Singleton;
use Tightenco\Collect\Support\Arr;

class BlueprintsFactory
{
    use Singleton;

    protected $blueprints = [];

    /**
     * Store blueprint configuration
     *
     * @param string $key
     * @param array $data
     * @return $this
     */
    public function store($key, array $data = [])
    {
        $this->blueprints[$key] = $data;
        return $this;
    }

    /**
     * Attempt to merge all fields from blueprints if available
     *
     * @param array $yamlFields
     * @return array
     */
    public function mergeBlueprints(array $yamlFields)
    {
        $result = [];
        foreach ($yamlFields as $key => $fields) {
            // Bail early if not 'blueprint' type field
            if (Arr::get($fields, 'type') !== 'blueprint') {
                $result[$key] = $fields;
                continue;
            }
            $result = array_merge($result, $this->unpackBlueprint($key, $fields));
        }
        return $result;
    }

    /**
     * Unpack given field blueprint
     *
     * @param string $key
     * @param array $fields
     * @return array
     */
    protected function unpackBlueprint($key, array $fields = [])
    {
        $source = Arr::get($fields, 'source');
        $blueprint = Arr::get($this->blueprints, $source);
        if (!$blueprint) {
            return [
                $key => $this->reportMissingBlueprint($source)
            ];
        }
        $cloner = new BlueprintBuilder($blueprint, $key, $fields);
        return $cloner->makeCopy();
    }

    /**
     * Create missing blueprint block message field
     *
     * @param string $name
     * @return array
     */
    protected function reportMissingBlueprint($name)
    {
        $styles = [
            'background-color: #ff7f50',
            'padding: 10px 15px',
            'color: #fff',
            'border-radius: 4px',
        ];
        $message = sprintf('
            <div style="%s">
                Missing blueprint: <strong>%s</strong>
            </div>
        ', implode('; ', $styles), $name);
        return [
            'type' => 'message',
            'message' => $message,
            'esc_html' => false
        ];
    }
}
