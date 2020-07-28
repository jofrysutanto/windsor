import { createGlobalState } from '@vueuse/core'
import { ref, watch } from 'vue'
import repository from './utils/repository'

export const useGlobalState = createGlobalState(
  () => {
    let isModeCompact = ref(true)
    let indentation = ref(2)
    let yamlFields = ref({})
    let activePreview = ref(null)
    let isLoadingField = ref(null)

    const changeMode = (mode) => {
      isModeCompact.value = mode
    }

    const changeIndentation = (newVal) => {
      indentation.value = newVal
    }

    const loadField = async (key) => {
      if (typeof yamlFields.value[key] !== 'undefined') {
        return
      }
      isLoadingField.value = key
      let { yaml } = await repository.fetchSingle({
        key,
        indent: indentation.value,
        mode: (isModeCompact.value === true) ? 'compact' : 'full'
      })
      yamlFields.value[key] = yaml
      isLoadingField.value = null
    }

    watch([isModeCompact, indentation], () => {
      yamlFields.value = {}
      if (activePreview.value) {
        loadField(activePreview.value)
      }
    })

    return {
      isModeCompact,
      indentation,
      isLoadingField,
      yamlFields,
      activePreview,
      changeMode,
      changeIndentation,
      loadField,
    }
  },
)
