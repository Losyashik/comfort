<template>
  <form class="sort_body">
    <div class="form_body">
      <div class="search">
        <input
          type="search"
          @input="getFormData()"
          name="search"
          placeholder="Поиск по названию"
        />
      </div>
      <div v-for="criteria in sortList" :key="criteria.name" class="criteria">
        <h4 @click="openList(criteria)">{{ criteria.name }}</h4>
        <ul :class="{ active: criteria.openList }">
          <li v-for="li in criteria.list" :key="li.id">
            <label
              ><input
                @change="getFormData()"
                :value="li.id"
                class="catalog"
                :name="criteria.value"
                v-model="li.checked"
                type="checkbox"
              />{{ li.name }}</label
            >
          </li>
        </ul>
      </div>
    </div>
  </form>
</template>
<script>
import { mapMutations } from "vuex";

export default {
  data() {
    return {
      sortData: {},
      sortList: [],
    };
  },
  methods: {
    ...mapMutations({ sorting: "SORTING_CATALOG" }),
    openList(creteria) {
      if (creteria.openList) {
        creteria.openList = false;
      } else {
        creteria.openList = true;
      }
    },

    async upodateSortList() {
      let data = "type_list=" + this.type;
      this.sortList = await fetch(this.$connect + "catalog/sortList.php", {
        method: "post",
        body: data,
        headers: { "content-type": "application/x-www-form-urlencoded" },
      }).then((resp) => resp.json());
    },
    async getFormData() {
      let search = document.querySelector('input[name="search"]').value;
      let checkBoxList = document.querySelectorAll(
        "input[type=checkbox].catalog"
      );
      this.sortData = {};
      let data = "type_list=" + this.type;
      checkBoxList.forEach((el) => {
        if (el.checked) {
          if (!this.sortData[el.name]) {
            this.sortData[el.name] = [];
          }
          this.sortData[el.name].push(el.value);
        }
      });
      if (search != "") {
        this.sortData["search"] = search;
      }
      data += "&sortList=" + JSON.stringify(this.sortList);
      this.sortList = await fetch(this.$connect + "catalog/sortList.php", {
        method: "post",
        body: data,
        headers: { "content-type": "application/x-www-form-urlencoded" },
      }).then((resp) => resp.json());
      this.$parent.count =
        this.type == "doorstep" || this.type == "related" ? 60 : 20;
      document.querySelector(".catalog_product_list").scrollTop = 0;
      this.sorting({ type: this.type, data: this.sortData });
    },
  },

  mounted: function () {
    this.$nextTick(function () {
      this.upodateSortList();
    });
  },
  props: ["type"],
};
</script>
<style lang="scss" scoped src="./../../assets/styles/sort.scss"></style>
