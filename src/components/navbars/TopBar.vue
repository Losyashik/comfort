<template>
  <nav class="topbar">
    <ul class="topbar__ul">
      <li class="topbar__li">
        <router-link
          :to="{ name: 'Main' }"
          class="topbar__link topbar__link-static"
          ><img src="./assets/images/main.png" alt="Главная"
        /></router-link>
      </li>
      <!-- -------------------- -->
      <li class="topbar__li">
        <router-link :to="{ name: 'Main' }" class="topbar__link">
          <div class="text">Заявка №3542</div>
          <img class="close" src="./assets/images/close.png" alt="" />
        </router-link>
      </li>
      <!-- --------------------- -->
      <li class="topbar__li">
        <router-link
          :to="{ name: 'Main' }"
          class="topbar__link topbar__link-static"
          ><img src="./assets/images/plus.png" alt="Главная"
        /></router-link>
      </li>
    </ul>
    <router-link :to="{ name: 'Main' }" class="topbar__user-data">
      <div class="name">
        Мартыновская<br />
        Юлия Вячеславовна
      </div>
    </router-link>
  </nav>
</template>
<style lang="scss" scoped src="./assets/styles/TopBarStyle.scss"></style>
