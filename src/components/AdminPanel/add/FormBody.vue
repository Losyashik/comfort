<template>
  <div>
    <h1></h1>
    <div class="flex">
      <form>
        <template v-for="tag in form" :key="tag.name">
          <div v-if="tag.tag == 'input' && tag.type != 'file'" class="row">
            <div class="name">{{ tag.name }}</div>
            <div>
              <input :name="tag.tag_name" :type="tag.type" />
              <!-- v-modal="update_data[tag.tag_name]" -->
            </div>
          </div>
          <div v-else-if="tag.tag == 'select'" class="row">
            <div class="name">{{ tag.name }}</div>
            <div>
              <template v-if="tag.multiple">
                <select
                  :size="tag.options.length + 2"
                  :name="tag.tag_name"
                  :multiple="tag.multiple"
                >
                  <option
                    v-for="opt in tag.options"
                    :key="opt.value"
                    :value="opt.id"
                    @mousedown.prevent="clickOption($event)"
                    @mousemove.prevent
                  >
                    {{ opt.name }}
                  </option>
                </select>
              </template>
              <template v-else>
                <select :name="tag.tag_name" :multiple="tag.multiple">
                  <option
                    v-for="opt in tag.options"
                    :key="opt.value"
                    :value="opt.id"
                  >
                    {{ opt.name }}
                  </option>
                </select>
              </template>
            </div>
          </div>
          <div
            v-else-if="tag.tag == 'input' && tag.type == 'file'"
            class="row file"
          >
            <input-file :multiple="tag.multiple" ref="inputFile"></input-file>
          </div>
          <div class="row" v-else-if="tag.tag == 'button'">
            <button
              :name="tag.tag_name"
              @click.prevent="addNewProduct($event.target)"
            >
              Добавить
            </button>
          </div>
        </template>
      </form>
      <div class="list">
        <div v-if="list.sort" class="sort">
          <form>
            <select
              @change="getDataList($event)"
              v-if="list.sort.plinth"
              name="plinth"
              id=""
            >
              <option selected value="0">Все плинтуса</option>
              <option
                v-for="plinth in list.sort.plinth"
                :key="plinth.id"
                :value="plinth.id"
              >
                {{ plinth.name }}
              </option>
            </select>
            <select
              @change="getDataList($event)"
              v-if="list.sort.maker"
              name="maker"
              id=""
            >
              <option selected value="0">Все производители</option>
              <option
                v-for="maker in list.sort.maker"
                :key="maker.id"
                :value="maker.id"
              >
                {{ maker.name }}
              </option>
            </select>
            <select
              @change="getDataList($event)"
              v-if="list.sort.collection"
              name="collection"
              id=""
            >
              <option selected value="0">Все коллекции</option>
              <option
                v-for="collection in list.sort.collection"
                :key="collection.id"
                :value="collection.id"
              >
                {{ collection.name }}
              </option>
            </select>
          </form>
        </div>
        <div class="block_list">
          <form>
            <select
              @click="selectProduct($event)"
              :class="{ no_sort: !list.sort }"
              multiple
              name="item[]"
            >
              <option
                @mousedown.prevent="clickOption($event)"
                @mousemove.prevent
                v-for="item in list.list"
                :data-log="item.show"
                :key="item.id"
                :value="item.id"
                :class="{ show: item.show == '1', hidden: item.show == '0' }"
              >
                {{ item.name }}
              </option>
            </select>
            <button
              id="edit"
              :name="
                data.product == '' ? data.type : data.product + '_' + data.type
              "
              @click.prevent="getDtataForEdit($event)"
            >
              Изменить
            </button>
            <template v-if="data.type == 'linoleum'">
              <button
                id="show"
                name="linoleum"
                @click.prevent="showFromAdd($event.target)"
              >
                Показать
              </button>
              <button
                id="hidden"
                name="linoleum"
                @click.prevent="showFromAdd($event.target)"
              >
                Скрыть
              </button>
            </template>
            <button
              id="delete"
              @click.prevent="deleteProducts($event.target)"
              :name="
                data.product == '' ? data.type : data.product + '_' + data.type
              "
            >
              Удалить
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import inputFile from "../components/InputFile.vue";
export default {
  created() {
    this.getDataForm();
  },
  components: {
    inputFile,
  },
  data() {
    return {
      data: {},
      form: [],
      // update_data: {},
      list: {
        sort: {},
        list: [],
      },
    };
  },
  methods: {
    clickOption(e) {
      let st = e.target.parentNode.scrollTop;
      e.target.selected = !e.target.selected;
      setTimeout(() => (e.target.parentNode.scrollTop = st), 0);
      this.focus();
    },
    async getDataForm() {
      if (this.$route.params.product == this.$route.params.type) {
        this.data = {
          product: "",
          type: this.$route.params.type,
        };
      } else {
        this.data = {
          product: this.$route.params.product,
          type: this.$route.params.type,
        };
      }
      const f = await fetch(this.$connect + "admin/formData.php", {
        method: "POST",
        body: JSON.stringify(this.data),
      }).then((r) => r.json());
      if (f.error && f.active) {
        let app = this.$parent.$parent.$parent.$parent.$parent.$parent;
        app.modal = f;
        setTimeout(function () {
          app.modal.active = false;
        }, 5000);
      } else {
        this.form = f;
      }
      this.getDataList();
    },
    async getDataList(event) {
      if (event) {
        if (this.data.sort) {
          delete this.data.sort;
        }
        let formData = new FormData(event.target.parentNode);
        if (formData.has("maker")) {
          if (formData.has("collection")) {
            if (formData.get("maker") != 0 || formData.get("collection") != 0)
              this.data.sort = {
                maker: formData.get("maker"),
                collection: formData.get("collection"),
              };
          } else if (formData.get("maker")) {
            this.data.sort = {
              maker: formData.get("maker"),
            };
          }
        } else if (formData.has("plinth") && formData.get("plinth")) {
          this.data.sort = {
            plinth: formData.get("plinth"),
          };
        }
      }
      const f = await fetch(this.$connect + "/admin/formList.php", {
        method: "POST",
        body: JSON.stringify(this.data),
      }).then((response) => response.json());
      if (f.error && f.active) {
        let app = this.$parent.$parent.$parent.$parent.$parent.$parent;
        app.modal = f;
        setTimeout(function () {
          app.modal.active = false;
        }, 50000);
      } else {
        this.list = f;
      }
    },
    async addNewProduct(but) {
      let formData = new FormData(but.parentNode.parentNode);
      formData.append("table_name", but.name);
      but.parentNode.parentNode.reset();
      console.log(this.form);
      if (this.form.filter((tag) => tag.type == "file").length) {
        this.$refs.inputFile[0].images = [];
      }
      let app = this.$parent.$parent.$parent.$parent.$parent.$parent;
      app.modal = await fetch(this.$connect + "/admin/addData.php", {
        method: "POST",
        body: formData,
      }).then((response) => response.json());

      this.getDataList();
    },
    async deleteProducts(but) {
      let formData = new FormData(but.parentNode);
      formData.append("table_name", but.name);
      let app = this.$parent.$parent.$parent.$parent.$parent.$parent;
      app.modal = await fetch(this.$connect + "admin/deleteData.php", {
        method: "POST",
        body: formData,
      }).then((response) => response.json());
      this.getDataList();
    },
    getDtataForEdit(but) {
      let formData = new FormData(but.parentNode);
      formData.append("table_name", but.name);
    },
    async showFromAdd(but) {
      let formData = new FormData(but.parentNode);
      console.log(but);
      formData.append("table_name", but.name);
      formData.append("show", but.id == "show" ? 1 : 0);
      await fetch("/backend/admin/showData.php", {
        method: "POST",
        body: formData,
      }).then((response) => response.json());
      this.getDataList();
    },
    selectProduct(event) {
      let count = 0;
      for (let i = 0; i < event.target.parentNode.children.length; i++) {
        if (event.target.parentNode.children[i].selected == true) {
          count++;
          if (count == 2) {
            for (
              let j = 0;
              j < event.target.parentNode.parentNode.children.length;
              j++
            ) {
              if (event.target.parentNode.parentNode.children[j].id == "edit") {
                event.target.parentNode.parentNode.children[j].disabled = true;
              }
            }
            break;
          }
        }
      }
      if (count == 1) {
        for (
          let j = 0;
          j < event.target.parentNode.parentNode.children.length;
          j++
        ) {
          if (event.target.parentNode.parentNode.children[j].id == "edit") {
            event.target.parentNode.parentNode.children[j].disabled = false;
          }
        }
      }
    },
  },
};
</script>
<style
  scoped
  lang="scss"
  src="./../../../assets/styles/admin/form.scss"
></style>
