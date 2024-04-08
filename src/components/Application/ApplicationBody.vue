<template>
  <main class="page">
    <section class="application">
      <section class="application_client-data client-data">
        <h3 class="client-data_heading">Данные о клиенте</h3>
        <div class="client-data_row">
          <input
            class="client-data_input"
            tabindex="1"
            id="nick"
            required
            autocomplete="false"
            v-model="data.nick"
            type="text"
          />
          <label class="client-data_placeholder" for="nick">Ник | Имя</label>
        </div>
        <div class="client-data_row">
          <input
            class="client-data_input"
            tabindex="2"
            id="number"
            autocomplete="false"
            v-model="data.number"
            type="text"
            required
            @input="data.number = $acceptNumber(data.number)"
          />
          <label class="client-data_placeholder" for="number"
            >Номер телефона</label
          >
        </div>
        <div class="client-data_row">
          <input
            class="client-data_input"
            id="no_order_1c"
            tabindex="3"
            autocomplete="false"
            v-model="data.no_order_1c"
            placeholder=""
            type="text"
            required
          />
          <label class="client-data_placeholder" for="no_order_1c"
            >Номер заказа 1С</label
          >
        </div>
        <div class="client-data_row">
          <select
            required
            class="client-data_select"
            id="conection"
            v-if="GET_TOC.length"
            tabindex="6"
            v-model="data.connection"
          >
            <option disabled></option>
            <option v-for="elem in GET_TOC" :key="elem.id" :value="elem.id">
              {{ elem.name }}
            </option>
          </select>
          <label class="client-data_placeholder" for="conection"
            >Тип связи</label
          >
        </div>
        <div class="client-data_row">
          <select
            required
            class="client-data_select"
            id="payment"
            v-if="GET_PAYMENTS.length"
            v-model="data.payment_method"
            tabindex="4"
            type="text"
          >
            <option value="" disabled></option>
            <option
              v-for="elem in GET_PAYMENTS"
              :key="elem.id"
              :value="elem.id"
            >
              {{ elem.name }}
            </option>
          </select>
          <label class="client-data_placeholder">Способ оплаты</label>
        </div>
        <div class="client-data_row">
          <div class="client-data_row">
            <select
              required
              class="client-data_select"
              v-if="GET_CITES.length"
              tabindex="7"
              v-model="data.city"
              id="city"
            >
              <option v-for="city in GET_CITES" :key="city.id" :value="city.id">
                {{ city.name }}
              </option>
            </select>
            <label class="client-data_placeholder">Город</label>
          </div>
          <div class="client-data_row">
            <input
              required
              class="client-data_input"
              tabindex="8"
              autocomplete="false"
              v-model="data.street"
              type="text"
            />
            <label class="client-data_placeholder">Улица</label>
          </div>
          <div class="client-data_row client-data_row--inside">
            <div class="client-data_ceil">
              <input
                required
                class="client-data_input"
                tabindex="9"
                autocomplete="false"
                v-model="data.house"
                type="text"
              />
              <label class="client-data_placeholder">Дом</label>
            </div>
            <div class="client-data_ceil">
              <input
                required
                class="client-data_input"
                tabindex="10"
                autocomplete="false"
                v-model="data.flat"
                type="text"
              /><label class="client-data_placeholder">Квартира</label>
            </div>
          </div>
          <div class="client-data_row client-data_row--inside">
            <div class="client-data_ceil">
              <input
                required
                class="client-data_input"
                tabindex="11"
                autocomplete="false"
                v-model="data.corpus"
                type="text"
              /><label class="client-data_placeholder">Корпус</label>
            </div>
            <div class="client-data_ceil">
              <input
                required
                class="client-data_input"
                tabindex="12"
                autocomplete="false"
                v-model="data.entrance"
                type="text"
              /><label class="client-data_placeholder">Подъезд</label>
            </div>
          </div>
        </div>
        <div
          class="client-data_row client-data_row--inside"
          v-if="$parent.user.rights.includes('11') && data.status == 2"
        >
          <div class="client-data_ceil">
            <input
              required
              class="client-data_input"
              tabindex="14"
              autocomplete="false"
              @dblclick="$event.target.classList.toggle('calendar')"
              v-model="data.measuring_date"
              @focus="$event.target.type = 'date'"
              @blur="
                $event.target.value == ''
                  ? ($event.target.type = 'text')
                  : false
              "
              :type="data.measuring_date == null ? 'text' : 'date'"
            /><label class="client-data_placeholder">Дата замера</label>
          </div>
          <div class="client-data_ceil">
            <input
              required
              class="client-data_input"
              tabindex="15"
              autocomplete="false"
              @dblclick="$event.target.classList.toggle('calendar')"
              v-model="data.measuring_time"
              placeholder="Выберете время"
              @focus="$event.target.type = 'time'"
              @blur="
                $event.target.value == ''
                  ? ($event.target.type = 'text')
                  : false
              "
              :type="data.measuring_time == null ? 'text' : 'time'"
            /><label class="client-data_placeholder">Подъезд</label>
          </div>
        </div>
        <div
          class="client-data_row client-data_row--inside"
          v-else-if="
            $parent.user.rights.includes('12') &&
            [5, 6, 7].includes(Number(data.status))
          "
        >
          <div class="client-data_ceil">
            <input
              required
              class="client-data_input"
              tabindex="14"
              autocomplete="false"
              @dblclick="$event.target.classList.toggle('calendar')"
              v-model="data.delivery_date"
              :disabled="data.status == 'Завершённый'"
              @focus="$event.target.type = 'date'"
              @blur="
                $event.target.value == ''
                  ? ($event.target.type = 'text')
                  : false
              "
              :type="data.delivery_date == null ? 'text' : 'date'"
            /><label class="client-data_placeholder">Дата доставки</label>
          </div>
          <div class="client-data_ceil">
            <input
              required
              class="client-data_input"
              tabindex="15"
              autocomplete="false"
              v-model="data.delivery_time"
              :disabled="data.status == 'Завершённый'"
              @focus="$event.target.type = 'time'"
              @blur="
                $event.target.value == ''
                  ? ($event.target.type = 'text')
                  : false
              "
              :type="data.delivery_time == null ? 'text' : 'time'"
            /><label class="client-data_placeholder">Время доставки</label>
          </div>
        </div>
        <div v-else class="client-data_row">
          <input
            required
            class="client-data_input"
            tabindex="14"
            autocomplete="false"
            v-model="data.expectation"
            @focus="$event.target.type = 'date'"
            @blur="
              $event.target.value == '' ? ($event.target.type = 'text') : false
            "
            :type="data.expectation == null ? 'text' : 'date'"
          /><label class="client-data_placeholder">Дата поторной связи</label>
        </div>
        <div class="client-data_row">
          <select
            required
            class="client-data_select"
            id="conection"
            v-if="GET_STATUSES.length"
            tabindex="13"
            v-model="data.status"
          >
            <option
              v-for="status in GET_STATUSES"
              :value="status.id"
              :key="status.id"
            >
              {{ status.name }}
            </option>
          </select>
          <label class="client-data_placeholder" for="conection"
            >Статус заказа</label
          >
        </div>
      </section>
      <section class="application_poducts-table poducts-table"></section>
    </section>

    <main-load
      v-if="load"
      style="
        position: absolute;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.2);
      "
    ></main-load>
  </main>
