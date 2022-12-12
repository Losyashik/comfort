<template>
  <div>
    <div class="schedules_body">
      <div class="header">
        График
        <templeat v-if="$route.params.schedules == 'measuring'"
          >замеров</templeat
        ><templeat v-else-if="$route.params.schedules == 'delivery'"
          >доставок</templeat
        >
        на <input type="date" v-model="date" @change="getList()" />
        <span class="date">{{ getStringDate(date) }}</span>
      </div>
      <div class="drag" v-if="date" @dragover.prevent="drag($event)">
        <template v-if="list.length">
          <div
            class="block"
            @dragstart="$event.target.classList.add('selected')"
            @dragend="$event.target.classList.remove('selected')"
            draggable="true"
            v-for="item in list"
            :key="item.id"
          >
            <div class="harm">
              <div class="line"></div>
              <div class="line"></div>
              <div class="line"></div>
            </div>
            <div class="info">
              <div class="contacts">
                <ul>
                  <li>Город: {{ item.city }}</li>
                  <li>Адрес: {{ item.addres }}</li>
                  <li v-if="item.name">Имя: {{ item.name }}</li>
                  <li v-else-if="item.nick">Ник: {{ item.nick }}</li>
                  <li>Номер: {{ item.number }}</li>
                  <li>№ заявки: {{ item.id }}</li>
                </ul>
              </div>
              <div class="note" v-if="item.note">
                <h5>Заметка</h5>
                <pre>{{ item.note }}</pre>
              </div>
            </div>
            <div class="time">
              <input type="time" v-model="item.time" /><span>{{
                getTime(item.time)
              }}</span>
            </div>
          </div>
        </template>
        <div class="none_item" v-else>
          <div>
            На данную дату нет назначеных
            <templeat v-if="$route.params.schedules == 'measuring'"
              >замеров</templeat
            ><templeat v-else-if="$route.params.schedules == 'delivery'"
              >доставок</templeat
            >
          </div>
        </div>
      </div>
      <div class="drag" v-else>
        <div class="none_date">
          <div>Не выбрана дата</div>
        </div>
      </div>
    </div>
    <div class="receipts" v-if="type == 'delivery' && list.length">
      <template v-for="item in list" :key="item.id">
        <div class="receipt">
          <div class="info">
            <table>
              <tr>
                <td class="name">Заявка №</td>
                <td>{{ item.id }}</td>
              </tr>
              <tr v-if="item.name">
                <td class="name">Имя</td>
                <td>{{ item.name }}</td>
              </tr>
              <tr v-else-if="item.nick">
                <td class="name">Ник</td>
                <td>{{ item.nick }}</td>
              </tr>
              <tr>
                <td class="name">Номер тел.</td>
                <td>{{ item.number }}</td>
              </tr>
              <tr>
                <td class="name">Адрес</td>
                <td>{{ "г." + item.city + ", " + item.addres }}</td>
              </tr>
              <tr>
                <td class="name">Дата</td>
                <td>{{ getStringDate(date) }}</td>
              </tr>
            </table>
          </div>
          <div class="list">
            <table>
              <tr>
                <th>№</th>
                <th>Наименование</th>
                <th>Кол-во</th>
                <th>Цена</th>
                <th>Итог</th>
              </tr>
              <tr v-for="el in item.product_list" :key="el.id">
                <td>{{ el.key }}</td>
                <td>{{ el.name }}</td>
                <td class="center">
                  {{ el.col_vo }}
                </td>
                <td class="center">{{ el.final_price }}</td>
                <td class="center">
                  {{
                    (el.final_price * el.col_vo)
                      .toFixed(2)
                      .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
                  }}
                </td>
              </tr>
              <tr>
                <td class="row" colspan="3"></td>
                <td class="table_itog">Итог:</td>
                <td class="center sum">
                  {{
                    parseFloat(item.sum)
                      .toFixed(2)
                      .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
                  }}
                </td>
              </tr>
            </table>
          </div>
          <div class="itog">Итог: {{ priceToText(item.sum) }}</div>
        </div>
        <div class="receipt">
          <div class="info">
            <table>
              <tr>
                <td class="name">Заявка №</td>
                <td>{{ item.id }}</td>
              </tr>
              <tr v-if="item.name">
                <td class="name">Имя</td>
                <td>{{ item.name }}</td>
              </tr>
              <tr v-else-if="item.nick">
                <td class="name">Ник</td>
                <td>{{ item.nick }}</td>
              </tr>
              <tr>
                <td class="name">Номер тел.</td>
                <td>{{ item.number }}</td>
              </tr>
              <tr>
                <td class="name">Адрес</td>
                <td>{{ "г." + item.city + ", " + item.addres }}</td>
              </tr>
              <tr>
                <td class="name">Дата</td>
                <td>{{ getStringDate(date) }}</td>
              </tr>
            </table>
          </div>
          <div class="list">
            <table>
              <tr>
                <th>№</th>
                <th>Наименование</th>
                <th>Кол-во</th>
                <th>Цена</th>
                <th>Итог</th>
              </tr>
              <tr v-for="el in item.product_list" :key="el.id">
                <td>{{ el.key }}</td>
                <td>{{ el.name }}</td>
                <td class="center">
                  {{ el.col_vo }}
                </td>
                <td class="center">{{ el.final_price }}</td>
                <td class="center">
                  {{
                    (el.final_price * el.col_vo)
                      .toFixed(2)
                      .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
                  }}
                </td>
              </tr>
              <tr>
                <td class="row" colspan="3"></td>
                <td class="table_itog">Итог:</td>
                <td class="center sum">
                  {{
                    parseFloat(item.sum)
                      .toFixed(2)
                      .replace(/\B(?=(\d{3})+(?!\d))/g, " ")
                  }}
                </td>
              </tr>
            </table>
          </div>
          <div class="itog">Итог: {{ priceToText(item.sum) }}</div>
        </div>
      </template>
    </div>
  </div>
