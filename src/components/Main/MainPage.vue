<template>
  <div class="body">
    <nav class="navbar">
      <div class="allbar">
        <nav-tab
          v-for="tab in tabs"
          :key="tab.no"
          @close="tabClose(tab)"
          @click="selectTab(tab)"
          v-bind:data="tab"
          class="tab"
          :class="{ selected: tab.no == selectedTab }"
        ></nav-tab>
      </div>
      <div class="tab interface" id="edit_to_list">
        <a @click="exit()" title="Выйти из аккаунта">
          <img src="./../../assets/images/interface/logout.png" alt="" />
        </a>
      </div>
    </nav>

    <div class="leftbar">
      <button id="main" class="button" @click="selectTab({ no: 'main' })">
        Главная
      </button>
      <button v-if="user.admin" class="button" @click="$router.push('/admin')">
        Панель администратора
      </button>
      <button
        v-if="schedules()"
        class="button"
        @click="$router.push('/schedules')"
      >
        Графики
      </button>
      <button
        id="seeing_catalog"
        @click="
          prevCatalog = true;
          add = 0;
          catalogMode = true;
        "
        class="button border-2g"
      >
        Посмотеть каталог
      </button>

      <button @click="addTab('new', {})" class="button add">
        Добавить заявку
      </button>
      <div :class="{ hidden: !displayMain }" class="sorting">
        <sort-main @sorting="updateMain"></sort-main>
      </div>
      <!-- <div class="see">
        {{ dataPage }}
      </div> -->
    </div>

    <main v-if="displayMain" id="main">
      <table>
        <tr>
          <th>№</th>
          <th>Город</th>
          <th>Адрес</th>
          <th style="min-width: 9em">Номер</th>
          <th>Статус</th>
          <th>Дата действия</th>
          <th>Заметка</th>
        </tr>
        <tr
          @click="addTab(data.no, data)"
          v-for="data in mainList"
          :key="data.no"
        >
          <td>{{ data.no }}</td>
          <td>{{ data.city }}</td>
          <td>{{ data.street + ", д." + data.house }}</td>
          <td>{{ $acceptNumber(data.number) }}</td>
          <td>{{ data.status }}</td>
          <td>
            {{
              data.status == "Замер"
                ? $getStringDate(data.measuring_date)
                : data.status == "Доставка"
                ? $getStringDate(data.delivery_date)
                : $getStringDate(data.expectation)
            }}
          </td>
          <td>
            <pre>{{ data.note }}</pre>
          </td>
        </tr>
      </table>
    </main>

    <application-body
      v-else
      :key="dataPage.no + Math.random()"
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
import ApplicationBody from "./../Application/ApplicationBody.vue";
import NavTab from "./NavTab.vue";
import SortMain from "./SortMain.vue";

