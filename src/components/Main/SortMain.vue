<template>
  <div class="sort_body">
    <div class="search">
      <select name="type" @change="getFormData" v-model="search.type">
        <option value="street">Поиск по улице</option>
        <option value="number">Поиск по номеру</option>
        <option value="no_order_1c">Поиск по номеру 1С</option>
        <option value="id">Поиск по № заявки</option>
        <option value="nick">Поиск по нику</option>
      </select>
      <input
        type="search"
        :name="search.type"
        placeholder="Поиск"
        v-model="search.text"
        @input="getFormData"
        @focus="$parent.focus = true"
        @focusout="$parent.focus = false"
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
      <div class="calendar">
        <div class="calendar_interface">
          <div class="calendar_interface_info">
            {{
              range.start
                ? $getStringDate(range.start) +
                  (range.end ? " - " + $getStringDate(range.end) : "")
                : "Дата не выбрана"
            }}
          </div>
          <button
            @click.prevent="
              range.start = '';
              range.end = '';
            "
          >
            Сброс
          </button>
        </div>
        <v-range-selector
          class="main_calendar"
          :singleMonth="true"
          :customClasses="{
            now_date: (date) => {
              var nowData = new Date();
              var nowDate =
                nowData.getFullYear() +
                '-' +
                (nowData.getMonth() + 1 < 10
                  ? '0' + nowData.getMonth() + 1
                  : nowData.getMonth() + 1) +
                '-' +
                (nowData.getDate() < 10
                  ? '0' + nowData.getDate()
                  : nowData.getDate());
              return nowDate == date;
            },
          }"
          :enableSingleDate="true"
          v-model:start-date="range.start"
          v-model:end-date="range.end"
        ></v-range-selector>
      </div>
    </div>
  </div>
</template>
<script>
import VRangeSelector from "./../../assets/componets/vuelendar/components/vl-range-selector";
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
  components: {
    VRangeSelector,
  },
  data() {
    return {
      search: { type: "nick", text: "" },
      sortData: {},
      sortList: [],
      range: {
        start: "",
        end: "",
      },
    };
  },
  methods: {
    ...mapMutations({ sortingMain: "SORTING_MAIN" }),
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
      if (keys != 0 || this.range.start != "") {
        this.sortData.opiration = [];
        this.sortData.opiration.push("sorting");
      }
      if (this.range.start) {
        this.sortData.date = this.range;
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
      this.sortingMain(this.sortData);
    },
  },

  created: function () {
    this.upodateSortList();
  },
  watch: {
    range: {
      deep: true,
      handler() {
        this.getFormData();
      },
    },
  },
};
</script>
<style src="vuelendar/scss/vuelendar.scss" lang="scss"></style>
<style scoped lang="scss" src="./../../assets/styles/sort.scss"></style>
<style lang="scss">
.vl-calendar.main_calendar {
  .vl-calendar-month {
    width: auto;
    margin-bottom: 10px;
  }
  .vl-calendar-month__week-day {
    margin-bottom: 5px;
  }
  .vl-calendar-month__day {
    &.now_date {
      background-color: #ccc;
      border-radius: 14px;
    }
    margin: 2px 0;
    @for $i from 1 through 6 {
      &--offset-#{$i} {
        margin-left: calc(#{$i} * 14%);
      }
    }
  }
}
</style>
