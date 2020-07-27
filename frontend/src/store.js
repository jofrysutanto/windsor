import { createGlobalState } from '@vueuse/core'
import { ref, watch } from 'vue'
import repository from './utils/repository'

export const useGlobalState = createGlobalState(
  () => {
    let isModeCompact = ref(false)
    let loadedFields = ref({})
    let yamlFields = ref({})
    let activePreview = ref(null)

    const changeMode = (mode) => {
      isModeCompact.value = mode
    }

    const loadField = async (key) => {
      if (typeof loadedFields.value[key] !== 'undefined') {
        return
      }
      let { field_group, yaml } = await repository.fetchSingle({
        key,
        mode: (isModeCompact.value === true) ? 'compact' : 'full'
      })
      loadedFields.value[key] = field_group
      yamlFields.value[key] = yaml.trim()
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
      loadedFields,
      yamlFields,
      activePreview,
      changeMode,
      loadField,
    }
  },
)
