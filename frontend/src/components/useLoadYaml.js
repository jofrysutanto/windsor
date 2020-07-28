import { onMounted, computed, ref, toRefs } from 'vue'
import { useGlobalState } from "./../store";

export const useLoadYaml = (fieldKey) => {
  const { yamlFields, isLoadingField } = toRefs(useGlobalState())
  const { loadField } = useGlobalState()
  let hasError = ref(false)
  const fieldYaml = computed(() => {
    return yamlFields.value[fieldKey.value]
  })
  onMounted(async () => {
    try {
      await loadField(fieldKey.value)
    } catch (error) {
      console.error(error)
      hasError.value = true
    }
  })
  let isLoadingYaml = computed(() => {
    return isLoadingField.value === fieldKey.value
  })
  return {
    isLoadingYaml,
    hasError,
    fieldYaml
  }
}
