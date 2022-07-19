<template>
  <div class="registration_user">
    <div class="close" @click="$emit('close')">
      <img src="./../../../assets/images/interface/close.png" />
    </div>
    <h4>Форма регистрации</h4>
    <form @submit.prevent="submit($event)">
      <input type="text" autocomplete="off" name="name" placeholder="ФИО" />
      <input type="text" autocomplete="off" name="login" placeholder="Логин" />
      <input
        type="text"
        autocomplete="off"
        name="password"
        @keydown="search($event)"
        placeholder="Пароль"
      />
      <input
        type="text"
        autocomplete="off"
        name="dblpassword"
        placeholder="Повторение пароля"
      />
      <div class="error" :class="{ active: message.length }">
        {{ message }}
      </div>
      <button>Зарегистрировать</button>
    </form>
  </div>
</template>
<script>
export default {
  data() {
    return {
      message: "",
    };
  },
  methods: {
    search(event) {
      var regexp = /[а-яё]/i;
      if (regexp.test(event.key)) {
        event.preventDefault();
        this.message =
          "В пароле можно использовать латинский алфавит, цыфры и любые символы";
        return false;
      }
    },
    async submit(e) {
      let data = new FormData(e.target);
      for (var pair of data.entries()) {
        if (pair[1] == "") {
          let inp = document.querySelector("input[name='" + pair[0] + "']");
          inp.classList.add("error_input");
          setTimeout(function () {
            inp.classList.remove("error_input");
          }, 4000);
          this.message = "Заполните все поля";
          return false;
        }
      }
      if (data.get("password") != data.get("dblpassword")) {
        this.message = "Пароли не совподают";
        return false;
      } else if (data.get("password").length < 6) {
        this.message = "Мин. длинна пароля 6 символов";
        return false;
      }
      let app = this.$parent.$parent.$parent.$parent.$parent;
      let resp = await fetch(this.$connect + "/admin/users/addUser.php", {
        method: "POST",
        body: data,
      }).then((response) => response.json());
      if (resp.error) {
        this.message = resp.text;
      } else {
        app.modal = resp;
        setTimeout(function () {
          app.modal.active = false;
        }, 5000);
        this.$emit("reg");
        this.$emit("close");
      }
    },
  },
  emits: ["close"]["reg"],
};
</script>
<style
  scoped
  lang="scss"
  src="./../../../assets/styles/admin/users/addUser.scss"
></style>
