<template>
  <div class="body">
    <nav>
      <router-link :to="{ name: 'ScriptMain' }" class="add_button">
        <img src="./../../assets/images/interface/plus.png" alt="" />
        <div>Добавить</div>
      </router-link>
      <h3>Начатые диалоги</h3>
      <ul>
        <li>
          <router-link
            v-for="item in dialogs"
            :key="item.id"
            :to="{ name: 'PersonalBody', params: { id: item.id } }"
            >{{ item.name ? item.name : "Неизвестный" }}</router-link
          >
        </li>
      </ul>
    </nav>
    <RouterView></RouterView>
  </div>
</template>
<script>
export default {
  data() {
    return {
      dialogs: [],
    };
  },
  methods: {
    addDialog() {
      this.tabs.push({
        steps: [0],
      });
      let index = this.tabs.length;
      this.$router.push({ name: "PersonalBody", params: { id: index } });
    },
  },
};
</script>
<style scoped lang="scss" src="./../../assets/styles/scripts/main.scss"></style>
