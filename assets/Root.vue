<template>
  <TheNavigation />

  <div class="container mb-5">
    <router-view></router-view>
  </div>
</template>

<script>
import SummaryGenerator from "./components/SummaryGenerator.vue";
import TheNavigation from "./components/TheNavigation";

export default {
  name: 'Root',
  components: {
    TheNavigation,
    SummaryGenerator
  },
}
</script>
