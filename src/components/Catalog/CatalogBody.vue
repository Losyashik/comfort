<template>
  <main class="catalog_body">
    <div class="catalog_product_body">
      <header class="product_type">
        <ul>
          <li
            v-for="pType in productType"
            :key="pType.type"
            @click="selectType(pType.type)"
            :class="{ selected: pType.selected }"
          >
            <a class="catalog_button"> {{ pType.name }} </a>
          </li>
        </ul>

        <div class="close_box">
          <img
            @click="
              $emit('close');
              tabTipe({ catalog: [], type: type });
            "
            src="./../../assets/images/interface/close.png"
            alt=""
          />
        </div>
      </header>
      <nav class="sort">
        <product-sorting
          :key="type"
          :type="type"
          @sorting="sortProducts"
        ></product-sorting>
      </nav>
      <div
        v-if="catalogMode"
        class="catalog_product_list"
        @scroll="scroll($event)"
      >
        <template v-if="GET_LIST.length">
          <template v-if="!GET_LIST[0].text">
            <article
              v-for="product in GET_LIST.slice(0, count)"
              :key="product.id"
              @click="openPreview(product)"
              class="catalog_product"
              :class="{ mini: !product.images }"
            >
              <img
                v-if="product.images"
                :src="$images + product.images[0]"
                alt=""
              />
              <h4>{{ product.name }}</h4>
              <button
                v-if="previewAdd"
                class="add"
                @click.stop="$emit('addProduct', product.id, type)"
              >
                Добавить
              </button>
            </article>
          </template>
          <template v-else>
            <h2>{{ GET_LIST[0].text }}</h2>
          </template>
        </template>
        <template v-else>
          <main-load></main-load>
        </template>
      </div>
      <div v-else class="warehouse_product_list">
        <table>
          <tr>
            <th>Название</th>
            <th>{{ type == "Linoleum" ? "Размер" : "Кол-во" }}</th>
            <th>Добавить</th>
          </tr>
          <tr v-for="item in productList" :key="item.id">
            <td>{{ item.name }}</td>
            <td>{{ type == "Linoleum" ? item.size : item.col_vo }}</td>
            <td>
              <button
                v-if="previewAdd"
                class="add"
                @click.stop="$emit('addProductFromWarehouse', item.id, type)"
              >
                Добавить
              </button>
            </td>
          </tr>
        </table>
      </div>
    </div>
    <div v-show="preview" class="catalog_preview_product">
      <template v-if="preview">
        <header>
          <button @click="closePreview" class="back">Назад</button>
          <h2>{{ prevObject.name }}</h2>
        </header>
        <div class="slider">
          <div class="slider_body">
            <div v-for="src in prevObject.images" :key="src" class="item">
              <img :src="$images + src" alt="" />
            </div>
          </div>
        </div>
        <div v-if="type == 'linoleum'" class="list">
          <div class="column">
            <h5>Характеристики</h5>
            <div class="li">Класс применения: {{ prevObject.class }}</div>
            <div class="li">
              Толщина покрытия,общая: {{ prevObject.totalThickness }}
            </div>
            <div class="li">Тип линолиума: {{ prevObject.type }}</div>
            <div class="li">
              Толщина защитного слоя: {{ prevObject.protective }}
            </div>
            <div class="li">Основа линолиума: {{ prevObject.base }}</div>
          </div>
          <div class="column">
            <h5>Основная информация</h5>
            <div class="li">Производитель: {{ prevObject.maker }}</div>
            <div class="li">Колекция: {{ prevObject.collection }}</div>
            <div class="li">Предназначение: {{ prevObject.destination }}</div>
            <div class="li">
              Ширины рулонов:
              <span v-for="width in prevObject.width" :key="width.id">
                {{ width.width }}</span
              >
            </div>
          </div>
          <div class="column">
            <h5>Калькулятор РРЦ</h5>
            <div class="li">
              <input
                type="number"
                v-model="s"
                @keydown.enter="addPrice(s)"
                placeholder="Введите площадь"
              />
              <button @click="addPrice(s)">Получить цену</button>
            </div>
            <div class="li">Цена: {{ prevObject.price.selected }}</div>
            <div class="li">
              <button
                v-if="previewAdd"
                class="add"
                @click.stop="$emit('addProduct', prevObject, type)"
              >
                Добавить
              </button>
            </div>
          </div>
        </div>
        <div v-if="type == 'plinth'" class="list">
          <div class="column">
            <h5>Характеристики</h5>
            <div class="li">Производитель: {{ prevObject.maker }}</div>
            <div class="li">Колекция: {{ prevObject.collection }}</div>
            <div class="li">Ширина: {{ prevObject.width }}</div>
            <div class="li">Высота: {{ prevObject.heigth }}</div>
            <div class="li">Длинна: {{ prevObject.len }}</div>
          </div>
          <div class="column">
            <h5>РРЦ</h5>
            <div class="li">{{ prevObject.price }} р/шт</div>
            <div class="li">
              <button
                v-if="previewAdd"
                class="add"
                @click.stop="$emit('addProduct', prevObject, type)"
              >
                Добавить
              </button>
            </div>
          </div>
        </div>
        <div v-if="type == 'linoleum'" class="recomendation">
          <h2>Рекомендуемые плинтуса</h2>
          <template v-if="prevObject['rec']">
            <article
              v-for="plinth in GET_ALL_CATALOG_LIST.plinth.filter((item) =>
                prevObject['rec'].includes(item.id)
              )"
              @click="
                selectType('plinth');
                openPreview(plinth);
              "
              :key="plinth.id"
            >
              <img :src="$images + plinth.src" alt="" />
              <h4>Плинтус {{ plinth.name }}</h4>
            </article>
          </template>
        </div>
        <div v-else-if="type == 'plinth'" class="recomendation">
          <h2>Фурнитура</h2>
          <article
            style="text-align: center; height: 15vh"
            v-for="accessories in prevObject['accessories']"
            :key="accessories.id"
          >
            <h4>{{ accessories.name }}</h4>
            <button
              v-if="previewAdd"
              class="add"
              @click.stop="$emit('addProduct', accessories.id, 'accessories')"
            >
              Добавить
            </button>
          </article>
        </div>
      </template>
    </div>
  </main>
