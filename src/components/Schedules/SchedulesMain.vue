<template>
  <div>
    <router-link :to="{ name: 'Main' }" class="button" v-if="right">
      На главную
    </router-link>
    <div class="button" v-else @click="show()">Назад</div>
    <div class="button" @click="trigger = !trigger">Сохранить</div>
    <div class="modal show">
      <div class="bg white"></div>
      <div class="bg"></div>
      <div class="window">
        <router-link
          @click="show()"
          :to="{ name: 'SchedulesBody', params: { schedules: 'measuring' } }"
          >График замеров</router-link
        >
        <router-link
          @click="show()"
          :to="{ name: 'SchedulesBody', params: { schedules: 'delivery' } }"
          >График доставок</router-link
        >
        <router-link :to="{ name: 'Main' }">На главную</router-link>
      </div>
    </div>
    <div class="drag_drop">
      <router-view :trigger="trigger"></router-view>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      right: false,
      trigger: 0,
    };
  },
  methods: {
    show() {
      let modal = document.querySelector(".modal");
      modal.classList.toggle("show");
      if (modal.classList.contains("show")) {
        this.$router.push({ name: "Schedules" });
      }
    },
  },
  mounted() {
    if (document.querySelector(".modal").classList.contains("show")) {
      this.$router.push({ name: "Schedules" });
      let user = JSON.parse(localStorage.user);
      if (user.rights.includes("11") && !user.rights.includes("12")) {
        this.$router.push({
          name: "SchedulesBody",
          params: { schedules: "measuring" },
        });
        this.right = true;
        this.show();
      } else if (user.rights.includes("12") && !user.rights.includes("11")) {
        this.$router.push({
          name: "SchedulesBody",
          params: { schedules: "delivery" },
        });
        this.right = true;
        this.show();
      }
    }
  },
};
</script>
<style
  scoped
  lang="scss"
  src="./../../assets/styles/schedules/main.scss"
></style>
