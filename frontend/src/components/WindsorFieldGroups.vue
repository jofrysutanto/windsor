<template>
  <div class="">
    <div class="my-0">
      <windsor-field-group-item
        :key="field.key"
        class="bg-white "
        :class="{
          'border-t border-gray-200': index > 0
        }"
        :field="field"
        v-for="(field, index) in items"/>
    </div>
  </div>
</template>

<script>
import WindsorFieldGroupItem from "./WindsorFieldGroupItem.vue";
export default {
  props: {
    items: {
      type: [Array]
    }
  },
  data () {
    return {
    }
  },
  components: {
    WindsorFieldGroupItem,
  },
  methods: {
  },
}
</script>
