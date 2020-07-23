<template>
  <div class="flex w-full min-h-wp-body">
    <div class="relative order-2 w-72">
      <windsor-sidebar />
    </div>
    <div class="flex-grow order-1">
      <windsor-header />
      <div class="max-w-4xl px-6 py-4 mx-auto">
        <windsor-field-groups
          :items="fields" />
      </div>
    </div>
  </div>
</template>

<script>
import repository from './../utils/repository'
import WindsorHeader from "./WindsorHeader.vue";
import WindsorSidebar from "./WindsorSidebar.vue";
import WindsorFieldGroups from "./WindsorFieldGroups.vue";
export default {
  setup () {
  },
  data () {
    return {
      fields: [],
    }
  },
  components: {
    WindsorHeader,
    WindsorSidebar,
    WindsorFieldGroups,
  },
  methods: {
  },
  async mounted () {
    this.fields = await repository.fetchAll()
  }
}
</script>
