<template>
  <div class="login_body">
    <form id="auth">
      <h2>Авторизация</h2>
      <div class="border">
        <input
          type="text"
          name="login"
          v-model="data.login"
          @change="$event.target.parentNode.style = ''"
          placeholder="Логин"
        />
      </div>
      <div class="border password">
        <input
          type="password"
          name="password"
          v-model="data.password"
          @change="$event.target.parentNode.style = ''"
          autocomplete="none"
          placeholder="Пароль"
        />
        <img
          :src="require('./../assets/images/interface/' + eye + '.png')"
          @click="watch()"
          alt=""
        />
      </div>
      <div :class="{ active: message.length, message: true }">
        {{ message }}
      </div>
      <button @click="authorization($event)">Войти</button>
    </form>
  </div>
</template>
<script>
export default {
  data() {
    return {
      data: {
        login: "",
        password: "",
      },
      auth: false,
      eye: "eye",
      message: "",
    };
  },
  methods: {
    watch() {
      let inp = document.querySelector("input[name = 'password']");
      switch (this.eye) {
        case "invisible": {
          inp.type = "password";
          this.eye = "eye";
          break;
        }
        case "eye": {
          inp.type = "text";
          this.eye = "invisible";
          break;
        }
      }
    },
    authorization(event) {
      event.preventDefault();
      if (this.data.login.length === 0) {
        let inp = document.querySelector("input[name = 'login']");
        inp.parentNode.style =
          "border: 1px solid red; background:rgba(253, 33, 33, .5);";
        this.message = "Поле логина не заполнено";
        return false;
      } else if (this.data.password.length === 0) {
        let inp = document.querySelector("input[name = 'password']");
        inp.parentNode.style =
          "border: 1px solid red; background:rgba(253, 33, 33, .5);";
        this.message = "Поле пароля не заполнено";
        return false;
      }
      let inp = document.querySelector("input");
      inp.style = "";
      this.message = "";
      let form = event.target.parentNode;
      let data = new FormData(form);
      let promis = fetch(this.$connect + "authorization.php", {
        method: "post",
        body: data,
      });
      promis
        .then((response) => response.json())
        .then((result) => {
          if (result.error) {
            this.message = result.error;
          } else {
            result.time = new Date().getTime();
            localStorage.user = JSON.stringify(result);
            this.$router.push("main");
          }
        });
    },
  },
};
</script>
<style scoped>
.login_body {
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  background: rgba(0, 0, 0, 0.4);
}
form#auth {
  background-color: #fff;
  width: 25%;
  padding: 10px 20px;
  border: 1px solid;
  display: flex;
  flex-direction: column;
}
form#auth h2 {
  text-align: center;
  margin: 0 0 5px;
}
form#auth .border {
  transition: all 0.4s;
  padding: 10px;
  margin: 5px 0;
  border: 1px solid;
}
form#auth .border.password {
  display: flex;
}
form#auth .border.password img {
  width: 20px;
}
form#auth .border input,
form#auth button {
  display: block;
  font-size: 12pt;
}
form#auth .border input:focus-visible {
  outline: none;
}
form#auth .border input {
  width: 100%;
  position: relative;
  border: none;
  background: rgba(0, 0, 0, 0);
}
form#auth .message {
  text-align: center;
  background: rgba(253, 33, 33, 0.95);
  padding: 0;
  height: 0;
  overflow: hidden;
  transition: all 0.3s;
}
form#auth .message.active {
  padding: 10px 0;
  height: 20px;
}
form#auth button {
  padding: 5px;
  margin: 10px 30%;
}
</style>
