<template>
  <div class="flex w-full min-h-wp-body">
    <div class="relative order-2 w-72">
      <windsor-sidebar />
    </div>
    <div class="flex-grow order-1">
      <windsor-header />
      <div class="max-w-4xl px-6 py-4 mx-auto">
        <div
          v-if="!hasError">
          <windsor-field-groups
            :items="fields" />
          <div v-if="fieldsUnavailable.length > 0"
            class="mt-8">
            <div class="bg-white border border-yellow-300">
              <div class="flex items-center justify-between px-4 py-3">
                <div class="">
                  {{ fieldsUnavailable.length }} field(s) are not shown because they are not available for exporting.
                </div>
                <div class="flex-shrink-0 pl-4">
                  <button type="button"
                    @click="showUnavailable = !showUnavailable"
                    class="p-1 transition duration-150 ease-in-out rounded-full hover:bg-gray-50 focus:shadow-outline-gray focus:outline-none">
                    <svg
                      v-if="!showUnavailable"
                      class="w-5 h-5 text-gray-400"
                      fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path></svg>
                    <svg
                      v-else
                      class="w-5 h-5 text-gray-400"
                      fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path></svg>
                  </button>
                </div>
              </div>
              <div class="p-4 bg-gray-50" v-if="showUnavailable">
                <div class="flex flex-wrap -mx-2">
                  <div class="w-1/2 px-2 mb-2 lg:w-1/3"
                    :key="field.key"
                    v-for="field in fieldsUnavailable">
                    <div class="px-4 py-3 bg-white border">
                      <h4 class="text-sm" v-html="field.title"></h4>
                      <div class="flex">
                        <div
                          style="font-size: 11px;"
                          class="px-3 py-0.5 mt-2 text-xs text-yellow-500 bg-yellow-100 rounded-full" v-if="field.local === 'php'">
                          Local PHP
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <windsor-error v-else />
      </div>
    </div>
  </div>
</template>

<script>
import repository from './../utils/repository'
import WindsorHeader from "./WindsorHeader.vue";
import WindsorSidebar from "./WindsorSidebar.vue";
import WindsorFieldGroups from "./WindsorFieldGroups.vue";
import WindsorError from "./WindsorError.vue";
export default {
  setup () {
  },
  data () {
    return {
      fields: [],
      fieldsUnavailable: [],
      showUnavailable: false,
      hasError: false,
      ui: _acf_windsor.acf.ui
    }
  },
  components: {
    WindsorHeader,
    WindsorSidebar,
    WindsorFieldGroups,
    WindsorError,
  },
  computed: {
  },
  async mounted () {
    try {
      const { fields, fields_unavailable } = await repository.fetchAll()
      this.fields = fields
      this.fieldsUnavailable = fields_unavailable
    } catch (error) {
      console.error(error)
      this.hasError = error
    }
  }
}
</script>
