<template>
  <div class="not_right">
    <h1>У вас нет прав, чтобы осуществлять данное действие</h1>
    <img src="" alt="" />
    <p>
      Если вы попали на данную страницу, обратитесь к администратору, чтобы он
      выдал доступ к данной странице
    </p>
    <a href="/">Вернуться на главную</a>
  </div>
</template>
<style scoped>
.not_right {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}
.not_right * {
  display: block;
}
.not_right a {
  border: 2px solid;
  background: #b3b2b2;
  padding: 5px;
}
.not_right a:hover {
  background: #dbdbdb;
}
.not_right a:active {
  box-shadow: inset 0 0 6px 1px #000;
}
</style>
