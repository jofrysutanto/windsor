<template>
  <div class="transition-all duration-150 ease-in-out"
    :class="{
      'my-4 -mx-2': isActive,
      'my-0 mx-0': isActive,
    }">
    <div>
      <a
        href="javascript:;"
        @click="activePreview = (isActive) ? null : field.key"
        class="block transition duration-150 ease-in-out hover:bg-gray-50 focus:outline-none focus:bg-gray-50 focus:shadow-none">
        <div class="flex items-center px-2 py-3">
          <div class="flex items-center flex-1 min-w-0">
            <div class="flex-1 min-w-0 px-4 md:grid md:grid-cols-2 md:gap-4">
              <div>
                <div class="text-sm font-medium leading-5 truncate text-wp-blue">
                  {{ field.title }}
                </div>
                <div class="flex items-center mt-1 text-sm leading-5 text-gray-500">
                  <span class="text-xs italic truncate">
                    {{ field.key }}
                  </span>
                </div>
              </div>
              <div class="self-center hidden text-right md:block">
                <div class="ml-auto">
                  <div class="inline-flex items-center justify-center w-6 h-6 px-3 py-1 text-xs leading-5 text-center text-gray-800 bg-gray-200 rounded-full">
                    {{ field.count }}
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="pr-2">
            <svg class="w-5 h-5 text-gray-400 transition duration-150 transform"
              :class="{
                'rotate-90': isActive,
                'rotate-0': !isActive,
              }"
              viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </a>
    </div>
    <!-- Expand YAML -->
    <windsor-yaml-preview
      v-if="isActive"
      :field-key="field.key" />
  </div>
</template>

<script>
import { useGlobalState } from "./../store";
import WindsorYamlPreview from "./WindsorYamlPreview.vue";
import { toRefs, computed } from 'vue';
export default {
  props: ['field'],
  setup (props) {
    const { activePreview } = toRefs(useGlobalState())
    const { field } = toRefs(props)
    const isActive = computed(() => {
      return field.value.key === activePreview.value
    })
    return {
      activePreview,
      isActive
    }
  },
  components: {
    WindsorYamlPreview,
  }
}
</script>
