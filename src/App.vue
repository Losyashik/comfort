<template>
  <div id="app">
    <router-view />
    <modal-window @close="close()" :obj="modal"></modal-window>
  </div>
</template>
<style src="@/assets/styles/variables.css"></style>
<script>
import { mapActions } from "vuex";

export default {
  name: "App",
  data() {
    return {
      modal: {},
      userActive: false,
    };
  },

  methods: {
    ...mapActions(["connectSocket"]),
  },
  created() {
    this.connectSocket(this.$ws_path);
  },
  watch: {
    modal() {
      if (this.modal.active) {
        let modal = this.modal;
        setTimeout(function () {
          modal.active = false;
        }, 5000);
      }
    },
  },
};
</script>
