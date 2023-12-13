<template>
  <div class="body">
    <nav class="navbar">
      <div class="tab interface" id="edit_to_list">
        <a @click="selectTab({ id: 'main' })" title="Главня">
          <img src="./../../assets/images/interface/main.png" alt="" />
        </a>
      </div>
      <div class="tab interface" id="edit_to_list">
        <a
          @click="
            prevCatalog = true;
            add = 0;
            catalogMode = true;
          "
          title="Каталог"
        >
          <img src="./../../assets/images/interface/catalog.png" alt="" />
        </a>
      </div>
      <!-- <div class="tab interface" id="edit_to_list">
        <router-link :to="{ name: 'ScriptMain' }" title="Скрипты">
          <img src="./../../assets/images/interface/script.png" alt="" />
        </router-link>
      </div> -->
      <div class="tab interface" id="edit_to_list">
        <a @click="addTab('new', {})" title="Добавить заявку">
          <img src="./../../assets/images/interface/plus.png" alt="" />
        </a>
      </div>
      <div class="allbar">
        <nav-tab
          v-for="tab in tabs"
          :key="tab.id"
          @close="tabClose(tab)"
          @click="selectTab(tab)"
          v-bind:data="tab"
          class="tab"
          :class="{ selected: tab.id == selectedTab }"
        ></nav-tab>
      </div>
      <div class="tab interface" id="edit_to_list">
        <a @click="exit()" title="Выйти из аккаунта">
          <img src="./../../assets/images/interface/logout.png" alt="" />
        </a>
      </div>
      <div class="tab interface" id="edit_to_list">
        <a @click="showMenu = !showMenu" title="Меню">
          <img src="./../../assets/images/interface/burger.png" alt="" />
        </a>
      </div>
    </nav>

    <nav v-show="showMenu" class="menu">
      <router-link v-if="user.admin" to="/admin"
        >Панель администратора</router-link
      >
      <router-link v-if="schedules()" to="/schedules">Графики</router-link>
    </nav>
    <div class="leftbar" :class="{ show: focus, hidden: displayMain }">
      <!-- <button
        id="seeing_catalog"
        @click="
          prevCatalog = true;
          add = 0;
          catalogMode = false;
        "
        class="button border-2g"
      >
        Товары на складе
      </button> -->
      <div class="sorting">
        <sort-main></sort-main>
      </div>
    </div>

    <main v-if="displayMain" id="main" @scroll="scrollMain($event)">
      <table v-if="GET_MAIN_LIST.length">
        <tr class="header">
          <th>№</th>
          <th
            class="sorting"
            @click="sortingFor($event, 'city')"
            data-order="none"
          >
            Город
          </th>
          <th>Адрес</th>
          <th>№ 1С</th>
          <th>Способ оплаты</th>
          <th style="min-width: 9em">Номер</th>
          <th
            class="sorting"
            @click="sortingFor($event, 'status')"
            data-order="none"
          >
            Статус
          </th>
          <th>Действие</th>
          <th
            class="sorting"
            @click="sortingFor($event, 'date')"
            data-order="none"
          >
            Дата действия
          </th>
          <th>Время действия</th>
          <th>Заметка</th>
        </tr>
        <tr
          @click="addTab(data.id, data)"
          v-for="data in GET_MAIN_LIST.slice(0, count)"
          :key="data.id"
        >
          <template v-if="!data.text">
            <td>{{ data.id }}</td>
            <td>
              {{ GET_CITY_NAME(data.city)
              }}<span class="null" v-show="!data.city">----------</span>
            </td>
            <td>
              {{
                data.street
                  ? data.street + (data.house ? ", д." + data.house : "")
                  : ""
              }}
              <span class="null" v-show="!data.street">----------</span>
            </td>
            <td>
              {{ data.no_order_1c }}
              <span class="null" v-show="!data.no_order_1c">----------</span>
            </td>
            <td>
              {{ GET_PAYMENT_NAME(data.payment_method) }}
              <span class="null" v-show="!data.payment_method">----------</span>
            </td>
            <td>
              {{ data.number ? $acceptNumber(data.number) : "" }}
              <span class="null" v-show="!data.number">----------</span>
            </td>
            <td>{{ GET_STATUS_NAME(data.status) }}</td>
            <td>
              {{
                data.status == 2
                  ? data.measuring_date
                    ? "Замер"
                    : ""
                  : [5, 6, 7].includes(Number(data.status))
                  ? data.delivery_date
                    ? "Доставка"
                    : ""
                  : data.expectation
                  ? "Связаться повторно"
                  : ""
              }}<span
                class="null"
                v-show="
                  !(data.status == 2
                    ? data.measuring_date
                      ? 'Замер'
                      : ''
                    : [5, 6, 7].includes(Number(data.status))
                    ? data.delivery_date
                      ? 'Доставка'
                      : ''
                    : data.expectation
                    ? 'Связаться повторно'
                    : '')
                "
                >----------</span
              >
            </td>
            <td>
              {{
                data.status == 2
                  ? $getStringDate(data.measuring_date)
                  : [5, 6, 7].includes(Number(data.status))
                  ? $getStringDate(data.delivery_date)
                  : $getStringDate(data.expectation)
              }}
              <span
                v-show="
                  !(data.status == 2
                    ? $getStringDate(data.measuring_date)
                    : [5, 6, 7].includes(Number(data.status))
                    ? $getStringDate(data.delivery_date)
                    : $getStringDate(data.expectation))
                "
                class="null"
                >----------</span
              >
            </td>
            <td>
              {{
                data.status == 2
                  ? $getTime(data.measuring_date, data.measuring_time)
                  : [5, 6, 7].includes(Number(data.status))
                  ? $getTime(data.delivery_date, data.delivery_time)
                  : ""
              }}
              <span
                v-show="
                  !(data.status == 2
                    ? $getTime(data.measuring_date, data.measuring_time)
                    : [5, 6, 7].includes(Number(data.status))
                    ? $getTime(data.delivery_date, data.delivery_time)
                    : '')
                "
                class="null"
                >----------</span
              >
            </td>
            <td>
              <pre>{{ data.note }}</pre>
            </td>
          </template>
          <template v-else>
            <td colspan="11" style="font-size: 1.5em">{{ data.text }}</td>
          </template>
        </tr>
      </table>
      <main-load v-else></main-load>
    </main>

    <application-body
      v-else
      :key="dataPage.id + Math.random()"
      :dataPage="dataPage"
    ></application-body>

    <catalog-body
      @close="prevCatalog = false"
      @addProduct="addProduct"
      :add="add"
      :catalog="catalogMode"
      v-if="prevCatalog"
    ></catalog-body>
  </div>
