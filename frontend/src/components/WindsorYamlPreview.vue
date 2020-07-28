<template>
  <div class="relative">
    <pre
      :class="{
        'h-72': isLoadingYaml
      }"
      class="px-6 py-4 mt-0 overflow-y-auto max-h-96 language-yaml line-numbers rounded-b-md"><code class="p-0 language-yaml" v-html="fieldYaml"></code></pre>
    <div v-if="!isLoadingYaml"
      class="absolute top-0 right-0 p-2">
      <button
        title="Copy to clipboard"
        @click="copyToClipboard(fieldYaml)"
        type="button"
        class="inline-flex items-center justify-center p-1 bg-transparent rounded-sm hover:bg-gray-600 focus:outline-none focus:border-gray-700 focus:shadow-outline-gray">
        <svg
          class="inline-block w-5 h-5 text-gray-400"
          fill="currentColor" viewBox="0 0 20 20"><path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"></path><path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"></path></svg>
      </button>
    </div>
    <spinner v-if="isLoadingYaml && !hasError" />
    <div class="absolute inset-0 flex items-center justify-center" v-if="hasError">
      <div class="max-w-sm px-4 py-2 mx-auto text-center text-white bg-red-500 rounded-sm shadow-lg">
        Whoops sorry, something went wrong.
      </div>
    </div>
  </div>
</template>

<script>
import { useGlobalState } from "./../store";
import { copyToClipboard } from "./../utils/clipboard";
import { toRefs, markRaw, onMounted, ref, watch } from 'vue';
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
    const { fieldKey } = toRefs(props)
    let { fieldYaml, isLoadingYaml, hasError } = useLoadYaml(fieldKey)
    let cmTextarea = ref(null)
    let cm = ref(null)

    watch(fieldYaml, (newVal) => {
      window.Prism.highlightAll()
    }, { deep: true })

    onMounted(() => {
      setTimeout(() => {
        window.Prism.highlightAll()
      }, 10);
    })
    return {
      cm,
      cmTextarea,
      isLoadingYaml,
      hasError,
      fieldYaml,
      copyToClipboard,
    }
  }
}
</script>

<style>
</style>
