<template>
  <div class="flex">
    <form>
      <h3>Линолиум</h3>
      <div class="select">
        <select v-model="linoleum.maker" @change="editLists('linoleum')">
          <option disabled value="0">Выберете производителя</option>
          <option
            v-for="item in linoleum.makers"
            :value="item.id"
            :key="item.id"
          >
            {{ item.name }}
          </option>
        </select>
        <select v-model="linoleum.collection" @change="editLists('linoleum')">
          <option disabled value="0">Выберете коллекцию</option>
          <option
            v-for="item in linoleum.collections"
            :value="item.id"
            :key="item.id"
          >
            {{ item.name }}
          </option>
        </select>
        <select
          name="linoleum"
          v-model="linoleum.id"
          @change="editLists('linoleum')"
        >
          <option disabled value="0">Выберете линолиум</option>
          <option
            v-for="item in linoleum.linoleums"
            :value="item.id"
            :key="item.id"
          >
            {{ item.name }}
          </option>
        </select>
        <select
          name="provider"
          v-model="linoleum.provider"
          @change="editLists('linoleum')"
        >
          <option disabled value="0">Выберете поставщика</option>
          <option v-for="item in providers" :value="item.id" :key="item.id">
            {{ item.name }}
          </option>
        </select>
      </div>
      <div class="width">
        <select name="width[]" multiple="multiple">
          <option
            v-for="item in linoleum.width"
            @mousedown.prevent="
              $event.target.selected = !$event.target.selected;
              item.selected = !item.selected;
            "
            :selected="item.selected"
            :value="item.id"
            :key="item.id"
          >
            {{ item.name }}
          </option>
        </select>
        <div>
          <label
            v-for="item in linoleum.width"
            :key="item.id"
            :class="{ none_select: !item.selected }"
          >
            <input
              v-if="item.selected"
              v-model="item.value"
              :name="'width_price[' + item.id + ']'"
              type="text"
            />
            <template v-else>Не выбрано</template>
          </label>
        </div>
      </div>
      <button name="table" value="linoleum" @click.prevent="submit($event)">
        Сохранить
      </button>
    </form>
    <form>
      <h3>Плинтуса</h3>
      <select
        name="provider"
        v-model="plinth.provider"
        @change="editLists('plinth')"
      >
        <option disabled value="0">Выберете поставщика</option>
        <option v-for="item in providers" :value="item.id" :key="item.id">
          {{ item.name }}
        </option>
      </select>
      <select id="" v-model="plinth.maker" @change="editLists('plinth')">
        <option disabled value="0">Выберете производителя</option>
        <option v-for="item in plinth.makers" :value="item.id" :key="item.id">
          {{ item.name }}
        </option>
      </select>
      <select
        name="plinth_collection"
        v-model="plinth.collection"
        @change="editLists('plinth')"
      >
        <option value="0" disabled>Выберете коллекцию</option>
        <option
          v-for="item in plinth.collections"
          :value="item.id"
          :key="item.id"
        >
          {{ item.name }}
        </option>
      </select>
      <div class="price">
        <label>
          <span>Опт: </span>
          <input
            type="text"
            name="opt"
            placeholder="Опт"
            v-model="plinth.opt"
          />
        </label>
        <label>
          <span>РРЦ: </span>
          <input
            type="text"
            name="rrc"
            placeholder="РРЦ"
            v-model="plinth.rrc"
          />
        </label>
      </div>
      <button name="table" value="plinth" @click.prevent="submit($event)">
        Сохранить
      </button>
    </form>
    <form>
      <h3>Фурнитура</h3>
      <div class="select">
        <select v-model="accessories.maker" @change="editLists('accessories')">
          <option disabled value="0">Выберете производителя</option>
          <option
            v-for="item in accessories.makers"
            :value="item.id"
            :key="item.id"
          >
            {{ item.name }}
          </option>
        </select>
        <select
          v-model="accessories.collection"
          @change="editLists('accessories')"
        >
          <option disabled value="0">Выберете коллекцию</option>
          <option
            v-for="item in accessories.collections"
            :value="item.id"
            :key="item.id"
          >
            {{ item.name }}
          </option>
        </select>
        <select
          name="plinth"
          v-model="accessories.plinth"
          @change="editLists('accessories')"
        >
          <option disabled value="0">Выберете плинтус</option>
          <option
            v-for="item in accessories.plinths"
            :value="item.id"
            :key="item.id"
          >
            {{ item.name }}
          </option>
        </select>
        <select
          name="provider"
          v-model="accessories.provider"
          @change="editLists('accessories')"
        >
          <option disabled value="0">Выберете поставщика</option>
          <option v-for="item in providers" :value="item.id" :key="item.id">
            {{ item.name }}
          </option>
        </select>
      </div>
      <div class="list">
        <div class="row" v-for="item in accessories.accessories" :key="item.id">
          {{ item.name }}:
          <div class="label">
            <label
              ><span>Опт:</span
              ><input
                type="text"
                v-model="item.opt"
                :name="'opt_price[' + item.id + ']'"
            /></label>
            <label
              ><span>РРЦ:</span
              ><input
                type="text"
                v-model="item.rrc"
                :name="'rrc_price[' + item.id + ']'"
            /></label>
          </div>
        </div>
      </div>
      <button name="table" value="accessories" @click.prevent="submit($event)">
        Сохранить
      </button>
    </form>
    <form>
      <h3>Пороги</h3>
      <select
        name="provider"
        v-model="doorstep.provider"
        @change="editLists('doorstep')"
      >
        <option disabled value="0">Выберете поставщика</option>
        <option v-for="item in providers" :value="item.id" :key="item.id">
          {{ item.name }}
        </option>
      </select>
      <select id="" v-model="doorstep.maker" @change="editLists('doorstep')">
        <option disabled value="0">Выберете производителя</option>
        <option v-for="item in doorstep.makers" :value="item.id" :key="item.id">
          {{ item.name }}
        </option>
      </select>
      <select
        name="doorstep"
        v-model="doorstep.doorstep"
        @change="editLists('doorstep')"
      >
        <option value="0" disabled>Выберете порог</option>
        <option
          v-for="item in doorstep.doorsteps"
          :value="item.id"
          :key="item.id"
        >
          {{ item.name }}
        </option>
      </select>
      <div class="price">
        <label>
          <span>Опт: </span>
          <input
            type="text"
            name="opt"
            placeholder="Опт"
            v-model="doorstep.opt"
          />
        </label>
        <label>
          <span>РРЦ: </span>
          <input
            type="text"
            name="rrc"
            placeholder="РРЦ"
            v-model="doorstep.rrc"
          />
        </label>
      </div>
      <button name="table" value="doorstep" @click.prevent="submit($event)">
        Сохранить
      </button>
    </form>
  </div>
