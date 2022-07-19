<template>
  <div id="add">
    <div class="breadcrumbs">
      <ul>
        <li><router-link :to="{ name: 'Add' }">Главная</router-link></li>
      </ul>
    </div>
    <router-view></router-view>
  </div>
</template>
<style scoped lang="scss" src="./../../../assets/styles/admin/add.scss"></style>
