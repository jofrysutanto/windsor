<?php
namespace Windsor\Admin\WordPress;

use Windsor\Support\Singleton;
use Tightenco\Collect\Support\Arr;
use Windsor\Admin\Exporter\FieldsPacker;
use Windsor\Admin\Exporter\YamlComposer;
use Tightenco\Collect\Support\Collection;
use Windsor\Admin\Exporter\FieldGroupsStore;

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

        add_action('wp_ajax_nopriv_windsor_export', [$this, 'ajaxExport']);
        add_action('wp_ajax_windsor_export', [$this, 'ajaxExport']);
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
            'field_groups' => $collection->toArray(),
            'field_groups_unavailable' => $this->store->queryNotExportable(),
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
        $indent = isset($_POST['indent']) ? $_POST['indent'] : 2;
        if (!$key) {
            return wp_send_json_error();
        }
        $yaml = new YamlComposer($indent);
        $packer = new FieldsPacker($this->loadFieldGroupForExport($key));
        $result = $packer
            ->setMode($mode)
            ->pack();
        return wp_send_json([
            'yaml' => $yaml->generate($result),
        ]);
    }

    /**
     * Handle AJAX request to export available field groups
     *
     * @return mixed
     */
    public function ajaxExport()
    {
        $mode = isset($_POST['mode']) ? $_POST['mode'] : 'full';
        $indent = isset($_POST['indent']) ? $_POST['indent'] : 2;
        $hasIndex = isset($_POST['include_index']) ? $_POST['include_index'] === 'true' : true;
        $shouldRename = isset($_POST['rename_files']) ? $_POST['rename_files'] === 'true' : true;
        $groups = $this->store->query();
        $result = [];
        $filenames = [];
        foreach ($groups as $group) {
            $packer = new FieldsPacker(
                $this->loadFieldGroupForExport(Arr::get($group, 'key'))
            );
            $fieldSettings = $packer
                ->setMode($mode)
                ->pack();
            $yaml = (new YamlComposer($indent))
                ->generate($fieldSettings);
            $filename = sprintf(
                "%s.acf.yaml",
                ($shouldRename ? sanitize_title_with_dashes($group['title']) : $group['key'])
            );
            $filenames[] = $filename;
            $result[] = [
                'filename' => $filename,
                'content' => $yaml
            ];
        }
        if ($hasIndex) {
            $indexContent = [
                'fields'     => [],
                'blueprints' => [],
                'blocks'     => [],
                'pages'      => $filenames,
            ];
            $result[] = [
                'filename' => 'index.yaml',
                'content' => (new YamlComposer($indent))->generate($indexContent)
            ];
        }
        return wp_send_json([
            'fields' => $result
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
        // Only work with raw, unmodified field structure
        $filters = acf_disable_filters();

        $field_group = acf_get_field_group($key);
        $field_group['fields'] = acf_get_fields($field_group);
        $result = acf_prepare_field_group_for_export($field_group);

        acf_enable_filters($filters);
        return $result;
    }
}
