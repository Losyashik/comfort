<template>
  <div>
    <div class="sorting">
      <form>
        <select name="user" v-model="selectedUser">
          <option value="0" disabled>Выберете пользователя</option>
          <option v-for="item in users" :key="item.id" :value="item.id">
            {{ item.name }}
          </option>
        </select>
        <div class="date">
          <div class="range_date">
            <div class="inputs">
              <input type="date" name="date_start" v-model="range.start" />
              <div>-</div>
              <input type="date" name="date_end" v-model="range.end" />
            </div>
            <button @click="getLists()">Показать</button>
          </div>

          <div class="calendar">
            <a @click="selectDate = !selectDate"
              >{{ selectDate ? "Скрыть" : "Показать" }} календарь</a
            >
            <v-range-selector
              v-show="selectDate"
              v-model:start-date="range.start"
              v-model:end-date="range.end"
              :singleMonth="true"
            />
          </div>
        </div>
      </form>
    </div>
    <table>
      <tr class="header">
        <th>№</th>
        <th>Адрес</th>
        <th>Маржа</th>
        <th>Действие</th>
        <th>Дата</th>
      </tr>
      <tr v-for="item in seeList" :key="item.id">
        <td>{{ item.id }}</td>
        <td>{{ item.addres }}</td>
        <td>{{ item.margin }}</td>
        <td>{{ item.action }}</td>
        <td>{{ $getStringDate(item.date) }}</td>
      </tr>
    </table>
  </div>
</template>
<script>
import VRangeSelector from "./../components/vuelendar/components/vl-range-selector";

export default {
  components: {
    VRangeSelector,
  },
  data() {
    return {
      selectedUser: 0,
      users: [],
      selectDate: false,
      range: {},
      list: [],
      seeList: [],
    };
  },
  methods: {
    async getData() {
      this.users = await fetch(
        this.$connect + "/admin/accounting/usersData.php"
      ).then((resp) => resp.json());
    },
    async getList() {
      if (this.range.end && this.range.start) {
        this.list = await fetch(
          this.$connect + "/admin/accounting/usersData.php"
        ).then((resp) => resp.json());
      }
    },
    async getListApplication() {
      this.seeList = await fetch(
        this.$connect + "/admin/accounting/applicationList.php"
      ).then((resp) => resp.json());
    },
  },
  created() {
    this.getData();
  },
  watch: {
    range: {
      deep: true,
      handler(newVal, oldVal) {
        if (this.watch) {
          if (newVal != oldVal) {
            this.getListApplication();
          }
        }
      },
    },
  },
};
</script>
<style
  lang="scss"
  scoped
  src="./../../../assets/styles/admin/accounting/accounting.scss"
></style>
<style lang="scss" src="./../components/vuelendar/scss/vuelendar.scss"></style>
