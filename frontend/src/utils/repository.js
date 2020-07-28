import ajax from "./ajax";
export default new class {

  async fetchAll () {
    let { data } = await ajax.post({
      action: 'windsor_load_fields',
    })
    return {
      fields: data.field_groups,
      fields_unavailable: data.field_groups_unavailable,
    }
  }

  async fetchSingle ({key, mode, indent = 2}) {
    let { data } = await ajax.post({
        action: 'windsor_load_single',
        indent,
        key,
        mode
      })
    let { field_group, yaml } = data
    return { field_group, yaml }
  }

  async export ({ mode, include_index = true, rename_files = true, indent = 2 }) {
    let { data } = await ajax.post({
      action: 'windsor_export',
      indent,
      mode,
      include_index,
      rename_files,
    })
    let { fields } = data
    return { fields }
  }

}
