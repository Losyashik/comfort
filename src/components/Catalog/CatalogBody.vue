<template>
  <main class="catalog_body">
    <div class="catalog_product_body">
      <header class="product_type">
        <ul>
          <li
            v-for="pType in productType"
            :key="pType.name"
            @click="selectType(pType)"
            :class="{ selected: pType.selected }"
          >
            <a class="catalog_button"> {{ pType.name }} </a>
          </li>
        </ul>

        <div class="close_box">
          <img
            @click="$emit('close')"
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
      <div v-if="catalogMode" class="catalog_product_list">
        <article
          v-for="product in productList"
          :key="product.id"
          @click="openPreview(product.id)"
          class="catalog_product"
        >
          <img :src="$images + product.src" alt="" />
          <h4>{{ product.name }}</h4>
          <button
            v-if="previewAdd"
            class="add"
            @click.stop="$emit('addProduct', product.id, type)"
          >
            Добавить
          </button>
        </article>
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
            <td></td>
          </tr>
        </table>
      </div>
    </div>
    <div v-show="preview" class="catalog_preview_product">
      <template v-if="preview">
        <header>
          <button @click="closePreview" class="back">Назад</button>
          <h2>{{ previewProduct.name }}</h2>
        </header>
        <div class="slider">
          <div class="slider_body">
            <div v-for="src in previewProduct.images" :key="src" class="item">
              <img :src="$images + src" alt="" />
            </div>
          </div>
        </div>
        <div v-if="type == 'Linoleum'" class="list">
          <div class="column">
            <h5>Характеристики</h5>
            <div class="li">Класс применения: {{ previewProduct.class }}</div>
            <div class="li">
              Толщина покрытия,общая: {{ previewProduct.totalThickness }}
            </div>
            <div class="li">Тип линолиума: {{ previewProduct.type }}</div>
            <div class="li">
              Толщина защитного слоя: {{ previewProduct.protective }}
            </div>
            <div class="li">Основа линолиума: {{ previewProduct.base }}</div>
          </div>
          <div class="column">
            <h5>Основная информация</h5>
            <div class="li">Производитель: {{ previewProduct.maker }}</div>
            <div class="li">Колекция: {{ previewProduct.collection }}</div>
            <div class="li">
              Предназначение: {{ previewProduct.destination }}
            </div>
            <div class="li">
              Ширины рулонов:
              <span v-for="width in previewProduct.width" :key="width">
                {{ width }}</span
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
            <div class="li">Цена: {{ previewProduct.price.selected }}</div>
            <div class="li">
              <button
                v-if="previewAdd"
                class="add"
                @click.stop="$emit('addProduct', previewProduct.id, type)"
              >
                Добавить
              </button>
            </div>
          </div>
        </div>
        <div v-if="type == 'Plintusa'" class="list">
          <div class="column">
            <h5>Характеристики</h5>
            <div class="li">Производитель: {{ previewProduct.maker }}</div>
            <div class="li">Колекция: {{ previewProduct.collection }}</div>
            <div class="li">Ширина: {{ previewProduct.width }}</div>
            <div class="li">Высота: {{ previewProduct.heigth }}</div>
            <div class="li">Длинна: {{ previewProduct.len }}</div>
          </div>
          <div class="column">
            <h5>РРЦ</h5>
            <div class="li">{{ previewProduct.price }} р/шт</div>
            <div class="li">
              <button
                v-if="previewAdd"
                class="add"
                @click.stop="$emit('addProduct', previewProduct.id, type)"
              >
                Добавить
              </button>
            </div>
          </div>
        </div>
        <div v-if="type == 'Linoleum'" class="recomendation">
          <h2>Рекомендуемые плинтуса</h2>
          <article
            v-for="plinth in previewProduct['rec']"
            @click="
              type = 'Plintusa';
              openPreview(plinth.id);
            "
            :key="plinth.id"
          >
            <img :src="$images + plinth.src" alt="" />
            <h4>Плинтус {{ plinth.name }}</h4>
          </article>
        </div>
        <div v-if="type == 'Plintusa'" class="recomendation">
          <h2>Фурнитура</h2>
          <article
            style="text-align: center; height: 15vh"
            v-for="accessories in previewProduct['accessories']"
            @click="
              type = 'Plintusa';
              openPreview(accessories.id);
            "
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
function translit(word) {
  var answer = "";
  var converter = {
    а: "a",
    б: "b",
    в: "v",
    г: "g",
    д: "d",
    е: "e",
    ё: "e",
    ж: "zh",
    з: "z",
    и: "i",
    й: "y",
    к: "k",
    л: "l",
    м: "m",
    н: "n",
    о: "o",
    п: "p",
    р: "r",
    с: "s",
    т: "t",
    у: "u",
    ф: "f",
    х: "h",
    ц: "c",
    ч: "ch",
    ш: "sh",
    щ: "sch",
    ь: "",
    ы: "y",
    ъ: "",
    э: "e",
    ю: "yu",
    я: "ya",

    А: "A",
    Б: "B",
    В: "V",
    Г: "G",
    Д: "D",
    Е: "E",
    Ё: "E",
    Ж: "Zh",
    З: "Z",
    И: "I",
    Й: "Y",
    К: "K",
    Л: "L",
    М: "M",
    Н: "N",
    О: "O",
    П: "P",
    Р: "R",
    С: "S",
    Т: "T",
    У: "U",
    Ф: "F",
    Х: "H",
    Ц: "C",
    Ч: "Ch",
    Ш: "Sh",
    Щ: "Sch",
    Ь: "",
    Ы: "Y",
    Ъ: "",
    Э: "E",
    Ю: "Yu",
    Я: "Ya",
  };

  for (var i = 0; i < word.length; ++i) {
    if (converter[word[i]] == undefined) {
      answer += word[i];
    } else {
      answer += converter[word[i]];
    }
  }

  return answer;
}
import productSorting from "./productSorting.vue";
export default {
  components: {
    productSorting,
  },
  data() {
    return {
      s: "",
      preview: false,
      previewAdd: false,
      catalogMode: true,
      type: translit("Линолеум"),
      productType: [
        { name: "Линолеум", selected: true },
        { name: "Плинтуса", selected: false },
        { name: "Пороги", selected: false },
      ],
      productList: [],
      previewProduct: {},
    };
  },
  methods: {
    async sortProducts(data) {
      this.productList = [];
      data.type = this.type;
      data.warehouse = this.catalogMode;
      this.productList = await fetch(
        this.$connect + "catalog/productList.php",
        {
          method: "POST",
          body: JSON.stringify(data),
        }
      ).then((resp) => resp.json());
    },
    async selectType(type) {
      if (type.selected) {
        return false;
      }
      this.productType.forEach((el) => {
        el.selected = false;
      });
      type.selected = true;

      this.type = this.productType.filter((type) => type.selected == true)[0];
      this.type = translit(this.type.name);

      let data = { type: this.type, warehouse: this.catalogMode };
      this.productList = [];
      this.productList = await fetch(
        this.$connect + "catalog/productList.php",
        {
          method: "POST",
          body: JSON.stringify(data),
        }
      ).then((resp) => resp.json());
    },
    async catlogList() {
      this.productList = [];
      let data = { type: this.type, warehouse: this.catalogMode };
      this.productList = await fetch(
        this.$connect + "catalog/productList.php",
        {
          method: "POST",
          body: JSON.stringify(data),
        }
      ).then((resp) => resp.json());
      if (!this.catalogMode) {
        this.productType.push({ name: "Фурнитура", selected: false });
      }
    },
    getImgUrl(pic) {
      return require(pic);
    },
    async openPreview(id) {
      if (this.type == "Linoleum") {
        this.previewProduct = await fetch(
          this.$connect + "catalog/previewProduct.php?id_linoleum=" + id
        ).then((resp) => resp.json());
        this.preview = true;
      } else if (this.type == "Plintusa") {
        this.previewProduct = await fetch(
          this.$connect + "catalog/previewProduct.php?id_plinth=" + id
        ).then((resp) => resp.json());
        this.preview = true;
      }
    },
    closePreview() {
      this.previewProduct = {};
      this.preview = false;
      let type = this.productType.filter(
        (type) => translit(type.name) == this.type
      )[0];
      this.selectType(type);
    },
    addPrice(s) {
      if (!s) return false;
      if (s < 11)
        this.previewProduct.price.selected =
          this.previewProduct.price.all.p0_10;
      else if (s < 16)
        this.previewProduct.price.selected =
          this.previewProduct.price.all.p11_15;
      else if (s < 21)
        this.previewProduct.price.selected =
          this.previewProduct.price.all.p16_20;
      else if (s < 31)
        this.previewProduct.price.selected =
          this.previewProduct.price.all.p21_30;
      else if (s < 41)
        this.previewProduct.price.selected =
          this.previewProduct.price.all.p31_40;
      else if (s < 56)
        this.previewProduct.price.selected =
          this.previewProduct.price.all.p41_55;
      else
        this.previewProduct.price.selected =
          this.previewProduct.price.all.p56_70;
    },
  },
  props: {
    add: Boolean,
    catalog: Boolean,
  },
  emits: ["addProduct"]["close"],
  mounted: function () {
    // Close modal with 'esc' key
    document.addEventListener("keydown", (e) => {
      if (e.keyCode == 27) {
        this.$emit("close");
      }
    });
    this.$nextTick(function () {
      this.previewAdd = Boolean(this.add);
      this.catalogMode = Boolean(this.catalog);
      this.catlogList();
    });
  },
};
</script>
<style lang="scss" scoped src="./../../assets/styles/catalog.scss"></style>
