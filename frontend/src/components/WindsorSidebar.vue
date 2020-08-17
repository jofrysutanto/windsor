<template>
  <div class="fixed top-0 right-0 z-10 h-screen pt-8 bg-white border-l border-acf-border w-72">
    <div class="flex flex-col h-full">
      <!-- Sidebar Top -->
      <div>
        <div class="flex items-center px-4 border-b border-acf-border h-acf-header-height">
          <h2 class="text-sm font-medium text-gray-700">
            Settings
          </h2>
          <div class="ml-auto">
            <a href="https://windsor-docs.netlify.app/configurations.html#ui"
              target="_blank"
              class="flex items-center w-full text-gray-400 transition duration-150 ease-in-out hover:text-gray-600">
              <div class="mr-1">Documentation</div>
              <svg class="flex-shrink-0 inline-block w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path></svg>
            </a>
          </div>
        </div>
        <div class="flex flex-col transition duration-150 ease-in-out divide-y border-acf-border divide-y-gray-200">
          <div class="flex p-4">
            <div class="flex-grow">
              <label for="location" class="block text-sm font-medium leading-5 text-black">Indentation</label>
              <select id="location"
                v-model="indentation"
                @input="changeIndentation($event.target.value)"
                class="block w-full py-2 pl-3 pr-10 mt-1 text-base leading-6 border-gray-300 form-select focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5">
                <option v-for="opt in indentOptions"
                  :key="opt.value"
                  :value="opt.value"
                  v-html="opt.label"></option>
              </select>
            </div>
          </div>
          <div class="flex p-4 cursor-pointer group hover:bg-gray-50"
            @click="changeMode(!isModeCompact)">
            <div class="flex-shrink-0">
              <span role="checkbox"
                tabindex="0"
                :aria-checked="isModeCompact"
                @keyup.enter="changeMode(!isModeCompact)"
                class="relative inline-flex items-center justify-center flex-shrink-0 w-10 h-5 cursor-pointer group focus:outline-none">
                <!-- On: "bg-indigo-600", Off: "bg-gray-200" -->
                <span aria-hidden="true"
                  :class="isModeCompact ? 'bg-wp-blue' : 'bg-gray-200'"
                  class="absolute h-4 mx-auto transition-colors duration-200 ease-in-out bg-gray-200 rounded-full w-9"></span>
                <!-- On: "translate-x-5", Off: "translate-x-0" -->
                <span aria-hidden="true"
                  :class="isModeCompact ? 'translate-x-5' : 'translate-x-0'"
                  class="absolute left-0 inline-block w-5 h-5 transition-transform duration-200 ease-in-out transform translate-x-0 bg-white border rounded-full shadow border-acf-border group-focus:shadow-outline group-focus:border-blue-300"></span>
              </span>
            </div>
            <div class="pl-2">
              <h4 class="text-sm font-medium text-black transition duration-150 ease-in-out group-hover:text-wp-blue">
                Compact Mode
              </h4>
              <p class="mt-1 text-xs text-gray-600">
                Exclude empty field settings to generate smaller YAML files.
              </p>
            </div>
          </div>
          <template v-if="isShowingAllSettings">
          <div class="flex p-4 cursor-pointer group hover:bg-gray-50"
            @click="isFriendlyFilename = !isFriendlyFilename">
            <div class="flex-shrink-0">
              <span role="checkbox"
                tabindex="0"
                :aria-checked="isFriendlyFilename"
                @keyup.enter="isFriendlyFilename = !isFriendlyFilename"
                class="relative inline-flex items-center justify-center flex-shrink-0 w-10 h-5 cursor-pointer group focus:outline-none">
                <!-- On: "bg-indigo-600", Off: "bg-gray-200" -->
                <span aria-hidden="true"
                  :class="isFriendlyFilename ? 'bg-wp-blue' : 'bg-gray-200'"
                  class="absolute h-4 mx-auto transition-colors duration-200 ease-in-out bg-gray-200 rounded-full w-9"></span>
                <!-- On: "translate-x-5", Off: "translate-x-0" -->
                <span aria-hidden="true"
                  :class="isFriendlyFilename ? 'translate-x-5' : 'translate-x-0'"
                  class="absolute left-0 inline-block w-5 h-5 transition-transform duration-200 ease-in-out transform translate-x-0 bg-white border rounded-full shadow border-acf-border group-focus:shadow-outline group-focus:border-blue-300"></span>
              </span>
            </div>
            <div class="pl-2">
              <h4 class="text-sm font-medium text-black transition duration-150 ease-in-out group-hover:text-wp-blue">
                Rename YAML fields
              </h4>
              <p class="mt-1 text-xs text-gray-600">
                Generated YAML filenames are inferred from the title. When disabled, unique keys are used: <i>group_xxxxx.acf.yaml</i>.
              </p>
            </div>
          </div>
          <div class="flex p-4 cursor-pointer group hover:bg-gray-50"
            @click="shouldIncludeIndex = !shouldIncludeIndex">
            <div class="flex-shrink-0">
              <span role="checkbox"
                tabindex="0"
                :aria-checked="shouldIncludeIndex"
                @keyup.enter="shouldIncludeIndex = !shouldIncludeIndex"
                class="relative inline-flex items-center justify-center flex-shrink-0 w-10 h-5 cursor-pointer group focus:outline-none">
                <!-- On: "bg-indigo-600", Off: "bg-gray-200" -->
                <span aria-hidden="true"
                  :class="shouldIncludeIndex ? 'bg-wp-blue' : 'bg-gray-200'"
                  class="absolute h-4 mx-auto transition-colors duration-200 ease-in-out bg-gray-200 rounded-full w-9"></span>
                <!-- On: "translate-x-5", Off: "translate-x-0" -->
                <span aria-hidden="true"
                  :class="shouldIncludeIndex ? 'translate-x-5' : 'translate-x-0'"
                  class="absolute left-0 inline-block w-5 h-5 transition-transform duration-200 ease-in-out transform translate-x-0 bg-white border rounded-full shadow border-acf-border group-focus:shadow-outline group-focus:border-blue-300"></span>
              </span>
            </div>
            <div class="pl-2">
              <h4 class="text-sm font-medium text-black transition duration-150 ease-in-out group-hover:text-wp-blue">
                Generate Index YAML
              </h4>
              <p class="mt-1 text-xs text-gray-600">
                Include <code>index.yaml</code> which you can drop into <code>acf-yaml/</code> directly to auto-register fields via Windsor.
              </p>
            </div>
          </div>
          </template>
          <a
            href="javascript:;"
            class="flex justify-center px-4 py-2 text-gray-500 no-underline cursor-pointer group hover:text-wp-blue"
            @click="isShowingAllSettings = !isShowingAllSettings">
            <span v-if="!isShowingAllSettings">Show All Settings</span>
            <span v-else>Hide Extra Settings</span>
          </a>
        </div>
      </div>
      <!-- ./ Sidebar Top -->
      <div class="mt-auto">
        <div class="px-6 py-3 border-t border-acf-border">
          <button
            @click="onExport"
            type="button"
            class="block w-full button button-primary button-large">
            Export All
          </button>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import repository from "./../utils/repository";
