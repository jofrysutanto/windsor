<template>
  <windsor/>
</template>

<script>
import Windsor from './components/Windsor.vue'

export default {
  name: 'App',
  components: {
    // 'wp-wrapper': () => import(/* webpackChunkName: "wp" */ './wp/WpWrapper.vue'),
    Windsor
  }
}
</script>
