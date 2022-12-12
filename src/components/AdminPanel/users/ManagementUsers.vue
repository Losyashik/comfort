<template>
  <div class="users">
    <button class="add_user" @click="modal = true">
      Зарегистрировать нового пользователя
    </button>
    <add-user
      v-if="modal"
      @close="modal = false"
      @reg="getUserList()"
    ></add-user>
    <form class="flex">
      <h2>Управление привелегиями пользователя</h2>
      <div class="users_list">
        <h3>Выберете пользователя</h3>
        <ul>
          <template v-for="item in users" :key="item.id">
            <li class="row_users" :class="{ checked: item.checked == true }">
              <label
                ><input
                  type="radio"
                  @change="selectUser(item)"
                  name="user"
                  :value="item.id"
                />{{ item.name }}</label
              >
            </li>
          </template>
        </ul>
      </div>
      <div v-if="rights.length" class="rights">
        <h3 class="full_name">Права пользователя: {{ title }}</h3>
        <ul>
          <template v-for="item in rights" :key="item.category">
            <li>
              <h4>{{ item.category }}</h4>
            </li>
            <template v-for="right in item.list" :key="right.id">
              <li :class="{ checked: right.checked }">
                <label
                  ><input
                    type="checkbox"
                    v-model="right.checked"
                    :value="right.id"
                    name="rights[]"
                  />{{ right.name }}</label
                >
              </li>
            </template>
          </template>
        </ul>
      </div>
      <button class="submit" @click.prevent="editRights($event)">
        Сохранить изменения
      </button>
    </form>
  </div>
</template>
<script>
import AddUser from "./AddUser.vue";
export default {
  components: {
    AddUser,
  },
  data() {
    return {
      rights: [],
      users: [],
      title: "",
      modal: false,
    };
  },
  methods: {
    async getUserList() {
      let data = new FormData();
      data.append("list", "1");
      this.users = await fetch(this.$connect + "admin/users/getLists.php", {
        method: "POST",
        body: data,
      }).then((response) => response.json());
    },
    async selectUser(item) {
      this.users.forEach((el) => {
        el.checked = document.querySelector(
          "input[type='radio'][value='" + el.id + "']"
        ).checked;
      });
      let data = new FormData();
      data.append("id_user", item.id);
      this.title = item.name;
      this.rights = await fetch(this.$connect + "admin/users/getLists.php", {
        method: "POST",
        body: data,
      }).then((response) => response.json());
    },
    async editRights(event) {
      let data = new FormData(event.target.parentNode);
      let app = this.$parent.$parent.$parent.$parent;
      app.modal = await fetch(this.$connect + "admin/users/updateRights.php", {
        method: "POST",
        body: data,
      }).then((response) => response.json());
    },
  },
  created() {
    this.getUserList();
  },
};
</script>
<style
  scoped
  lang="scss"
  src="./../../../assets/styles/admin/users/usersMain.scss"
></style>
