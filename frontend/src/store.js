import { createGlobalState } from '@vueuse/core'
import { ref } from 'vue'
import repository from './utils/repository'

export const useGlobalState = createGlobalState(
  () => {
    let loadedFields = ref({})
    let yamlFields = ref({})
    const loadField = async (key) => {
      if (typeof loadedFields.value[key] !== 'undefined') {
        return
      }
      let { field_group, yaml } = await repository.fetchSingle(key)
      loadedFields.value[key] = field_group
      yamlFields.value[key] = yaml.trim()
    }

    let isModeCompact = ref(false)
    const changeMode = (mode) => {
      isModeCompact.value = mode
    }

    return {
      isModeCompact,
      loadedFields,
      yamlFields,
      activePreview: null,
      changeMode,
      loadField,
    }
  },
)