</template>
<script>
// import AppVue from "../../../App.vue";
export default {
  data() {
    return {
      providers: [],
      linoleum: {
        id: 0,
        provider: 0,
        maker: 0,
        collection: 0,
        makes: [],
        collections: [],
        linoleums: [],
        width: [],
      },
      plinth: {
        provider: 0,
        makers: [],
        collections: [],
        maker: 0,
        collection: 0,
        rrc: "",
        opt: "",
      },
      accessories: {
        provider: 0,
        makers: [],
        collections: [],
        plinths: [],
        maker: 0,
        collection: 0,
        plinth: 0,
        accessories: [],
      },
      doorstep: {
        provider: 0,
        makers: [],
        doorsteps: [],
        maker: 0,
        doorstep: 0,
        rrc: "",
        opt: "",
      },
    };
  },
  methods: {
    async editLists(name) {
      let dataForm = this.FromDataBuild(name);
      dataForm.append("table", name);
      let lists = await fetch(this.$connect + "admin/pricing/sortList.php", {
        method: "POST",
        body: dataForm,
      }).then((response) => response.json());
      switch (name) {
        case "linoleum": {
          this.linoleum.collections = lists.collections;
          this.linoleum.linoleums = lists.linoleums;
          this.linoleum.width = lists.width;
          break;
        }
        case "plinth": {
          this.plinth.collections = lists.collections;
          this.plinth.opt = lists.opt;
          this.plinth.rrc = lists.rrc;
          break;
        }
        case "accessories": {
          this.accessories.collections = lists.collections;
          this.accessories.plinths = lists.plinths;
          this.accessories.accessories = lists.accessories;
          break;
        }
        case "doorstep": {
          this.doorstep.doorsteps = lists.doorsteps;
          this.doorstep.opt = lists.opt;
          this.doorstep.rrc = lists.rrc;
          break;
        }
      }
    },
    FromDataBuild(name) {
      let dataForm = new FormData();
      switch (name) {
        case "linoleum": {
          if (this.linoleum.maker != 0) {
            dataForm.append("maker", this.linoleum.maker);
          }
          if (this.linoleum.collection != 0) {
            dataForm.append("collection", this.linoleum.collection);
          }
          if (this.linoleum.id != 0) {
            dataForm.append("id", this.linoleum.id);
          }
          if (this.linoleum.provider != 0) {
            dataForm.append("provider", this.linoleum.provider);
          }
          return dataForm;
        }
        case "plinth": {
          if (this.plinth.maker != 0) {
            dataForm.append("maker", this.plinth.maker);
          }
          if (this.plinth.collection != 0) {
            dataForm.append("collection", this.plinth.collection);
          }
          if (this.plinth.provider != 0) {
            dataForm.append("provider", this.plinth.provider);
          }
          return dataForm;
        }
        case "accessories": {
          if (this.accessories.maker != 0) {
            dataForm.append("maker", this.accessories.maker);
          }
          if (this.accessories.collection != 0) {
            dataForm.append("collection", this.accessories.collection);
          }
          if (this.accessories.provider != 0) {
            dataForm.append("provider", this.accessories.provider);
          }
          if (this.accessories.plinth != 0) {
            dataForm.append("plinth", this.accessories.plinth);
          }
          return dataForm;
        }
        case "doorstep": {
          if (this.doorstep.maker != 0) {
            dataForm.append("maker", this.doorstep.maker);
          }
          if (this.doorstep.doorstep != 0) {
            dataForm.append("doorstep", this.doorstep.doorstep);
          }
          if (this.doorstep.provider != 0) {
            dataForm.append("provider", this.doorstep.provider);
          }
          return dataForm;
        }
      }
    },
    async getLists() {
      const lists = await fetch(this.$connect + "admin/pricing/lists.php", {
        method: "POST",
      }).then((response) => response.json());
      this.linoleum.makers = lists.linoleum.makers;
      this.linoleum.collections = lists.linoleum.collections;
      this.linoleum.linoleums = lists.linoleum.linoleums;
      this.providers = lists.providers;
      this.plinth.makers = lists.plinth.makers;
      this.plinth.collections = lists.plinth.collections;
      this.accessories.makers = lists.plinth.makers;
      this.accessories.collections = lists.plinth.collections;
      this.accessories.plinths = lists.plinth.plinths;
      this.doorstep.makers = lists.doorstep.makers;
      this.doorstep.doorsteps = lists.doorstep.doorsteps;
    },
    async submit(event) {
      let dataForm = new FormData(event.target.parentNode);
      dataForm.append(event.target.name, event.target.value);
      let app = this.$parent.$parent.$parent.$parent.$parent.$parent;
      app.modal = await fetch(this.$connect + "admin/pricing/submit.php", {
        method: "POST",
        body: dataForm,
      }).then((text) => text.json());
      setTimeout(function () {
        app.modal.active = false;
      }, 5000);
    },
  },
  mounted() {
    this.getLists();
  },
};
</script>
<style
  scoped
  lang="scss"
  src="./../../../assets/styles/admin/pricing/addPrice.scss"
></style>
