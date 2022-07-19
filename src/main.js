import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import ModalWindow from "./components/ModalWindow";

const app = createApp(App);
app.component("modal-window", ModalWindow);

app.config.globalProperties.$images = "";
app.config.globalProperties.$connect = "/backend/";
app.config.globalProperties.$connect = "http://backend/backend/";
app.config.globalProperties.$images = "http://backend/";

app.config.globalProperties.$acceptNumber = (number) => {
  var x = number
    .replace(/\D/g, "")
    .match(/(\d{0,1})(\d{0,3})(\d{0,3})(\d{0,2})(\d{0,2})/);
  return !x[3]
    ? x[1] + x[2]
    : x[1] +
        " (" +
        x[2] +
        ") " +
        x[3] +
        (x[4] ? "-" + x[4] + (x[5] ? " " + x[5] : "") : "");
};

app.config.globalProperties.$getStringDate = function (dateString) {
  if (dateString == "" || dateString == undefined) return "--.--.----";
  var options = {
    year: "numeric",
    month: "numeric",
    day: "numeric",
  };
  var seconds = Date.parse(dateString);
  var date = new Date(seconds);
  return date.toLocaleString("ru", options);
};

app.use(router).mount("#app");
document.body.lang = "ru";
