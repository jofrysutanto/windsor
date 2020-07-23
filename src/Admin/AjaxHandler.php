<?php
namespace Windsor\Admin;

use Windsor\Support\Singleton;
use Tightenco\Collect\Support\Collection;

class AjaxHandler
{
    use Singleton;

    /**
     * Data store for field groups
     *
     * @var \Windsor\Admin\FieldGroupsStore
     */
    protected $store;

    public function init()
    {
        $this->store = new FieldGroupsStore;
    }

    /**
     * Register WordPress AJAX handlers
     *
     * @return void
     */
    public function registerHandlers()
    {
        add_action('wp_ajax_nopriv_windsor_load_fields', [$this, 'ajaxLoadFields']);
        add_action('wp_ajax_windsor_load_fields', [$this, 'ajaxLoadFields']);

        add_action('wp_ajax_nopriv_windsor_load_single', [$this, 'ajaxLoadSingle']);
        add_action('wp_ajax_windsor_load_single', [$this, 'ajaxLoadSingle']);
    }

    /**
     * Handle AJAX request to load field groups
     *
     * @return mixed
     */
    public function ajaxLoadFields()
    {
        $results = $this->store->query();
        $collection = new Collection;
        foreach ($results as $result) {
            $collection->push(FluentFieldGroup::fromRawFieldGroup($result));
        }
        return wp_send_json([
            'field_groups' => $collection->toArray()
        ]);
    }

    /**
     * Handle AJAX request to load individual field group
     *
     * @return mixed
     */
    public function ajaxLoadSingle()
    {
        $key = isset($_POST['key']) ? $_POST['key'] : null;
        if (!$key) {
            return wp_send_json_error();
        }
        $result = $this->loadFieldGroupForExport($key);
        $yaml = new YamlComposer($result);
        return wp_send_json([
            'field_group' => $result,
            'yaml' => $yaml->generate(),
        ]);
    }

    /**
     * Load field group export configuration
     *
     * @param string $key
     * @return array
     */
    protected function loadFieldGroupForExport($key)
    {
        // load field group
        $field_group = acf_get_field_group($key);
        // load fields
        $field_group['fields'] = acf_get_fields($field_group);
        // prepare for export
        return acf_prepare_field_group_for_export($field_group);
    }
}