export default {
  components: {
    NavTab,
    CatalogBody,
    ApplicationBody,
    SortMain,
  },
  data() {
    return {
      user: {},
      add: false,
      cites: [],
      toc: [],
      status: [],
      mainList: [],
      selectedTab: "main",
      prevCatalog: false,
      displayMain: true,
      dataPage: {},
      tabs: [],
      sortMainData: [],
      catalogMode: true,
    };
  },
  methods: {
    schedules() {
      return this.user.rights.includes("11") || this.user.rights.includes("12");
    },
    async exit() {
      localStorage.removeItem("user");
      await fetch(this.$connect + "exit.php");
      this.$router.push("login");
    },
    async searchMain(data) {
      if (data.text == "") {
        data = [];
      }
      const f = await fetch(this.$connect + "mainList.php", {
        method: "POST",
        body: JSON.stringify(data),
      });
      const text = await f.json();
      text.forEach((el) => {
        this.mainList.push(el);
      });
    },
    async getLists() {
      this.cites = await fetch(
        this.$connect + "application/getLists.php?cites=0"
      ).then((resp) => resp.json());
      this.status = await fetch(
        this.$connect + "application/getLists.php?status=0"
      ).then((resp) => resp.json());
      this.toc = await fetch(
        this.$connect + "application/getLists.php?toc=0"
      ).then((resp) => resp.json());
    },
    tabClose(tabToClose) {
      if (tabToClose.data.seved) {
        this.tabs = this.tabs.filter((tab) => tab != tabToClose);
        this.updateMain(this.sortMainData);
        this.dataPage = {};
      } else {
        let res = confirm(
          "Заявка не была сохранена на сервере, вы действительно хотите закрыйть ее? В случае закрытия данные будут утеряны."
        );
        if (res) {
          this.tabs = this.tabs.filter((tab) => tab != tabToClose);
          this.updateMain(this.sortMainData);
          this.dataPage = {};
        } else {
          return false;
        }
      }
      this.selectedTab == tabToClose.no
        ? this.selectTab({ no: "main" })
        : false;
    },
    async addProduct(id, type) {
      this.dataPage.seved = false;
      let list = this.dataPage.productList;
      let newKey = 1;
      switch (type) {
        case "Linoleum":
          list.linoleum.push(
            await fetch(
              this.$connect +
                "application/dataProduct.php?id_linoleum=" +
                id +
                "&key=" +
                this.dataPage.lastKey
            ).then((resp) => resp.json())
          );
          this.dataPage.lastKey++;
          break;
        case "Plintusa":
          list.plinth.push(
            await fetch(
              this.$connect +
                "application/dataProduct.php?id_plinth=" +
                id +
                "&key=" +
                this.dataPage.lastKey
            ).then((resp) => resp.json())
          );
          this.dataPage.lastKey++;
          break;
        case "accessories":
          list.accessories.push(
            await fetch(
              this.$connect +
                "application/dataProduct.php?id_accessories=" +
                id +
                "&key=" +
                this.dataPage.lastKey
            ).then((resp) => resp.json())
          );
          this.dataPage.lastKey++;
          break;
        case "Porogi":
          list.doorstep.push(
            await fetch(
              this.$connect +
                "application/dataProduct.php?id_doorstep=" +
                id +
                "&key=" +
                this.dataPage.lastKey
            ).then((resp) => resp.json())
          );
          this.dataPage.lastKey++;
          break;
      }
      for (let i in list) {
        list[i].forEach((product) => {
          product.key = newKey++;
        });
      }
    },
    async updateMain(data = []) {
      this.sortMainData = data;
      this.mainList = [];

      const f = await fetch(this.$connect + "mainList.php", {
        method: "POST",
        body: JSON.stringify(this.sortMainData),
      });
      const text = await f.json();
      text.forEach((el) => {
        this.mainList.push(el);
      });
      if (this.$route.name == "App") {
        this.mainList.forEach((el) => {
          if (el.no == this.$route.params.id) {
            this.addTab(el.no, el);
          }
        });
      }
    },
    async addTab(no, data) {
      switch (no) {
        case "new":
          data = {
            seved: false,
            nick: "",
            initials: "",
            status: "Заявка",
            toc: "Авито",
            number: "",
            note: "",
            city: "",
            street: "",
            house: "",
            corpus: "",
            entrance: "",
            flat: "",
            no: 0,
            square: "0.00",
            weight: "0.00",
            sum: "0.00",
            purchase: "0.00",
            lastKey: 0,
            productList: {
              linoleum: [],
              plinth: [],
              accessories: [],
              doorstep: [],
            },
          };

          break;
        default:
          data.number = this.$acceptNumber(data.number);
          data.seved = true;
          data.productList = {
            linoleum: [],
            plinth: [],
            accessories: [],
            doorstep: [],
          };
          data.productList = await fetch(
            this.$connect + "application/productList.php?id=" + no
          ).then((resp) => resp.json());
          break;
      }
      var tab = { no: no, data: data };
      var list = this.tabs.filter((t) => t.no != tab.no);
      if (list.length == this.tabs.length)
        if (this.tabs.length >= 5) {
          return false;
        } else {
          this.tabs.push(tab);
          this.selectTab(tab);
        }
      else this.selectTab(tab);
    },
    selectTab(tab) {
      this.selectedTab = tab.no;
      switch (tab.no) {
        case "new":
          this.displayApp = true;
          break;
        default:
          this.displayApp = true;
      }
      if (this.selectedTab == "main") {
        this.displayMain = true;
        this.$router.push({ name: "Main" });
      } else {
        this.$router.push({ name: "App", params: { id: tab.no } });
        this.displayMain = false;
        this.dataPage = tab.data;
      }
    },
  },
  created() {
    this.user = JSON.parse(localStorage.user);
    this.updateMain(this.sortMainData);
    this.getLists();
  },
};
</script>
<style lang="scss" src="./../../assets\styles\main.scss"></style>
<style lang="scss" src="./../../assets\styles\navbar.scss"></style>