</template>

<script>
import CatalogBody from "./../Catalog/CatalogBody.vue";
import MainLoad from "./../MainLoad.vue";
import ApplicationBody from "./../Application/ApplicationBody.vue";
import NavTab from "./NavTab.vue";
import SortMain from "./SortMain.vue";
import api from "@/api";

import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
  components: {
    NavTab,
    CatalogBody,
    ApplicationBody,
    SortMain,
    MainLoad,
  },
  data() {
    return {
      showMenu: false,
      user: {},
      add: false,
      focus: false,
      selectedTab: "main",
      prevCatalog: false,
      displayMain: true,
      dataPage: {},
      tabs: [],
      catalogMode: true,
      count: 30,
    };
  },
  methods: {
    ...mapActions(["fetchCatalog", "exit", "fetchTabs", "fetchUpdateList"]),
    ...mapMutations({
      SortingByColumn: "SORTING_BY_COLUMN",
    }),
    refresh() {
      this.fetchCatalog();
    },
    scrollMain(e) {
      let height = e.target.children[0].clientHeight;
      if (
        e.target.scrollTop + e.target.clientHeight > height - 150 &&
        this.GET_MAIN_LIST.length > this.count
      ) {
        this.count += 30;
      }
    },
    schedules() {
      return this.user.rights.includes("11") || this.user.rights.includes("12");
    },
    tabClose(tabToClose) {
      if (tabToClose.data.seved) {
        this.tabs = this.tabs.filter((tab) => tab != tabToClose);
        this.fetchTabs(this.tabs);
        this.selectedTab == tabToClose.id
          ? this.selectTab({ id: "main" })
          : false;
      } else {
        let res = confirm(
          "Заявка не была сохранена на сервере, вы действительно хотите закрыйть ее? В случае закрытия данные будут утеряны."
        );
        if (res) {
          this.tabs = this.tabs.filter((tab) => tab != tabToClose);
          this.fetchTabs(this.tabs);
          this.dataPage = {};
          this.fetchUpdateList(tabToClose.id);
          this.selectTab({ id: "main" });
        } else {
          return false;
        }
      }
      this.selectedTab == tabToClose.id
        ? this.selectTab({ id: "main" })
        : false;
    },
    async addProduct(id, type) {
      this.dataPage.seved = false;
      let list = this.dataPage.productList;
      let newKey = 1;
      switch (type) {
        case "linoleum":
          list.linoleum.push(
            await api
              .get(
                "application/dataProduct.php?id_linoleum=" +
                  id +
                  "&key=" +
                  this.dataPage.lastKey
              )
              .then((resp) => resp.data)
          );
          this.dataPage.lastKey++;
          break;
        case "plinth":
          list.plinth.push(
            await api
              .get(
                "application/dataProduct.php?id_plinth=" +
                  id +
                  "&key=" +
                  this.dataPage.lastKey
              )
              .then((resp) => resp.data)
          );
          this.dataPage.lastKey++;
          break;
        case "accessories":
          list.accessories.push(
            await api
              .get(
                "application/dataProduct.php?id_accessories=" +
                  id +
                  "&key=" +
                  this.dataPage.lastKey
              )
              .then((resp) => resp.data)
          );
          this.dataPage.lastKey++;
          break;
        case "doorstep":
          list.doorstep.push(
            await api
              .get(
                "application/dataProduct.php?id_doorstep=" +
                  id +
                  "&key=" +
                  this.dataPage.lastKey
              )
              .then((resp) => resp.data)
          );
          this.dataPage.lastKey++;
          break;
        case "related": {
          list.related.push(
            await api
              .get(
                "application/dataProduct.php?id_related=" +
                  id +
                  "&key=" +
                  this.dataPage.lastKey
              )
              .then((resp) => resp.data)
          );
          this.dataPage.lastKey++;
        }
      }
      for (let i in list) {
        list[i].forEach((product) => {
          product.key = newKey++;
        });
      }
    },
    sortingFor(e, column) {
      let order = e.target.dataset.order;
      if (order == "none") {
        e.target.dataset.order = "up";
        e.target.classList.add("up");
      } else if (order == "up") {
        e.target.dataset.order = "down";
        e.target.classList.remove("up");
        e.target.classList.add("down");
      } else if (order == "down") {
        e.target.dataset.order = "none";
        e.target.classList.remove("down");
      }
      order = e.target.dataset.order;
      this.SortingByColumn({ column, order });
    },
    addTab(id, data) {
      switch (id) {
        case "new":
          data = {
            id: "new",
            no_order_1c: "",
            payment_method: 0,
            nick: "",
            full_name: "",
            connection: 1,
            city: "",
            street: "",
            house: "",
            corpus: "",
            entrance: "",
            flat: "",
            number: "",
            status: 1,
            note: "",
            sum: "0.00",
            weight: "0.00",
            purchase: "",
            square: "0.00",
            measuring_date: null,
            measuring_time: null,
            delivery_date: null,
            delivery_time: null,
            expectation: null,
            productList: {
              linoleum: [],
              plinth: [],
              accessories: [],
              doorstep: [],
              related: [],
            },
            lastKey: 2,
            seved: false,
          };

          break;
        default:
          data.number = this.$acceptNumber(data.number);
          data.seved = true;
          break;
      }
      var tab = { id, data };
      var list = this.tabs.filter((t) => t.id != tab.id);
      if (list.length == this.tabs.length)
        if (this.tabs.length >= 5) {
          let app = this.$parent.$parent;
          app.modal = {
            active: true,
            text: "Добавлено максимальное количество вкладок",
            error: true,
          };
          return false;
        } else {
          this.tabs.push(tab);
          this.fetchTabs(this.tabs);
          this.selectTab(tab);
        }
      else this.selectTab(tab);
    },
    selectTab(tab) {
      console.log(tab.id);
      this.selectedTab = tab.id;
      if (this.selectedTab == "main") {
        this.dataPage = {};
        this.displayMain = true;
        this.displayApp = false;
        this.$router.push({ name: "Main" });
      } else {
        console.log(tab);
        this.displayMain = false;
        this.displayApp = true;
        this.dataPage = tab.data;
        if (this.$route.name != "App" || this.$route.params.id != tab.id)
          this.$router.push({ name: "App", params: { id: tab.id } });
      }
    },
  },
  created() {
    if (localStorage.user) {
      this.user = JSON.parse(localStorage.user);
    }
  },
  mounted() {
    if (this.user.opened_tabs.length) {
      let t = this.tabs;
      let tabs = this.user.opened_tabs;
      tabs.forEach((i) => {
        t.push(i);
      });
      this.fetchTabs(this.tabs);
    }
    if (this.$route.name == "App") {
      if (this.tabs.length) {
        console.log(this.tabs.filter((i) => i.id == this.$route.params.id));
        var tab = this.tabs.filter((i) => i.id == this.$route.params.id)[0];
        console.log(this.$route.params.id);

        this.selectTab(tab);
      }
    }
  },
  computed: {
    ...mapGetters([
      "GET_MAIN_LIST",
      "GET_CITY_NAME",
      "GET_TOC_NAME",
      "GET_PAYMENT_NAME",
      "GET_STATUS_NAME",
    ]),
  },
};
</script>
<style lang="scss" src="./../../assets\styles\main.scss"></style>
<style lang="scss" src="./../../assets\styles\navbar.scss"></style>
