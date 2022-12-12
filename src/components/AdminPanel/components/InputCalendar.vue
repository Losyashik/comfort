<template>
  <div class="input">
    <div class="hidden_elements">
      <input type="date" name="start_date" v-model="startDate" />
      <input type="date" name="end_date" v-model="endDate" />
    </div>
    <div class="calendar">
      <div class="month">
        <div class="arrows arrow_left" @click="prevMonth()">&laquo;</div>
        <div class="mounth_name">
          {{
            calendar.filter((i) => i.year == selectedYear)[0].months[
              selectedMonth
            ].name +
            " " +
            calendar.filter((i) => i.year == selectedYear)[0].year
          }}
        </div>
        <div class="arrows arrow_right" @click="nextMonth()">&raquo;</div>
      </div>
      <div class="days">
        <template
          v-if="
            calendar.filter((i) => i.year == selectedYear)[0].months[
              selectedMonth
            ].dayNFirst != 1 && selectedMonth != 0
          "
        >
          <span
            v-for="i in calendar.filter((i) => i.year == selectedYear)[0]
              .months[selectedMonth - 1].dayNlast"
            :key="i"
            :data-date="
              calendar.filter((i) => i.year == selectedYear)[0].months[
                selectedMonth - 1
              ].countDays -
              calendar.filter((i) => i.year == selectedYear)[0].months[
                selectedMonth - 1
              ].dayNlast +
              i +
              '.' +
              selectedMonth +
              '.' +
              selectedYear
            "
            >{{
              calendar.filter((i) => i.year == selectedYear)[0].months[
                selectedMonth - 1
              ].countDays -
              calendar.filter((i) => i.year == selectedYear)[0].months[
                selectedMonth - 1
              ].dayNlast +
              i
            }}</span
          >
        </template>
        <template
          v-else-if="
            calendar.filter((i) => i.year == selectedYear)[0].months[
              selectedMonth
            ].dayNFirst != 1 && selectedMonth == 0
          "
        >
          <span
            v-for="i in calendar.filter((i) => i.year == selectedYear)[0]
              .months[selectedMonth].dayNFirst - 1"
            :key="i"
            :data-date="
              31 -
              (calendar.filter((i) => i.year == selectedYear)[0].months[
                selectedMonth
              ].dayNFirst -
                1) +
              i +
              '.' +
              '12' +
              '.' +
              selectedYear -
              1
            "
            >{{
              31 -
              (calendar.filter((i) => i.year == selectedYear)[0].months[
                selectedMonth
              ].dayNFirst -
                1) +
              i
            }}</span
          >
        </template>
        <span
          v-for="i in calendar.filter((i) => i.year == selectedYear)[0].months[
            selectedMonth
          ].countDays"
          :key="i"
          :data-date="i + '.' + (selectedMonth + 1) + '.' + selectedYear"
          >{{ i }}</span
        >
        <template
          v-if="
            calendar.filter((i) => i.year == selectedYear)[0].months[
              selectedMonth
            ].dayNlast != 0 && selectedMonth != 11
          "
        >
          <span
            v-for="i in 7 -
            calendar.filter((i) => i.year == selectedYear)[0].months[
              selectedMonth
            ].dayNlast"
            :key="i"
            :data-date="i + '.' + (selectedMonth + 2) + '.' + selectedYear"
            >{{ i }}</span
          >
        </template>
        <template
          v-else-if="
            calendar.filter((i) => i.year == selectedYear)[0].months[
              selectedMonth
            ].dayNlast != 0 && selectedMonth == 11
          "
        >
          <span
            v-for="i in 7 -
            calendar.filter((i) => i.year == selectedYear)[0].months[
              selectedMonth
            ].dayNlast"
            :key="i"
            :data-date="i + '.' + '01' + '.' + (selectedYear + 1)"
            >{{ i }}</span
          >
        </template>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data() {
    return {
      calendar: [],
      startDate: new Date().getDate(),
      endDate: new Date().getDate(),
      selectedMonth: new Date().getMonth(),
      selectedYear: new Date().getFullYear(),
    };
  },
  methods: {
    nextMonth() {
      if (this.selectedMonth == 11) {
        this.selectedYear++;
        this.selectedMonth = 0;
      } else this.selectedMonth++;
    },
    prevMonth() {
      if (this.selectedMonth == 0) {
        this.selectedYear--;
        this.selectedMonth = 11;
      } else this.selectedMonth--;
    },
  },
  created() {
    var date = new Date();
    const month = [
      "Январь",
      "Февраль",
      "Март",
      "Апрель",
      "Май",
      "Июнь",
      "Июль",
      "Август",
      "Сентябрь",
      "Октябрь",
      "Ноябрь",
      "Декабрь",
    ]; // название месяца, вместо цифр 0-11
    for (var year = 2021; year <= date.getFullYear() + 2; year++) {
      var objectYear = { year: year };
      objectYear.months = [];
      for (var j = 0; j <= 11; j++) {
        var dayLast = new Date(year, j + 1, 0).getDate(); // последний день месяца

        let objectMonth = {
          name: month[j],
          number: j,
          countDays: new Date(year, j + 1, 0).getDate(), // последний день месяца
          dayNlast: new Date(year, j, dayLast).getDay(), // день недели последнего дня месяца
          dayNFirst: new Date(year, j, 1).getDay(), // день недели первого дня месяца
        };

        objectYear.months.push(objectMonth);
      }
      this.calendar.push(objectYear);
    }
    console.log(this.calendar);
  },
};
</script>
<style lang="scss" scoped>
.hidden_elements {
  display: none;
}
.calendar {
  width: 200px;
  padding: 10px;
  border: 1px solid;
  .month {
    display: flex;
    justify-content: space-between;
    user-select: none;
    width: 100%;
    .arrays {
      width: 10%;
    }
  }
  .days {
    display: flex;
    flex-wrap: wrap;
    span {
      user-select: none;
      cursor: pointer;
      &:nth-child(7n),
      &:nth-child(7n - 1) {
        color: #f00;
      }
      &.selected {
        background-color: #e2e2e2;
        &.end {
          border-radius: 0 10px 10px 0;
        }
        &.start {
          border-radius: 10px 0 0 10px;
        }
      }
      display: block;
      text-align: center;
      padding: 3px 2px;
      width: calc((100% / 7) - 4px);
    }
  }
}
</style>
