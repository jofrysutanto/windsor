<template>
  <div class="relative">
    <textarea ref="cmTextarea"
      v-model="fieldYaml"
      class="w-full"></textarea>
    <spinner v-if="isLoadingYaml" />
  </div>
</template>

<script>
import { useGlobalState } from "./../store";
import { toRefs, markRaw, computed, onMounted, ref, watch } from 'vue';
import { useLoadYaml } from "./useLoadYaml";
import Spinner from "./Spinner.vue";

import 'codemirror/lib/codemirror.js';
import 'codemirror/mode/yaml/yaml'
import 'codemirror/lib/codemirror.css'
import 'codemirror/theme/ayu-mirage.css'

const cmConfig = {
  mode: 'yaml',
  lineNumbers: true,
  foldGutter: false,
  gutters: [],
  tabSize: 2,
  indentUnit: 2,
  lineWrapping: false,
  theme: 'ayu-mirage',
  readOnly: true,
}

export default {
  props: ['field-key'],
  data () {
    return {
      codemirror: null
    }
  },
  components: {
    Spinner,
  },
  setup (props) {
    const { isModeCompact } = useGlobalState()
    const { fieldKey } = toRefs(props)
    let { fieldYaml, isLoadingYaml } = useLoadYaml(fieldKey)
    let cmTextarea = ref(null)
    let cm = ref(null)
    watch(fieldYaml, (newVal) => {
      if (cm.value) {
        cm.value.setValue(newVal)
      }
    }, { deep: true })
    onMounted(async () => {
      cm.value = markRaw(CodeMirror.fromTextArea(cmTextarea.value, cmConfig))
    })
    return {
      isModeCompact,
      cm,
      cmTextarea,
      isLoadingYaml,
      fieldYaml
    }
  }
}
</script>
