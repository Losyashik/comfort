<template>
  <nav class="rightbar">
    <ul class="rightbar__ul">
      <li class="rightbar__item">
        <router-link :to="{ name: 'Main' }" class="rightbar__link"
          ><img src="./assets/images/notification.png" alt=""
        /></router-link>
      </li>
      <li class="rightbar__item">
        <router-link :to="{ name: 'Main' }" class="rightbar__link"
          ><img src="./assets/images/catalog.png" alt=""
        /></router-link>
      </li>
      <li class="rightbar__item">
        <router-link :to="{ name: 'Main' }" class="rightbar__link"
          ><img src="./assets/images/chat.png" alt=""
        /></router-link>
      </li>
      <li class="rightbar__item">
        <router-link :to="{ name: 'Main' }" class="rightbar__link"
          ><img src="./assets/images/schedule-delivery.png" alt=""
        /></router-link>
      </li>
      <li class="rightbar__item">
        <router-link :to="{ name: 'Main' }" class="rightbar__link"
          ><img src="./assets/images/schedule-measuring.png" alt=""
        /></router-link>
      </li>

      <li class="rightbar__item">
        <router-link :to="{ name: 'Main' }" class="rightbar__link"
          ><img src="./assets/images/settings.png" alt=""
        /></router-link>
      </li>
    </ul>
    <router-link :to="{ name: 'Main' }" class="rightbar__link"
      ><img src="./assets/images/logout.png" alt=""
    /></router-link>
  </nav>
</template>
<style lang="scss" scoped src="./assets/styles/RightBarStyle.scss"></style>
