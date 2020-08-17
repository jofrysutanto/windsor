<template>
  <div>
    <div class="flex items-center justify-between px-4 mb-4 bg-white border-b border-acf-border h-acf-header-height">
      <h1 class="my-0 text-base font-semibold text-gray-800">
        <span class="font-normal text-gray-600">ACF Windsor -</span> YAML Exporter
      </h1>
      <div>
        <button type="button"
          @click="showTip = !showTip"
          class="inline-flex items-center justify-center bg-yellow-100 rounded-full w-7 h-7 focus:shadow-outline-orange focus:outline-none">
          <svg
            class="w-6 h-6 text-yellow-300"
            fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </button>
      </div>
    </div>
    <div class="px-4 pt-4 mb-4 -mt-4"
      v-if="showTip">
      <div class="relative flex items-center max-w-4xl p-4 mx-auto text-yellow-700 rounded shadow-md bg-yellow-50">
        <div class="absolute top-0 right-0 p-2">
          <button type="button"
            @click="showTip = !showTip"
            class="inline-flex items-center justify-center p-1 transition duration-150 ease-in-out rounded-full hover:bg-yellow-100 focus:shadow-outline-orange focus:outline-none">
            <svg
              class="w-4 h-4 text-yellow-300"
              fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
          </button>
        </div>
        <div class="flex-shrink-0">
          <svg
            class="w-8 h-8 mr-4 text-yellow-300"
            fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
        </div>
        <div>
          It is recommended that you disable this page when you no longer need access to this tool. <br>
          For help on how to safely disable this page, visit our documentation page.
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data () {
    return {
      showTip: false
    }
  }
}
</script>
