<template>
  <div class="relative">
    <pre class="px-6 py-4 mt-0 overflow-y-auto max-h-96 rounded-b-md"><code class="p-0 language-yaml" v-html="fieldYaml"></code></pre>
    <spinner v-if="isLoadingYaml" />
  </div>
</template>

<script>
import { useGlobalState } from "./../store";
import { toRefs, markRaw, computed, onMounted, ref, watch } from 'vue';
import { useLoadYaml } from "./useLoadYaml";
import Spinner from "./Spinner.vue";

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
      window.Prism.highlightAll()
    }, { deep: true })
    onMounted(async () => {
      window.Prism.highlightAll()
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

<style>
</style>
