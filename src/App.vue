<template>
  <div id="app">
    <router-view />
    <modal-window @close="close()" :obj="modal"></modal-window>
  </div>
</template>
<script>
import { mapActions } from "vuex";

export default {
  name: "App",
  data() {
    return {
      modal: { active: false, text: "", error: false },
      userActive: false,
    };
  },

  methods: {
    ...mapActions({ updateMain: "fetchUpdateList" }),
    close() {
      this.modal.active = false;
      this.modal.error = false;
    },
  },
  created() {
    this.$ws.onmessage = (send) => {
      send = JSON.parse(send.data);
      this.updateMain(send);
    };
    this.$ws.onclose = (e) => {
      switch (e.code) {
        case 1000:
          console.log("Normal close");
          break;

        default:
          console.log("closed websocked connect");
          window.location.reload();
          break;
      }
    };
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