import { useGlobalState } from "./../store";
import { toRefs, ref } from 'vue';
import fileSaver from 'file-saver';
export default {
  setup () {
    let { isModeCompact, changeMode, indentation, changeIndentation } = toRefs(useGlobalState())
    let isFriendlyFilename = ref(true)
    let shouldIncludeIndex = ref(true)
    let isShowingAllSettings = ref(false)
    let indentOptions = ref([
      { value: 2, label: '2 Spaces' },
      { value: 4, label: '4 Spaces' },
      { value: 6, label: '6 Spaces' },
      { value: 8, label: '8 Spaces' },
    ])
    return {
      isModeCompact,
      indentation,
      indentOptions,
      isFriendlyFilename,
      shouldIncludeIndex,
      isShowingAllSettings,
      changeMode,
      changeIndentation,
    }
  },
  methods: {
    async onExport () {
      let mode = this.isModeCompact ? 'compact' : 'full'
      let indent = this.indentation
      let { fields } = await repository.export({
        mode,
        indent,
        include_index: this.shouldIncludeIndex,
        rename_files: this.isFriendlyFilename,
      })
      var zip = new window.JSZip()
      fields.forEach(({ filename, content }) => {
        zip.file(filename, content);
      });
      let blob = await zip.generateAsync({ type:"blob" })
      fileSaver.saveAs(blob, 'acf-yaml.zip')
    }
  }
}
</script>
