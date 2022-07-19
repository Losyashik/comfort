<template>
  <div class="flex">
    <form id="allowances">
      <input
        type="hidden"
        name="collection"
        v-model="allowances.id_collection"
      />
      <div class="row">
        <div class="name">0-10</div>
        <div><input v-model="allowances.p0_10" type="text" name="0_10" /></div>
      </div>
      <div class="row">
        <div class="name">11-15</div>
        <div>
          <input v-model="allowances.p11_15" type="text" name="11_15" />
        </div>
      </div>
      <div class="row">
        <div class="name">16-20</div>
        <div>
          <input v-model="allowances.p16_20" type="text" name="16_20" />
        </div>
      </div>
      <div class="row">
        <div class="name">21-30</div>
        <div>
          <input v-model="allowances.p21_30" type="text" name="21_30" />
        </div>
      </div>
      <div class="row">
        <div class="name">31-40</div>
        <div>
          <input v-model="allowances.p31_40" type="text" name="31_40" />
        </div>
      </div>
      <div class="row">
        <div class="name">41-55</div>
        <div>
          <input v-model="allowances.p41_55" type="text" name="41_55" />
        </div>
      </div>
      <div class="row">
        <div class="name">56-70</div>
        <div>
          <input v-model="allowances.p56_70" type="text" name="56_70" />
        </div>
      </div>

      <div class="row">
        <button @click.prevent="editAllowances($event)">Сохранить</button>
      </div>
    </form>
    <div class="list">
      <div v-if="list.sort" class="sort">
        <form>
          <select
            @change="getList($event)"
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
        </form>
      </div>
      <div class="block_list">
        <form>
          <select
            :class="{ no_sort: !list.sort }"
            @mousedown.prevent
            multiple
            name="item"
          >
            <option
              v-for="item in list.list"
              :key="item.id"
              :value="item.id"
              @mousedown.prevent="selectCollection($event)"
            >
              {{ item.name }}
            </option>
          </select>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      allowances: {},
      list: [],
    };
  },
  methods: {
    async editAllowances(e) {
      let formData = new FormData(e.target.parentNode.parentNode);
      formData.append("table_name", "linoleum_allowances");
      let app = this.$parent.$parent.$parent.$parent.$parent.$parent;
      app.modal = await fetch(this.$connect + "/admin/addData.php", {
        method: "POST",
        body: formData,
      }).then((response) => response.json());
      setTimeout(function () {
        app.modal.active = false;
      }, 5000);
    },
    async getList(event) {
      let data;
      if (event != undefined) {
        data = new FormData(event.parentNode);
      } else {
        data = new FormData();
      }
      this.list = await fetch(
        this.$connect + "/admin/pricing/getListAllowances.php",
        {
          method: "POST",
          body: data,
        }
      ).then((resp) => resp.json());
    },
    async selectCollection(e) {
      e.target.parentNode.parentNode.reset();
      e.target.selected = !e.target.selected;
      let data = new FormData(e.target.parentNode.parentNode);
      this.allowances = await fetch(
        this.$connect + "/admin/pricing/getAllowances.php",
        {
          method: "POST",
          body: data,
        }
      ).then((responsy) => responsy.json());
    },
  },
  mounted() {
    this.getList();
  },
};
</script>
<style
  scoped
  lang="scss"
  src="./../../../assets/styles/admin/form.scss"
></style>
<style scoped>
.flex {
  margin: 10px 20px;
}
.name {
  text-align: center;
}
</style>
