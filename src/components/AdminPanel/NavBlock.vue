<template>
  <div class="navigation">
    <div class="block-link main">
      <router-link to="/">На главную</router-link>
    </div>
    <div class="block-link">
      <router-link to="/admin/add">Добавление</router-link>
    </div>
    <div class="block-link">
      <router-link :to="{ name: 'Pricing' }">Ценообразование</router-link>
    </div>
    <div class="block-link">
      <router-link :to="{ name: 'Users' }">Полльзователи</router-link>
    </div>
  </div>
  <router-view class="admin_page"></router-view>
</template>
<script>
export default {
  updated() {},
};
</script>
<style
  scoped
  lang="scss"
  src="./../../assets/styles/admin/navigation.scss"
></style>
