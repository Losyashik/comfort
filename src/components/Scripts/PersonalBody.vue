<template>
  <main class="script_body">
    <article class="script_card">
      <h3>Данные о клиенте</h3>
      <div class="person_list">
        <ul>
          <template v-for="(item, index) in data" :key="index">
            <li v-if="item.text">
              {{ item.title + item.text }}
            </li>
            <li v-if="index == 'size' && item.pieces.length">
              {{ item.title }}
              <ul>
                <li v-for="(el, index) in item.pieces" :key="index">
                  {{
                    el.width != "" && el.len != ""
                      ? el.width + " * " + el.len + " = " + el.space
                      : el.space
                  }}
                </li>
                <li>Общая площадь: {{ item.allSpace }}</li>
              </ul>
            </li>
          </template>
        </ul>
      </div>
    </article>
    <article class="script">
      <div class="step" v-for="item in steps" :key="item">
        <div class="phrase">
          <div class="text">
            {{
              script[item].text.replace("name", data.name.text) +
              (item == 1 ? user.name : "")
            }}
          </div>
        </div>
        <div class="answers">
          <div
            class="answer"
            v-for="(answer, index) in script[item].answers"
            :key="index"
            :style="'background:' + answer.bg"
            @click="
              !steps.includes(answer['next-step']) && !answer.input
                ? steps.push(answer['next-step'])
                : none
            "
          >
            {{ answer.text }}
            <templeat v-if="answer.input == 'name'">
              <input
                @keydown.enter="
                  !steps.includes(answer['next-step'])
                    ? steps.push(answer['next-step'])
                    : none
                "
                type="text"
                v-model="data[answer.input].text"
              />
              <button
                @click="
                  !steps.includes(answer['next-step'])
                    ? steps.push(answer['next-step'])
                    : none
                "
              >
                Далее
              </button>
            </templeat>
            <templeat v-if="answer.input == 'areal'">
              <select v-model="data[answer.input].text">
                <option value="">Выберете регион</option>
                <option
                  v-for="(item, index) in arials"
                  :key="index"
                  :value="item"
                >
                  {{ item }}
                </option>
              </select>
            </templeat>
          </div>
        </div>
      </div>
    </article>
  </main>
</template>
<style scoped lang="scss" src="./../../assets/styles/scripts/main.scss"></style>
<script>
import json from "./../../assets/data/script-phone.json";
export default {
  data() {
    return {
      arials: [
        "Нижегородская обл",
        "Владимирская обл",
        "Московская обл",
        "Ульяновская обл",
        "республика Мордовия",
        "Марий Эл",
        "Татарстан",
        "Чувашия",
        "другое",
      ],
      script: json,
      steps: [1],
      user: {},
      data: {
        name: { title: "Имя: ", text: "" },
        areal: { title: "Регион: ", text: "" },
        space: {
          title: "Размеры: ",
          pieces: [
            { width: "4", len: "3", space: "12" },
            { width: "", len: "", space: "20" },
          ],
          allSpace: "32",
        },
      },
    };
  },
  props: ["id_person"],
  mounted() {
    this.user = JSON.parse(localStorage.user);
  },
};
</script>
