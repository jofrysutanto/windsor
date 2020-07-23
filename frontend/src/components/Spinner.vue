<template>
  <div class="absolute top-0 left-0 flex items-center justify-center w-full h-full windsor-spinner-wrap">
    <div v-if="overlay"
      class="absolute w-full h-full bg-white opacity-75 cursor-wait">
    </div>
    <div class="windsor-spinner"></div>
  </div>
</template>

<script>
export default {
  props: {
    overlay: [Boolean],
  },
}
</script>

<style scoped>
.windsor-spinner {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50px;
  height: 50px;
  margin-bottom: 50px;
  border: 1px solid rgba(202, 202, 202, 0.6);
  border-radius: 50%;
  border-top-color: rgb(31, 36, 48);
  animation: spin 0.6s linear infinite;
}
@keyframes spin {
  to { transform: rotate(360deg); }
}
</style>
