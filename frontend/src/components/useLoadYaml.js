import { onMounted, computed, ref, toRefs } from 'vue'
import { useGlobalState } from "./../store";

export const useLoadYaml = (fieldKey) => {
  const { yamlFields } = toRefs(useGlobalState())
  const { loadField } = useGlobalState()
  let isLoadingYaml = ref(false)
  let hasError = ref(false)
  const fieldYaml = computed(() => {
    return yamlFields.value[fieldKey.value]
  })
  onMounted(async () => {
    isLoadingYaml.value = true
    try {
      await loadField(fieldKey.value)
    } catch (error) {
      hasError.value = true
    }
    isLoadingYaml.value = false
  })
  return {
    isLoadingYaml,
    hasError,
    fieldYaml
  }
}
