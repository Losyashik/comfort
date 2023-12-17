<template>
  <top-bar></top-bar>
  <router-view id="body"></router-view>
  <right-bar></right-bar>
</template>
<script>
import TopBar from "@/components/navbars/TopBar.vue";
import RightBar from "@/components/navbars/RightBar.vue";
export default {
  components: {
    TopBar,
    RightBar,
  },
  data: function () {
    return {};
  },
};
</script>
<style lang="scss" src="./assets/styles/app.scss"></style>
