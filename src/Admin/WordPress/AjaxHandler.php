<?php
namespace Windsor\Admin\WordPress;

use Windsor\Support\Singleton;
use Windsor\Admin\Exporter\YamlComposer;
use Tightenco\Collect\Support\Collection;
use Windsor\Admin\Exporter\FieldGroupsStore;
use Windsor\Admin\Exporter\FluentFieldGroup;

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
            $collection->push(array_merge($result, [
                'count' => acf_get_field_count($result)
            ]));
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
        $mode = isset($_POST['mode']) ? $_POST['mode'] : 'full';
        if (!$key) {
            return wp_send_json_error();
        }
        $result = $this->loadFieldGroupForExport($key);
        $yaml = new YamlComposer($result, $mode);
        // sleep(2);
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
    public function loadFieldGroupForExport($key)
    {
        $field_group = acf_get_field_group($key);
        $field_group['fields'] = acf_get_fields($field_group);
        return acf_prepare_field_group_for_export($field_group);
    }
}