</template>
<script>
import { mapGetters, mapMutations } from "vuex";
import productSorting from "./productSorting.vue";
import MainLoad from "./../MainLoad.vue";
export default {
  components: {
    productSorting,
    MainLoad,
  },
  data() {
    return {
      s: "",
      preview: false,
      prevObject: {},
      previewAdd: false,
      catalogMode: true,
      count: 20,

      type: "linoleum",
      productType: [
        { name: "Линолеум", type: "linoleum", selected: true, count: 20 },
        { name: "Плинтуса", type: "plinth", selected: false, count: 20 },
        { name: "Пороги", type: "doorstep", selected: false, count: 20 },
        { name: "Соп. Товары", type: "related", selected: false, count: 20 },
      ],
    };
  },
  methods: {
    ...mapMutations({ tabTipe: "SET_CATALOG" }),
    selectType(type) {
      const selectedType = this.productType.filter((item) => item.selected)[0];
      if (selectedType.type == type) return false;
      else {
        this.type = type;
        this.count =
          this.type == "doorstep" || this.type == "related" ? 60 : 20;
        document.querySelector(".catalog_product_list").scrollTop = 0;
        selectedType.selected = false;
        this.productType.filter((item) => item.type == type)[0].selected = true;
      }
    },
    scroll(e) {
      var b = this.type == "doorstep" || this.type == "related" ? 30 : 10;
      var a = this.count / b;
      if (
        e.target.scrollTop + e.target.clientHeight >=
          e.target.clientHeight * a - 100 &&
        this.GET_LIST.length > this.count
      ) {
        this.count += b;
      }
    },
    getImgUrl(pic) {
      return require(pic);
    },
    openPreview(product) {
      this.prevObject = product;
      this.preview = true;
    },
    closePreview() {
      if (this.type == "linoleum") this.prevObject.price.selected = "";
      this.preview = false;
    },
    addPrice(s) {
      if (!s) return false;
      if (s < 11)
        this.prevObject.price.selected = this.prevObject.price.all.p0_10;
      else if (s < 16)
        this.prevObject.price.selected = this.prevObject.price.all.p11_15;
      else if (s < 21)
        this.prevObject.price.selected = this.prevObject.price.all.p16_20;
      else if (s < 31)
        this.prevObject.price.selected = this.prevObject.price.all.p21_30;
      else if (s < 41)
        this.prevObject.price.selected = this.prevObject.price.all.p31_40;
      else if (s < 56)
        this.prevObject.price.selected = this.prevObject.price.all.p41_55;
      else this.prevObject.price.selected = this.prevObject.price.all.p56_70;
    },
  },
  props: {
    add: Boolean,
    catalog: Boolean,
  },
  emits: ["addProduct"]["close"],
  computed: {
    ...mapGetters(["GET_LIST", "GET_ALL_CATALOG_LIST"]),
  },
  mounted: function () {
    // Close modal with 'esc' key
    document.addEventListener("keydown", (e) => {
      if (e.keyCode == 27) {
        this.tabTipe({ catalog: [], type: this.type });
        this.$emit("close");
      }
    });
    this.$nextTick(function () {
      this.previewAdd = Boolean(this.add);
      this.catalogMode = Boolean(this.catalog);
    });
  },
  watch: {
    type(newVal) {
      this.tabTipe({ catalog: [], type: newVal });
    },
  },
};
</script>
<style lang="scss" scoped src="./../../assets/styles/catalog.scss"></style>
