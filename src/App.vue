<template>
  <div id="app">
    <router-view @mousemove="upadate" />
    <modal-window @close="close()" :obj="modal"></modal-window>
  </div>
</template>
<script>
async function presence(t) {
  if (localStorage.user) {
    let user = JSON.parse(localStorage.user);
    if (t.userActive === false) {
      let time = new Date().getTime();
      if (time - user.time > 20 * 60 * 1000) {
        localStorage.removeItem("user");
        await fetch(t.$connect + "exit.php");
        t.$router.push("login");
      }
    } else {
      t.userActive = false;
      user.time = new Date().getTime();
      localStorage.user = JSON.stringify(user);
    }
  }
}
export default {
  name: "App",
  data() {
    return {
      modal: { active: false, text: "", error: false },
      userActive: false,
    };
  },
  methods: {
    close() {
      this.modal.active = false;
      this.modal.error = false;
    },
    upadate() {
      if (!this.userActive) this.userActive = true;
    },
    presence() {
      let thet = this;
      setInterval(function () {
        presence(thet);
      }, 1000);
    },
  },
  created() {
    this.presence();
  },
};
</script>
