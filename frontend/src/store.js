import { createGlobalState } from '@vueuse/core'
import { ref, watch } from 'vue'
import repository from './utils/repository'

export const useGlobalState = createGlobalState(
  () => {
    let isModeCompact = ref(true)
    let loadedFields = ref({})
    let yamlFields = ref({})
    let activePreview = ref(null)
    let isLoadingField = ref(null)

    const changeMode = (mode) => {
      isModeCompact.value = mode
    }

    const loadField = async (key) => {
      if (typeof loadedFields.value[key] !== 'undefined') {
        return
      }
      isLoadingField.value = key
      let { field_group, yaml } = await repository.fetchSingle({
        key,
        mode: (isModeCompact.value === true) ? 'compact' : 'full'
      })
      loadedFields.value[key] = field_group
      yamlFields.value[key] = yaml.trim()
      isLoadingField.value = null
    }

    watch(isModeCompact, () => {
      yamlFields.value = {}
      loadedFields.value = {}
      if (activePreview.value) {
        loadField(activePreview.value)
      }
    })

    return {
      isModeCompact,
      isLoadingField,
      loadedFields,
      yamlFields,
      activePreview,
      changeMode,
      loadField,
    }
  },
)
