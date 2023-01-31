<template>
  <div class="statistics">
    <nav class="statistics_nav">
      <div class="calendar">
        <v-range-selector
          class="statistics_nav__calendar"
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
          :enableSingleDate="false"
          v-model:start-date="range.start"
          v-model:end-date="range.end"
        ></v-range-selector>
      </div>
    </nav>

    <div class="statistics_main" v-if="general && statistics">
      <h2 class="statistics_heading" v-if="range.start">
        Статистика
        {{
          range.end
            ? "c " +
              $getStringDate(range.start) +
              " по " +
              $getStringDate(range.end)
            : " на " + $getStringDate(range.start)
        }}
      </h2>
      <div class="statistics_body general">
        <h2 class="statistics_heading">Общая статистика</h2>
        <div class="statistics_body__all">
          <div
            class="statistics_item__block"
            :class="{ statistics_item__active: selectedItem == 'orders' }"
            @click="selectedItem = 'orders'"
          >
            <h4 class="statistics_item__heading">Количество заявок:</h4>
            <div class="statistics_item__info">
              {{ general.orders + " " + editEi(general.orders) }}
            </div>
          </div>
          <div
            class="statistics_item__block"
            :class="{ statistics_item__active: selectedItem == 'space' }"
            @click="selectedItem = 'space'"
          >
            <h4 class="statistics_item__heading">Общая площадь:</h4>
            <div class="statistics_item__info">
              {{ general.space }} м<sup>2</sup>
            </div>
          </div>
          <div
            class="statistics_item__block"
            :class="{ statistics_item__active: selectedItem == 'plinth' }"
            @click="selectedItem = 'plinth'"
          >
            <h4 class="statistics_item__heading">Количество плинтусов:</h4>
            <div class="statistics_item__info">{{ general.plinth }} шт.</div>
          </div>
          <div
            class="statistics_item__block"
            :class="{ statistics_item__active: selectedItem == 'accessories' }"
            @click="selectedItem = 'accessories'"
          >
            <h4 class="statistics_item__heading">Количество фурнитуры:</h4>
            <div class="statistics_item__info">
              {{ general.accessories }} шт.
            </div>
          </div>
          <div
            class="statistics_item__block"
            :class="{ statistics_item__active: selectedItem == 'doorstep' }"
            @click="selectedItem = 'doorstep'"
          >
            <h4 class="statistics_item__heading">Количество порогов:</h4>
            <div class="statistics_item__info">{{ general.doorstep }} шт.</div>
          </div>
          <div
            class="statistics_item__block"
            :class="{ statistics_item__active: selectedItem == 'related' }"
            @click="selectedItem = 'related'"
          >
            <h4 class="statistics_item__heading">Количество соп. товарв</h4>
            <div class="statistics_item__info">{{ general.related }} шт.</div>
          </div>
        </div>
      </div>
      <div class="statistics_body list">
        <h2 class="statistics_heading">
          Подробная статистика
          {{ heading() }} <button @click="copyTable()">Копировать</button>
        </h2>
        <div class="statistics_table">
          <div class="statistics_table__body">
            <!-- Образец -->
            <template v-if="['accessories', 'space'].includes(selectedItem)">
              <div
                class="statistics_table__row"
                v-for="row in view"
                :key="row.name"
                :class="{ row__show: row.bol }"
              >
                <div class="statistics_table__ceil ceil__name">
                  {{ row.name }}
                </div>
                <div
                  class="statistics_table__ceil ceil__info"
                  style="display: inline-block"
                >
                  {{ row.count }} <span v-html="row.ei"></span>
                </div>
                <div
                  class="statistics_table__ceil ceil__button"
                  @click="show($event, row)"
                >
                  {{ row.bol ? "Скрыть" : "Подробнее" }}
                </div>
                <div class="statistics_table__ceil ceil__table">
                  <div class="table_body">
                    <div class="table_row">
                      <div class="table_ceil">Ширина</div>
                      <div class="table_ceil">Площадь</div>
                    </div>
                    <div
                      class="table_row"
                      v-for="item in row.items"
                      :key="item.name"
                    >
                      <div class="table_ceil">{{ item.name }}</div>
                      <div class="table_ceil" style="display: inline-block">
                        {{ item.count }} <span v-html="row.ei"></span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </template>
            <template v-else>
              <div
                class="statistics_table__row statistics_table__row_minimal"
                v-for="(item, i) in view"
                :key="i"
              >
                <div class="statistics_table__ceil ceil__name">
                  {{ item.name }}
                </div>
                <div class="statistics_table__ceil ceil__info">
                  <span>
                    {{ item.cnt }}
                    <span
                      v-html="item.ei != 'шт.' ? editEi(item.cnt) : item.ei"
                    ></span>
                  </span>
                </div>
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
    <main-load v-else></main-load>
    <div class="hide" id="copy">
      <table>
        <template v-if="['accessories', 'space'].includes(selectedItem)">
          <tr v-if="selectedItem == 'space'">
            <th>Линолеум</th>
            <th>Ширина</th>
            <th>Площадь</th>
            <th>Общая площадь</th>
          </tr>
          <tr v-else-if="(selectedItem = 'accessories')">
            <th>Плинтус</th>
            <th>Название фурнитуры</th>
            <th>Кол-во</th>
            <th>Общее кол-во</th>
          </tr>
          <template v-for="row in view" :key="row.name">
            <tr v-for="(item, index) in row.items" :key="index">
              <td v-if="index == 0" :rowspan="row.items.length">
                {{ row.name }}
              </td>
              <td>
                {{
                  selectedItem == "space" ? replaceDot(item.name) : item.neme
                }}
              </td>
              <td>
                {{
                  selectedItem == "space" ? replaceDot(item.count) : item.count
                }}
              </td>
              <td v-if="index == 0" :rowspan="row.items.length">
                {{
                  selectedItem == "space" ? replaceDot(row.count) : row.count
                }}
              </td>
            </tr>
          </template>
        </template>
        <template v-else>
          <tr>
            <th>Название</th>
            <th>Кол-во</th>
            <th>е. и.</th>
          </tr>
          <tr v-for="item in view" :key="item.name">
            <td>{{ item.name }}</td>
            <td>{{ replaceDot(item.cnt) }}</td>
            <td v-html="item.ei != 'шт.' ? editEi(item.cnt) : item.ei"></td>
          </tr>
        </template>
      </table>
    </div>
  </div>
