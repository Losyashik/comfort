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
          <router-link :to="{ name: 'PersonalBody', params: { id: '1' } }"
            >Николай</router-link
          >
        </li>
      </ul>
    </nav>
    <RouterView></RouterView>
  </div>
</template>
<style scoped lang="scss" src="./../../assets/styles/scripts/main.scss"></style>