</template>
<style lang="scss" scoped src="@/assets/styles/application.scss"></style>
<script>
import { mapGetters, mapMutations } from "vuex";
import MainLoad from "../MainLoad.vue";
import api from "../../api";

export default {
  data() {
    return {
      data: {},
      watch: false,
      load: false,
    };
  },
  components: {
    MainLoad,
  },
  methods: {
    ...mapMutations({ openModal: "setDataModalWindow" }),
    getStringDate(dateString) {
      if (dateString == "" || dateString == undefined) return "настоящее время";
      var options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
      };
      var seconds = Date.parse(dateString);
      var date = new Date(seconds);
      return date.toLocaleString("ru", options);
    },
    statusInclud() {
      switch (this.data.status) {
        case "4":
          if (this.$parent.user.rights.includes("6")) return true;
          else return false;
        case "5":
          if (this.$parent.user.rights.includes("7")) return true;
          else return false;
        case "6":
          if (this.$parent.user.rights.includes("8")) return true;
          else return false;
        case "7":
          if (this.$parent.user.rights.includes("9")) return true;
          else return false;
      }
    },
    nextInput(event) {
      let inputs = [];
      if (event.target.getAttribute("tabindex") == 17) {
        inputs = document.querySelectorAll('input[tabindex="17"]');
        for (let i = 0; i < inputs.length; i++) {
          if (inputs[i] == event.target && i < inputs.length - 1) {
            inputs[i + 1].focus();
            return;
          } else if (i == inputs.length - 1) {
            document.querySelectorAll('input[tabindex="18"]')[0].focus();
            return;
          }
        }
      } else if (event.target.getAttribute("tabindex") == 18) {
        inputs = document.querySelectorAll('input[tabindex="18"]');
        for (let i = 0; i < inputs.length; i++) {
          if (inputs[i] == event.target && i != inputs.length - 1) {
            inputs[i + 1].focus();
            return;
          } else if (i == inputs.length - 1) {
            document.querySelectorAll('input[tabindex="19"]')[0].focus();
            return;
          }
        }
      } else if (event.target.getAttribute("tabindex") == 19) {
        inputs = document.querySelectorAll('input[tabindex="19"]');
        for (let i = 0; i < inputs.length; i++) {
          if (inputs[i] == event.target && i != inputs.length - 1) {
            inputs[i + 1].focus();
            return;
          }
        }
      }
    },
    removeProduct(key) {
      let list = this.data.productList;
      let newKey = 1;
      this.data.seved = false;
      for (let i in list) {
        list[i] = list[i].filter((product) => product.key != key);
        list[i].forEach((product) => {
          product.key = newKey++;
        });
      }
      this.data.lastKey--;
    },
    editPurchasePrice() {
      this.data.productList.linoleum.forEach((el) => {
        let block = el;
        el.purchase_price = el.purchase_price_list.filter(
          (i) =>
            i.width == block.width.select && i.id_provider == block.provider
        )[0].purchase_price;
      });
    },
    editSelectedRRC() {
      this.watch = false;
      let sum = this.data.square;
      let rrcKey = "";
      this.data.seved = false;
      if (sum > 0 && sum <= 10) rrcKey = "p0_10";
      else if (sum <= 15) rrcKey = "p11_15";
      else if (sum <= 20) rrcKey = "p16_20";
      else if (sum <= 30) rrcKey = "p21_30";
      else if (sum <= 40) rrcKey = "p31_40";
      else if (sum <= 55) rrcKey = "p41_55";
      else rrcKey = "p56_70";
      this.data.productList.linoleum.forEach((el) => {
        el.price.rrc.selected = el.price.rrc.all[rrcKey];
      });
    },
    sum(data = this.data) {
      let sum = 0;
      let weight = 0;
      let square = 0;
      let purchase = 0;
      data.productList.linoleum.forEach((el) => {
        el.price.fact = String(el.price.fact).replace(/,/, ".");
        el.len = String(el.len).replace(/,/, ".");
        el.purchase_price = String(el.purchase_price).replace(/,/, ".");

        if (
          el.price.fact < el.price.rrc.selected - el.price.rrc.selected * 0.1 &&
          !this.$parent.user.rights.includes("10")
        ) {
          el.price.fact = el.price.rrc.selected - el.price.rrc.selected * 0.1;
        }

        sum += el.width.select * el.len * el.price.fact;
        weight += el.width.select * el.len * el.weight;
        square += el.width.select * el.len;
        purchase += el.width.select * el.len * el.purchase_price;
      });
      data.square = square.toFixed(2);
      this.editSelectedRRC();
      data.productList.plinth.forEach((el) => {
        el.price = el.price ? el.price : 0;
        el.final_price = String(el.final_price).replace(/,/, ".");
        el.purchase_price = String(el.purchase_price).replace(/,/, ".");
        if (
          el.final_price < el.price - el.price.price * 0.1 &&
          !this.$parent.user.rights.includes("10")
        ) {
          el.final_price = el.price - el.price.price * 0.1;
        }
        sum += el.col_vo * el.final_price;
        weight += el.col_vo * el.weight;
        purchase += el.col_vo * el.purchase_price;
      });
      data.productList.accessories.forEach((el) => {
        el.price = el.price ? el.price : 0;
        el.final_price = String(el.final_price).replace(/,/, ".");
        el.purchase_price = String(el.purchase_price).replace(/,/, ".");
        if (
          el.final_price < el.price - el.price.price * 0.1 &&
          !this.$parent.user.rights.includes("10")
        ) {
          el.final_price = el.price - el.price.price * 0.1;
        }
        sum += el.col_vo * el.final_price;
        purchase += el.col_vo * el.purchase_price;
      });
      data.productList.doorstep.forEach((el) => {
        el.price = el.price ? el.price : 0;
        el.final_price = String(el.final_price).replace(/,/, ".");
        el.purchase_price = String(el.purchase_price).replace(/,/, ".");
        if (
          el.final_price < el.price - el.price.price * 0.1 &&
          !this.$parent.user.rights.includes("10")
        ) {
          el.final_price = el.price - el.price.price * 0.1;
        }
        sum += el.col_vo * el.final_price;
        purchase += el.col_vo * el.purchase_price;
      });
      data.productList.related.forEach((el) => {
        el.price = el.price ? el.price : 0;
        el.final_price = String(el.final_price).replace(/,/, ".");
        el.purchase_price = String(el.purchase_price).replace(/,/, ".");
        if (
          el.final_price < el.price - el.price.price * 0.1 &&
          !this.$parent.user.rights.includes("10")
        ) {
          el.final_price = el.price - el.price.price * 0.1;
        }
        sum += el.col_vo * el.final_price;
        purchase += el.col_vo * el.purchase_price;
      });

      data.sum = sum.toFixed(2);
      data.weight = weight.toFixed(2);
      data.purchase = purchase.toFixed(2);

      this.watch = true;
    },
    async addApplication() {
      if (!this.data.connection) {
        alert("Выберете тип связи");
        return false;
      } else if (!this.data.nick) {
        alert("Введите ник");
        return false;
      } else if (!this.data.status) {
        alert("Выберете статус заказа");
        return false;
      } else if (!this.data.city) {
        alert("Выберете город");
        return false;
      }

      this.load = true;
      this.watch = false;
      this.data.add = true;
      this.data.number = this.data.number.replace(/[\s()-]/g, "");

      const text = await api.post(
        "application/updateApplication.php",
        this.data
      );

      if (text.status == 200) {
        this.data.seved = true;
        var tab = this.$parent.tabs.filter((tab) => tab.id == "new")[0];
        tab.id = text.data.dataPage.id;
        tab.data = text.data.dataPage;
        this.$parent.selectTab(tab);
        this.load = false;
        this.$ws.send(
          JSON.stringify({ type: "application", data: text.data.dataPage.id })
        );
      } else {
        console.error(text.data.text);
      }
      this.openModal(text.data.modal);
      this.watch = true;
    },
    async updateApplication() {
      if (this.data.conected) {
        alert("Выберете тип связи");
        return false;
      } else if (!this.data.nick) {
        alert("Введите ник");
        return false;
      } else if (!this.data.status) {
        alert("Выберете статус заказа");
        return false;
      } else if (!this.data.city) {
        alert("Выберете город");
        return false;
      }
      this.load = true;
      this.watch = false;
      this.data.update = true;
      this.data.seved = true;
      this.data.number = this.data.number.replace(/[\s()-]/g, "");

      const text = await api.post(
        "application/updateApplication.php",
        this.data
      );
      if (text.status == 200) {
        this.data = text.data.dataPage;
        this.data.number = this.$acceptNumber(this.data.number);
        this.watch = true;
        this.load = false;
        this.$ws.send(
          JSON.stringify({ type: "application", data: this.data.id })
        );
      } else {
        console.error(text.data.text);
      }
      console.log(text.data);
      this.openModal(text.data.modal);
    },
  },
  computed: {
    ...mapGetters([
      "GET_CITES",
      "GET_STATUSES",
      "GET_TOC",
      "GET_PAYMENTS",
      "GET_MAIN_LIST",
    ]),
  },
  created() {
    this.data = this.dataPage;
  },
  mounted() {
    this.watch = true;
  },
  watch: {
    data: {
      deep: true,
      handler(newVal, oldVal) {
        if (this.watch) {
          if (oldVal != {}) {
            this.sum();
          }
        }
      },
    },
  },
  props: {
    dataPage: Object,
  },
};
</script>