</template>
<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import VRangeSelector from "./../../../assets/componets/vuelendar/components/vl-range-selector";
import MainLoad from "@/components/MainLoad.vue";

export default {
  components: {
    MainLoad,
    VRangeSelector,
  },
  data() {
    return {
      bol: false,
      selectedItem: "orders",
      range: {
        start: "",
        end: "",
      },
    };
  },
  methods: {
    ...mapMutations({ update: "SELECT_VIEW_DATA" }),
    ...mapActions({ sorting_by: "FETCH_STATISTICS_IN_DATE" }),
    heading() {
      switch (this.selectedItem) {
        case "orders":
          return "по заявкам";
        case "space":
          return "по линолеуму";
        case "plinth":
          return "по плинтусам";
        case "accessories":
          return "по фурнитуре";
        case "doorstep":
          return "по порогам";
        case "related":
          return "по соп. товарам";
      }
    },
    replaceDot(count) {
      return String(count).replace(".", ",");
    },
    copyTable() {
      let table = document.getElementById("copy");
      table.classList.remove("hide");
      let range = document.createRange();
      range.selectNodeContents(table);
      window.getSelection().empty();
      window.getSelection().addRange(range);
      document.execCommand("copy");
      table.classList.add("hide");
    },
    editEi(cnt) {
      let count = cnt;
      if (count > 20) while (count > 10) count %= 10;
      if (count == 1) return "заявка";
      else if (count >= 2 && count <= 4) return "заявки";
      else if (count >= 5 || count == 0) return "заявок";
    },
    show(e, item) {
      item.bol = !item.bol;
      let nextRow = e.currentTarget.parentElement.nextElementSibling;
      if (nextRow != null)
        if (item.bol) nextRow.classList.add("row__nextshow");
        else nextRow.classList.remove("row__nextshow");
    },
  },
  created() {
    this.selectedItem = "orders";
  },
  computed: {
    ...mapGetters({
      general: "GET_GENERAL_STATISTICS",
      statistics: "GET_ALL_STATISTICS",
      view: "GET_STATISTICS",
    }),
  },
  watch: {
    selectedItem(newVal, oldVal) {
      if (newVal != oldVal) this.update(newVal);
    },
    range: {
      deep: true,
      handler() {
        this.selectedItem = "orders";
        this.sorting_by(this.range);
      },
    },
  },
};
</script>
<style
  lang="scss"
  src="./../../../assets/styles/admin/statistics/main.scss"
></style>
