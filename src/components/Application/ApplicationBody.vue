<template>
  <main class="page">
    <div class="history" v-show="historyShow">
      <div class="history__row header">
        <div class="history__status_name">Статус</div>
        <div class="history__date">Даты</div>
        <div
          class="history__close"
          @click="
            historyShow = false;
            history = [];
          "
        >
          <img src="./../../assets/images/interface/close.png" alt="" />
        </div>
      </div>
      <template v-if="history.length">
        <div v-for="item in history" :key="item.status" class="history__row">
          <div class="history__status_name">{{ item.name }}</div>
          <div class="history__date">
            c {{ getStringDate(item.start_date) }} по
            {{ getStringDate(item.stop_date) }}
          </div>
        </div>
      </template>
      <div v-else class="history__row">
        <div class="history__none">История отсутствует</div>
      </div>
    </div>
    <div class="start_form">
      <div class="data_client">
        <div class="block">
          <span>Ник</span>
          <div id="nick">
            <input
              tabindex="1"
              autocomplete="false"
              v-model="data.nick"
              placeholder="Введите ник"
              type="text"
            />
          </div>
        </div>
        <div class="block">
          <span>ФИО</span>
          <div id="name">
            <input
              tabindex="2"
              autocomplete="false"
              v-model="data.initials"
              placeholder="Введите ФИО"
              type="text"
            />
          </div>
        </div>
        <div class="block">
          <span>Номер телефона</span>
          <div id="number">
            <input
              tabindex="3"
              autocomplete="false"
              v-model="data.number"
              placeholder="Введите номер телефона"
              type="text"
              @input="data.number = $acceptNumber(data.number)"
            />
          </div>
        </div>
        <div class="block">
          <span>Тип связи</span>
          <div class="select_block">
            <select v-if="toc.length" tabindex="4" v-model="data.toc">
              <option v-for="elem in toc" :key="elem.id" :value="elem.name">
                {{ elem.name }}
              </option>
            </select>
          </div>
        </div>
        <div class="block addres">
          <span>Адрес</span>
          <div class="addres_input">
            <label>
              <div>Город</div>
              <select tabindex="5" v-model="data.city" name="" id="city">
                <option v-for="city in cites" :key="city.id" :value="city.name">
                  {{ city.name }}
                </option>
              </select>
            </label>
            <label>
              <div>Улица</div>
              <input
                tabindex="6"
                autocomplete="false"
                v-model="data.street"
                type="text"
              />
            </label>
            <label>
              <div>Дом</div>
              <input
                tabindex="6"
                autocomplete="false"
                v-model="data.house"
                type="text"
              />
            </label>
            <label>
              <div>Корпус</div>
              <input
                tabindex="6"
                autocomplete="false"
                v-model="data.corpus"
                type="text"
              />
            </label>
            <label>
              <div>Подъезд</div>
              <input
                tabindex="6"
                autocomplete="false"
                v-model="data.entrance"
                type="text"
              />
            </label>
            <label>
              <div>Квартира</div>
              <input
                tabindex="6"
                autocomplete="false"
                v-model="data.flat"
                type="text"
              />
            </label>
          </div>
        </div>
        <div class="block">
          <span>Статус заказа</span>
          <div class="select_block">
            <select v-if="status.length" tabindex="7" v-model="data.status">
              <option
                v-for="status in status"
                :value="status.name"
                :key="status.id"
              >
                {{ status.name }}
              </option>
            </select>
          </div>
        </div>
        <div
          v-if="$parent.user.rights.includes('11') && data.status == 'Замер'"
          class="block"
        >
          <span>Дата замера</span>
          <div>
            <input
              tabindex="7"
              autocomplete="false"
              @dblclick="$event.target.classList.toggle('calendar')"
              v-model="data.measuring_date"
              placeholder="Выберете дату"
              type="date"
              class="calendar"
            />
          </div>
        </div>
        <div
          v-else-if="
            $parent.user.rights.includes('12') &&
            (data.status == 'Доставка' || data.status == 'Завершённый')
          "
          class="block"
        >
          <span>Дата доставки</span>
          <div>
            <input
              tabindex="7"
              autocomplete="false"
              @dblclick="$event.target.classList.toggle('calendar')"
              v-model="data.delivery_date"
              placeholder="Выберете дату"
              :disabled="data.status == 'Завершённый'"
              type="date"
              class="calendar"
            />
          </div>
        </div>
        <div v-else class="block">
          <span>Дата звонка</span>
          <div>
            <input
              tabindex="7"
              autocomplete="false"
              @dblclick="$event.target.classList.toggle('calendar')"
              v-model="data.expectation"
              placeholder="Выберете дату"
              type="date"
              class="calendar"
            />
          </div>
        </div>
      </div>
      <div class="note_block">
        <div class="note" id="note">
          <textarea
            tabindex="8"
            autocomplete="false"
            v-model="data.note"
            placeholder="Введите заметку"
          ></textarea>
        </div>
      </div>
      <div class="buttons">
        <button class="button" @click="openHistory()">История заявки</button>
        <button class="button" @click="addApplication" v-if="data.no == 0">
          Добавить заявку
        </button>
        <button class="button" @click="updateApplication" v-else>
          Сохранить
        </button>
      </div>
    </div>
    <table class="add_request" data-row="table">
      <tr>
        <th>№</th>
        <th>Наименование</th>
        <template v-if="statusInclud()">
          <th>Размер</th>
          <th>Кол-во</th>
        </template>
        <template v-else>
          <th>Ширина</th>
          <th>Длина</th>
          <th>Кол-во</th>
          <th>Ед. изм.</th>
          <th>РРЦ</th>
        </template>
        <th>Цена</th>
        <th>Сумма</th>
        <template v-if="statusInclud()">
          <th>Поставщик</th>
          <th>Зак. цена</th>
          <th>Закуп</th>
        </template>
      </tr>
      <template v-if="data.productList.linoleum.length">
        <tr v-for="product in data.productList.linoleum" :key="product.key">
          <td class="key" @click="removeProduct(product.key)">
            <span>{{ product.key }}</span>
            <img src="./../../assets/images/interface/close.png" alt="" />
          </td>
          <td class="name">{{ product.product }}</td>
          <template v-if="statusInclud()">
            <td>
              {{ product.width.select + " x " + product.len }}
            </td>
            <td>
              {{
                (product.len * product.width.select)
                  .toFixed(2)
                  .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
              }}
              <span v-html="product.ei"></span>
            </td>
          </template>
          <template v-else>
            <td>
              <select
                @change="editPurchasePrice()"
                v-model="product.width.select"
              >
                <option
                  v-for="width in product.width.all"
                  :key="width"
                  :value="width"
                >
                  {{ width }}
                </option>
              </select>
            </td>
            <td>
              <input
                tabindex="9"
                type="text"
                @focus="$event.target.select()"
                v-model="product.len"
                @keydown.prevent.enter="nextInput($event)"
              />
            </td>
            <td>
              {{
                (product.len * product.width.select)
                  .toFixed(2)
                  .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
              }}
            </td>
            <td v-html="product.ei"></td>
            <td>{{ product.price.rrc.selected }}</td>
          </template>

          <td>
            <input
              @keydown.prevent.enter="nextInput($event)"
              class="price"
              tabindex="10"
              type="text"
              @focus="$event.target.select()"
              v-model="product.price.fact"
            />
          </td>
          <td>
            {{
              (product.price.fact * product.len * product.width.select)
                .toFixed(2)
                .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
            }}
          </td>
          <template v-if="statusInclud()">
            <td>
              <select
                @change="
                  product.purchase_price = product.purchase_price_list.filter(
                    (item) =>
                      item.id_provider == $event.target.value &&
                      item.width == product.width.select
                  )[0].purchase_price
                "
                v-model="product.provider"
              >
                <option
                  v-for="item in product.providers"
                  :key="item.id"
                  :value="item.id"
                >
                  {{ item.name }}
                </option>
              </select>
            </td>
            <td>
              <input
                type="text"
                tabindex="11"
                @focus="$event.target.select()"
                @keydown.prevent.enter="nextInput($event)"
                v-model="product.purchase_price"
              />
            </td>
            <td>
              {{
                (product.purchase_price * product.len * product.width.select)
                  .toFixed(2)
                  .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
              }}
            </td>
          </template>
        </tr>
      </template>
      <template v-if="data.productList.plinth.length">
        <tr v-for="product in data.productList.plinth" :key="product.key">
          <td class="key" @click="removeProduct(product.key)">
            <span>{{ product.key }}</span>
            <img src="./../../assets/images/interface/close.png" alt="" />
          </td>

          <template v-if="statusInclud()">
            <td colspan="2" class="name">{{ product.product }}</td>
            <td>{{ product.col_vo }} <span v-html="product.ei"></span></td>
          </template>
          <template v-else>
            <td colspan="3" class="name">{{ product.product }}</td>
            <td>
              <input
                @keydown.prevent.enter="nextInput($event)"
                @focus="$event.target.select()"
                type="text"
                tabindex="9"
                v-model="product.col_vo"
              />
            </td>
            <td v-html="product.ei"></td>
            <td>{{ product.price }}</td>
          </template>

          <td>
            <input
              @keydown.prevent.enter="nextInput($event)"
              @focus="$event.target.select()"
              tabindex="10"
              type="text"
              v-model="product.final_price"
            />
          </td>
          <td>
            {{
              (product.final_price * product.col_vo)
                .toFixed(2)
                .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
            }}
          </td>
          <template v-if="statusInclud()">
            <td>
              <select
                @change="
                  product.purchase_price = product.purchase_price_list.filter(
                    (item) => item.id_provider == $event.target.value
                  )[0].purchase_price
                "
                v-model="product.provider"
              >
                <option
                  v-for="item in product.providers"
                  :key="item.id"
                  :value="item.id"
                >
                  {{ item.name }}
                </option>
              </select>
            </td>
            <td>
              <input
                type="text"
                tabindex="11"
                @focus="$event.target.select()"
                @keydown.prevent.enter="nextInput($event)"
                v-model="product.purchase_price"
              />
            </td>
            <td>
              {{
                (product.purchase_price * product.col_vo)
                  .toFixed(2)
                  .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
              }}
            </td>
          </template>
        </tr>
      </template>
      <template v-if="data.productList.accessories.length">
        <tr v-for="product in data.productList.accessories" :key="product.key">
          <td class="key" @click="removeProduct(product.key)">
            <span>{{ product.key }}</span>
            <img src="./../../assets/images/interface/close.png" alt="" />
          </td>
          <template v-if="statusInclud()">
            <td colspan="2" class="name">{{ product.product }}</td>
            <td>{{ product.col_vo }} <span v-html="product.ei"></span></td>
          </template>
          <template v-else>
            <td colspan="3" class="name">{{ product.product }}</td>

            <td>
              <input
                @keydown.prevent.enter="nextInput($event)"
                @focus="$event.target.select()"
                tabindex="9"
                type="text"
                v-model="product.col_vo"
              />
            </td>
            <td v-html="product.ei"></td>
            <td>{{ product.price }}</td>
          </template>

          <td>
            <input
              @keydown.prevent.enter="nextInput($event)"
              @focus="$event.target.select()"
              tabindex="10"
              type="text"
              v-model="product.final_price"
            />
          </td>
          <td>
            {{ (product.final_price * product.col_vo).toFixed(2) }}
          </td>
          <template v-if="statusInclud()">
            <td>
              <select
                @change="
                  product.purchase_price = product.purchase_price_list.filter(
                    (item) => item.id_provider == $event.target.value
                  )[0].purchase_price
                "
                v-model="product.provider"
              >
                <option
                  v-for="item in product.providers"
                  :key="item.id"
                  :value="item.id"
                >
                  {{ item.name }}
                </option>
              </select>
            </td>
            <td>
              <input
                type="text"
                tabindex="11"
                @focus="$event.target.select()"
                @keydown.prevent.enter="nextInput($event)"
                v-model="product.purchase_price"
              />
            </td>
            <td>
              {{
                (product.purchase_price * product.col_vo)
                  .toFixed(2)
                  .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
              }}
            </td>
          </template>
        </tr>
      </template>
      <template v-if="data.productList.doorstep.length">
        <tr v-for="product in data.productList.doorstep" :key="product.key">
          <td class="key" @click="removeProduct(product.key)">
            <span>{{ product.key }}</span>
            <img src="./../../assets/images/interface/close.png" alt="" />
          </td>
          <template v-if="statusInclud()">
            <td colspan="2" class="name">{{ product.product }}</td>
            <td>{{ product.col_vo }} <span v-html="product.ei"></span></td>
          </template>
          <template v-else>
            <td colspan="3" class="name">{{ product.product }}</td>
            <td>
              <input
                @keydown.prevent.enter="nextInput($event)"
                @focus="$event.target.select()"
                type="text"
                tabindex="9"
                v-model="product.col_vo"
              />
            </td>
            <td v-html="product.ei"></td>
            <td>{{ product.price }}</td>
          </template>
          <td>
            <input
              @keydown.prevent.enter="nextInput($event)"
              @focus="$event.target.select()"
              tabindex="10"
              type="text"
              v-model="product.final_price"
            />
          </td>
          <td>
            {{
              (product.final_price * product.col_vo)
                .toFixed(2)
                .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
            }}
          </td>
          <template v-if="statusInclud()">
            <td>
              <select
                @change="
                  product.purchase_price = product.purchase_price_list.filter(
                    (item) => item.id_provider == $event.target.value
                  )[0].purchase_price
                "
                v-model="product.provider"
              >
                <option
                  v-for="item in product.providers"
                  :key="item.id"
                  :value="item.id"
                >
                  {{ item.name }}
                </option>
              </select>
            </td>
            <td>
              <input
                type="text"
                tabindex="11"
                @focus="$event.target.select()"
                @keydown.prevent.enter="nextInput($event)"
                v-model="product.purchase_price"
              />
            </td>
            <td>
              {{
                (product.purchase_price * product.col_vo)
                  .toFixed(2)
                  .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
              }}
            </td>
          </template>
        </tr>
      </template>
      <tr class="product" id="append_tovar" v-if="!statusInclud()">
        <td>+</td>
        <td class="name" colspan="8">
          <div
            class="open_list open_modal_window"
            data-name-window="product_list"
            @click="
              $parent.prevCatalog = true;
              $parent.add = true;
            "
          >
            Добавить товар
          </div>
        </td>
      </tr>
      <tr class="product">
        <td></td>
        <template v-if="statusInclud()">
          <td colspan="2" class="name">Итог</td>
          <td id="itog">
            {{ data.square.replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} м<sup
              >2</sup
            >
          </td>
          <td id="itog">
            {{ data.weight.replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} кг
          </td>

          <td colspan="2" id="itog">
            Сумма:
            {{ data.sum.replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} &#8381;
          </td>
          <td colspan="2" id="itog">
            Закуп:
            {{ data.purchase.replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} &#8381;
          </td>
        </template>
        <template v-else>
          <td colspan="3" class="name">Итог</td>
          <td colspan="2" id="itog">
            Площадь:
            {{ data.square.replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} м<sup
              >2</sup
            >
          </td>
          <td colspan="2" id="itog">
            Вес: {{ data.weight.replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} кг
          </td>
          <td colspan="2" id="itog">
            Цена:
            {{ data.sum.replace(/\B(?=(\d{3})+(?!\d))/g, " ") }} &#8381;
          </td>
        </template>
      </tr>
    </table>
  </main>
</template>
<script>
async function get(list, path) {
  const f = await fetch(path);
  const data = await f.json();
  data.forEach((el) => {
    list.push(el);
  });
}
export default {
  data() {
    return {
      historyShow: false,
      history: [],
      data: {},
      cites: [],
      toc: [],
      status: [],
      seved: true,
      watch: false,
    };
  },
  methods: {
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
    async openHistory() {
      this.history = await fetch(
        this.$connect + "application/history.php?id=" + this.data.no
      ).then((resp) => resp.json());
      this.historyShow = true;
    },
    statusInclud() {
      switch (this.data.status) {
        case "Уточнение наличия":
          if (this.$parent.user.rights.includes("6")) return true;
          else return false;
        case "Подготовка заказа":
          if (this.$parent.user.rights.includes("7")) return true;
          else return false;
        case "Доставка":
          if (this.$parent.user.rights.includes("8")) return true;
          else return false;
        case "Завершённый":
          if (this.$parent.user.rights.includes("9")) return true;
          else return false;
      }
    },
    getLists() {
      get(this.cites, this.$connect + "application/getLists.php?cites=0");
      get(this.status, this.$connect + "application/getLists.php?status=0");
      get(this.toc, this.$connect + "application/getLists.php?toc=0");
    },
    nextInput(event) {
      let inputs = [];
      if (event.target.getAttribute("tabindex") == 9) {
        inputs = document.querySelectorAll('input[tabindex="9"]');
        for (let i = 0; i < inputs.length; i++) {
          if (inputs[i] == event.target && i < inputs.length - 1) {
            inputs[i + 1].focus();
            return;
          } else if (i == inputs.length - 1) {
            document.querySelectorAll('input[tabindex="10"]')[0].focus();
            return;
          }
        }
      } else if (event.target.getAttribute("tabindex") == 10) {
        inputs = document.querySelectorAll('input[tabindex="10"]');
        for (let i = 0; i < inputs.length; i++) {
          if (inputs[i] == event.target && i != inputs.length - 1) {
            inputs[i + 1].focus();
            return;
          } else if (i == inputs.length - 1) {
            document.querySelectorAll('input[tabindex="11"]')[0].focus();
            return;
          }
        }
      } else if (event.target.getAttribute("tabindex") == 11) {
        inputs = document.querySelectorAll('input[tabindex="11"]');
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
      let sum = 0;
      let rrcKey = "";
      this.data.seved = false;
      this.data.productList.linoleum.forEach((el) => {
        sum += el.width.select * el.len;
      });
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
      this.sum(this.data);
    },
    sum(data = this.data) {
      let sum = 0;
      let weight = 0;
      let square = 0;
      let purchase = 0;
      data.productList.linoleum.forEach((el) => {
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
      data.productList.plinth.forEach((el) => {
        el.price = el.price ? el.price : 0;
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
      data.square = square.toFixed(2);
      data.purchase = purchase.toFixed(2);
      this.watch = true;
    },
    async addApplication() {
      if (this.data.toc == "") {
        alert("Выберете тип связи");
        return false;
      } else if (this.data.nick == "") {
        alert("Введите ник");
        return false;
      } else if (this.data.status == "") {
        alert("Выберете статус заказа");
        return false;
      }
      this.watch = false;
      this.data.seved = true;
      this.data.add = true;
      this.data.number = this.data.number.replace(/[\s()-]/g, "");
      const text = await fetch(
        this.$connect + "application/updateApplication.php",
        {
          method: "POST",
          body: JSON.stringify(this.data),
        }
      ).then((resp) => resp.json());
      let app = this.$parent.$parent.$parent;
      if (text.active) {
        app.modal = text;
      } else {
        this.data = text.dataPage;
        app.modal = text.modal;
        this.$parent.tabs = this.$parent.tabs.filter((tab) => tab.no != "new");
        this.$parent.addTab(this.data.no, this.data);
        this.$parent.updateMain(this.$parent.sortMainData);
      }

      setTimeout(function () {
        app.modal.active = false;
      }, 5000);
      this.watch = true;
    },
    async updateApplication() {
      if (this.data.toc == "") {
        alert("Выберете тип связи");
        return false;
      } else if (this.data.nick == "") {
        alert("Введите ник");
        return false;
      } else if (this.data.status == "") {
        alert("Выберете статус заказа");
        return false;
      }
      this.watch = false;
      this.data.update = true;
      this.data.seved = true;
      this.data.number = this.data.number.replace(/[\s()-]/g, "");
      const text = await fetch(
        this.$connect + "application/updateApplication.php",
        {
          method: "POST",
          body: JSON.stringify(this.data),
        }
      ).then((resp) => resp.json());
      this.data = text.dataPage;
      let app = this.$parent.$parent.$parent;
      app.modal = text.modal;
      setTimeout(function () {
        app.modal.active = false;
      }, 5000);
      this.$parent.updateMain(this.$parent.sortMainData);
      this.watch = true;
    },
  },
  async created() {
    this.getLists();
    this.data = this.dataPage;
    this.acceptNumber;
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
            this.editSelectedRRC();
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
