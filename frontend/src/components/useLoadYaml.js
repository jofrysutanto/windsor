import { onMounted, computed, ref } from 'vue'
import { useGlobalState } from "./../store";

export const useLoadYaml = (fieldKey) => {
  const { yamlFields, loadField } = useGlobalState()
  let isLoadingYaml = ref(false)
  const fieldYaml = computed(() => {
    return yamlFields[fieldKey.value]
  })
  onMounted(async () => {
    isLoadingYaml.value = true
    await loadField(fieldKey.value)
    isLoadingYaml.value = false
  })
  return {
    isLoadingYaml,
    fieldYaml
  }
}
