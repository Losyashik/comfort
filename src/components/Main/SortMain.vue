<template>
  <div class="sort_body">
    <div class="search">
      <select name="type" @change="getFormData" v-model="search.type">
        <option value="nick">Поиск по нику</option>
        <option value="number">Поиск по номеру</option>
        <option value="street">Поиск по улице</option>
        <option value="no_order_1c">Поиск по номеру 1С</option>
      </select>
      <input
        type="search"
        :name="search.type"
        placeholder="Поиск"
        v-model="search.text"
        @input="getFormData"
      />
    </div>
    <div class="form_body">
      <div v-for="criteria in sortList" :key="criteria.name" class="criteria">
        <h4 @click="openList(criteria)">{{ criteria.name }}</h4>
        <ul :class="{ active: criteria.openList }">
          <li v-for="li in criteria.list" :key="li.id">
            <label
              ><input
                @change="getFormData"
                :value="li.id"
                class="main"
                :name="criteria.value"
                type="checkbox"
              />{{ li.name }}</label
            >
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
import { mapMutations } from "vuex";

async function post(list, path, data) {
  const f = await fetch(path, {
    method: "POST",
    body: data,
    headers: { "content-type": "application/x-www-form-urlencoded" },
  });
  const text = await f.json();
  text.forEach((el) => {
    list.push(el);
  });
}
export default {
  data() {
    return {
      search: { type: "nick", text: "" },
      sortData: {},
      sortList: [],
    };
  },
  methods: {
    ...mapMutations(["sortingMain"]),
    openList(creteria) {
      if (creteria.openList) {
        creteria.openList = false;
      } else {
        creteria.openList = true;
      }
    },

    upodateSortList() {
      this.sortList = [];
      let path = this.$connect + "main/sotList.php";
      post(this.sortList, path);
    },
    getFormData() {
      let checkBoxList = document.querySelectorAll("input[type=checkbox].main");
      this.sortData = {};

      document.querySelector("#main").scrollTop = 0;
      this.$parent.count = 30;

      checkBoxList.forEach((el) => {
        if (el.checked) {
          if (!this.sortData[el.name]) this.sortData[el.name] = [];
          this.sortData[el.name].push(el.value);
        }
      });
      let keys;
      if (Object.keys(this.sortData) != [])
        keys = Object.keys(this.sortData).length;
      else keys = 0;
      if (keys != 0) {
        this.sortData.opiration = [];
        this.sortData.opiration.push("sorting");
      }
      if (this.search.text.length != 0) {
        this.sortData.type = this.search.type;
        this.sortData.text = this.search.text;
        if (this.sortData.opiration) {
          this.sortData.opiration.push("search");
        } else {
          this.sortData.opiration = ["search"];
        }
      }
      if (!this.sortData.opiration) {
        this.sortData = {};
      }
      console.log(this.sortData);
      this.sortingMain(this.sortData);
    },
  },

  created: function () {
    this.upodateSortList();
  },
};
</script>
<style scoped lang="scss" src="./../../assets/styles/sort.scss"></style>
