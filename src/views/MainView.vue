<template>
  <main class="main-page">
    <table class="main-page__table">
      <tr class="main-page__table-tr main-page__table-heading">
        <th class="main-page__table-th">№ заявки</th>
        <th class="main-page__table-th">Город</th>
        <th class="main-page__table-th">Адрес</th>
        <th class="main-page__table-th">№ 1С</th>
        <th class="main-page__table-th">Способ оплаты</th>
        <th class="main-page__table-th">Номер</th>
        <th class="main-page__table-th">Статус</th>
        <th class="main-page__table-th">Дата действия</th>
        <th class="main-page__table-th">Заметка</th>
      </tr>
      <tr v-for="item in mainList" :key="item.id" class="main-page__table-tr">
        <td :ceilspacing="item.id" class="main-page__table-td">
          {{ item.id }}
        </td>
        <td class="main-page__table-td">{{ item.city }}</td>
        <td class="main-page__table-td">
          {{ item.addres }}
        </td>
        <td :ceilspacing="item.id" class="main-page__table-td">
          {{ item.no_order_1c }}
        </td>
        <td :ceilspacing="item.id" class="main-page__table-td">
          {{ item.short_payment_method }}
        </td>
        <td :ceilspacing="item.id" class="main-page__table-td">
          {{ item.number }}
        </td>
        <td :ceilspacing="item.id" class="main-page__table-td">
          {{ item.status }}
        </td>
        <td :ceilspacing="item.id" class="main-page__table-td">
          {{ item.delivery_date }}
        </td>
        <td :ceilspacing="item.id" class="main-page__table-td">
          <pre>{{ item.note }}</pre>
        </td>
      </tr>
    </table>
  </main>
</template>
<style
  lang="scss"
  src="@\components\Main\assets\styles\main.scss"
  scoped
></style>
<script>
import json from "./data.json";
export default {
  data() {
    return {
      mainList: json,
      col_vo: 40,
    };
  },
};
</script>