</template>
<script>
import { mapMutations } from "vuex";
function number_to_string(_number) {
  var _arr_numbers = new Array();
  _arr_numbers[1] = new Array(
    "",
    "один",
    "два",
    "три",
    "четыре",
    "пять",
    "шесть",
    "семь",
    "восемь",
    "девять",
    "десять",
    "одиннадцать",
    "двенадцать",
    "тринадцать",
    "четырнадцать",
    "пятнадцать",
    "шестнадцать",
    "семнадцать",
    "восемнадцать",
    "девятнадцать"
  );
  _arr_numbers[2] = new Array(
    "",
    "",
    "двадцать",
    "тридцать",
    "сорок",
    "пятьдесят",
    "шестьдесят",
    "семьдесят",
    "восемьдесят",
    "девяносто"
  );
  _arr_numbers[3] = new Array(
    "",
    "сто",
    "двести",
    "триста",
    "четыреста",
    "пятьсот",
    "шестьсот",
    "семьсот",
    "восемьсот",
    "девятьсот"
  );
  function number_parser(_num, _desc) {
    var _string = "";
    var _num_hundred = "";
    if (_num.length == 3) {
      _num_hundred = _num.substr(0, 1);
      _num = _num.substr(1, 3);
      _string = _arr_numbers[3][_num_hundred] + " ";
    }
    if (_num < 20) _string += _arr_numbers[1][parseFloat(_num)] + " ";
    else {
      var _first_num = _num.substr(0, 1);
      var _second_num = _num.substr(1, 2);
      _string +=
        _arr_numbers[2][_first_num] + " " + _arr_numbers[1][_second_num] + " ";
    }
    switch (_desc) {
      case 0:
        var _last_num = parseFloat(_num.substr(-1));
        if (_last_num == 1) _string += "рубль";
        else if (_last_num > 1 && _last_num < 5) _string += "рубля";
        else _string += "рублей";
        break;
      case 1:
        _last_num = parseFloat(_num.substr(-1));
        if (_last_num == 1) _string += "тысяча ";
        else if (_last_num > 1 && _last_num < 5) _string += "тысячи ";
        else _string += "тысяч ";
        _string = _string.replace("один ", "одна ");
        _string = _string.replace("два ", "две ");
        break;
      case 2:
        _last_num = parseFloat(_num.substr(-1));
        if (_last_num == 1) _string += "миллион ";
        else if (_last_num > 1 && _last_num < 5) _string += "миллиона ";
        else _string += "миллионов ";
        break;
      case 3:
        _last_num = parseFloat(_num.substr(-1));
        if (_last_num == 1) _string += "миллиард ";
        else if (_last_num > 1 && _last_num < 5) _string += "миллиарда ";
        else _string += "миллиардов ";
        break;
    }
    _string = _string.replace("  ", " ");
    return _string;
  }
  function decimals_parser(_num) {
    var _first_num = _num.substr(0, 1);
    var _second_num = parseFloat(_num.substr(1, 2));
    var _string = " " + _first_num + _second_num;
    if (_second_num == 1) _string += " копейка";
    else if (_second_num > 1 && _second_num < 5) _string += " копейки";
    else _string += " копеек";
    return _string;
  }
  if (!_number || _number == 0) return false;
  if (typeof _number !== "number") {
    _number = _number.replace(",", ".");
    _number = parseFloat(_number);
    if (isNaN(_number)) return false;
  }
  _number = _number.toFixed(2);
  if (_number.indexOf(".") != -1) {
    var _number_arr = _number.split(".");
    _number = _number_arr[0];
    var _number_decimals = _number_arr[1];
  }
  var _number_length = _number.length;
  var _string = "";
  var _num_parser = "";
  var _count = 0;
  for (var _p = _number_length - 1; _p >= 0; _p--) {
    var _num_digit = _number.substr(_p, 1);
    _num_parser = _num_digit + _num_parser;
    if (
      (_num_parser.length == 3 || _p == 0) &&
      !isNaN(parseFloat(_num_parser))
    ) {
      _string = number_parser(_num_parser, _count) + _string;
      _num_parser = "";
      _count++;
    }
  }
  if (_number_decimals) _string += decimals_parser(_number_decimals);
  return _string;
}
export default {
  data() {
    return {
      type: "",
      date: "",
      list: [],
    };
  },
  methods: {
    ...mapMutations["updateModal"],
    priceToText(price) {
      return number_to_string(price);
    },
    drag(e) {
      const getNextItem = (cursorPosition, currentElement) => {
        let i = 0;
        while (i == 0) {
          if (currentElement.classList.contains("block")) i = 1;
          else currentElement = currentElement.parentNode;
        }
        const currentElementCoord = currentElement.getBoundingClientRect();
        const currentElementCenter =
          currentElementCoord.y + currentElementCoord.height / 2;
        let nextElement =
          cursorPosition > currentElementCenter
            ? currentElement
            : currentElement.nextElementSibling;

        return nextElement;
      };
      let tasksListElement = document.querySelector(".drag");
      let activeItem = tasksListElement.querySelector(".selected");
      let current = e.target;
      if (activeItem !== current && current.classList.contains("block")) {
        return;
      }
      let nextItem = getNextItem(e.clientY, current);
      if (
        (nextItem && activeItem === nextItem.previousElementSibling) ||
        activeItem === nextItem
      ) {
        return;
      }
      tasksListElement.insertBefore(activeItem, nextItem);
    },
    getTime(time) {
      if (time == "") return;
      var options = {
        hour: "numeric",
        minute: "numeric",
      };
      var seconds = Date.parse(this.date + "T" + time);

      var date = new Date(seconds);
      return date.toLocaleString("ru", options);
    },
    getStringDate(dateString) {
      if (dateString == "") return;
      var options = {
        year: "numeric",
        month: "long",
        day: "numeric",
      };
      var seconds = Date.parse(dateString);
      var date = new Date(seconds);
      return date.toLocaleString("ru", options);
    },
    async getList() {
      this.list = await fetch(
        this.$connect +
          "schedules/lists.php?type=" +
          this.type +
          "&date=" +
          this.date
      ).then((response) => response.json());
    },
  },
  mounted() {
    if (this.$route.params.schedules == "measuring") {
      document.querySelector("title").innerHTML = "График замеров";
      this.type = "measuring";
    } else if (this.$route.params.schedules == "delivery") {
      document.querySelector("title").innerHTML = "График доставок";
      this.type = "delivery";
    } else {
      this.$router.push({ name: "main" });
    }
  },
  props: ["trigger"],
  watch: {
    trigger: async function () {
      let data = new FormData();
      data.append("type", this.type);
      this.list.forEach((el, index) => {
        data.append("list[" + index + "][id]", el.id);
        data.append("list[" + index + "][time]", el.time);
      });
      let resp = await fetch(this.$connect + "schedules/save.php", {
        method: "POST",
        body: data,
      }).then((response) => response.json());
      this.updateModal(resp);
    },
  },
};
</script>
<style
  scoped
  lang="scss"
  src="./../../assets/styles/schedules/schedules.scss"
></style>
