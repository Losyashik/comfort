<template>
  <div class="flex">
    <form>
      <div class="row">
        <div class="name">Название</div>
        <div><input type="text" name="name" /></div>
      </div>
      <div class="row">
        <div class="name">Краткое название</div>
        <div><input type="text" name="short_name" /></div>
      </div>
      <div class="row">
        <div class="name">Количество адресов</div>
        <div><input type="number" max="5" v-model="col" /></div>
      </div>
      <template v-for="i in col" :key="i">
        <div class="row">
          <div class="name">Адрес {{ i }}</div>
          <div>
            <input type="text" :name="'contacts[' + i + '][address]'" />
          </div>
        </div>
        <div class="row">
          <div class="name">Номер {{ i }}</div>
          <div><input type="text" :name="'contacts[' + i + '][number]'" /></div>
        </div>
      </template>

      <div class="row">
        <button @click.prevent="addProvider($event)">Добавить</button>
      </div>
    </form>
    <div class="list">
      <div class="block_list">
        <form>
          <select class="no_sort" multiple name="item[]">
            <option
              v-for="item in list"
              :key="item.id"
              @mousedown.prevent="
                $event.target.selected = !$event.target.selected;
                selectProduct($event);
              "
            >
              {{ item.name }}
            </option>
          </select>
          <button id="edit">Изменить</button>
          <button>Удалить</button>
        </form>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      col: 1,
      list: [],
    };
  },
  methods: {
    addProvider(e) {
      let formData = new FormData(e.target.parentNode.parentNode);
      formData.append("add", "1");
      e.target.parentNode.parentNode.reset();
      async function post(thet, data) {
        const f = await fetch(
          thet.$connect + "/admin/pricing/addProvider.php",
          {
            method: "POST",
            body: data,
          }
        );
        const text = await f.json();
        thet.list = text;
      }
      post(this, formData);
    },
    getList() {
      async function post(thet) {
        const f = await fetch(
          thet.$connect + "/admin/pricing/addProvider.php",
          {
            method: "POST",
          }
        );
        const text = await f.json();
        thet.list = text;
      }
      post(this);
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
  mounted() {
    this.getList();
  },
};
</script>
<style scoped>
.flex {
  margin: 10px 20px;
}
</style>
<style
  scoped
  lang="scss"
  src="./../../../assets/styles/admin/form.scss"
></style>
