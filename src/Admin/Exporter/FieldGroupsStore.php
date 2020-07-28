<?php
namespace Windsor\Admin\Exporter;

use Tightenco\Collect\Support\Arr;

class FieldGroupsStore
{

    /**
     * Query available field groups for exporting
     *
     * @return array
     */
    public function query()
    {
        $groups = acf_get_field_groups();
        return array_values(array_filter($groups, function ($group) {
            return !in_array(Arr::get($group, 'local'), ['php']);
        }));
    }

    /**
     * Query field groups NOT available for exporting
     *
     * @return array
     */
    public function queryNotExportable()
    {
        $groups = acf_get_field_groups();
        return array_values(array_filter($groups, function ($group) {
            return in_array(Arr::get($group, 'local'), ['php']);
        }));
    }
}
