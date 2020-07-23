<template>
  <div class="fixed top-0 right-0 z-10 h-screen pt-8 bg-white border-l border-gray-200 w-72">
    <div class="flex flex-col h-full">
      <!-- Sidebar Top -->
      <div>
        <div class="flex items-center h-12 px-4 border-b border-gray-200">
          <h2 class="text-sm font-medium text-gray-700">
            Settings
          </h2>
          <div class="ml-auto">
            <a href="https://windsor-docs.netlify.app/"
              class="flex items-center w-full text-gray-400 transition duration-150 ease-in-out hover:text-gray-600">
              <div class="mr-1">Documentation</div>
              <svg class="flex-shrink-0 inline-block w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M11 3a1 1 0 100 2h2.586l-6.293 6.293a1 1 0 101.414 1.414L15 6.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-5z"></path><path d="M5 5a2 2 0 00-2 2v8a2 2 0 002 2h8a2 2 0 002-2v-3a1 1 0 10-2 0v3H5V7h3a1 1 0 000-2H5z"></path></svg>
            </a>
          </div>
        </div>
        <div class="flex flex-col transition duration-150 ease-in-out border-b border-gray-200 divide-y divide-y-gray-200 hover:bg-gray-50">
          <div class="flex p-4 cursor-pointer group"
            @click="changeMode(!isModeCompact)">
            <div class="flex-shrink-0">
              <span role="checkbox"
                tabindex="0"
                :aria-checked="isModeCompact"
                @keyup.enter="$emit('mode', {mode: !isModeCompact})"
                class="relative inline-flex items-center justify-center flex-shrink-0 w-10 h-5 cursor-pointer group focus:outline-none">
                <!-- On: "bg-indigo-600", Off: "bg-gray-200" -->
                <span aria-hidden="true"
                  :class="isModeCompact ? 'bg-wp-blue' : 'bg-gray-200'"
                  class="absolute h-4 mx-auto transition-colors duration-200 ease-in-out bg-gray-200 rounded-full w-9"></span>
                <!-- On: "translate-x-5", Off: "translate-x-0" -->
                <span aria-hidden="true"
                  :class="isModeCompact ? 'translate-x-5' : 'translate-x-0'"
                  class="absolute left-0 inline-block w-5 h-5 transition-transform duration-200 ease-in-out transform translate-x-0 bg-white border border-gray-200 rounded-full shadow group-focus:shadow-outline group-focus:border-blue-300"></span>
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
        </div>
      </div>
      <!-- ./ Sidebar Top -->
      <div class="mt-auto">
        <div class="px-6 py-3 border-t border-gray-200">
          <button
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
import { useGlobalState } from "./../store";
import { toRefs } from 'vue';
export default {
  setup () {
    let { isModeCompact, changeMode } = toRefs(useGlobalState())
    return {
      isModeCompact,
      changeMode
    }
  }
}
</script>
