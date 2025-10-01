import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import ModalWindow from "./components/ModalWindow";

// ws = new WebSocket("ws://localhost:81");

const app = createApp(App);

app.component("modal-window", ModalWindow);

app.config.globalProperties.$images = "";
app.config.globalProperties.$connect = "/backend/";
app.config.globalProperties.$ws_path = "ws://fox-click.ru:9081/";
// app.config.globalProperties.$images = "http://backend/";

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

app.config.globalProperties.$getStringDate = (dateString) => {
  if (!dateString) return "";
  else {
    var options = {
      year: "numeric",
      month: "numeric",
      day: "numeric",
    };
    var seconds = Date.parse(dateString);
    var date = new Date(seconds);
    return date.toLocaleString("ru", options);
  }
};
app.config.globalProperties.$getTime = (date, time) => {
  if (!(time && date)) return "";
  var options = {
    hour: "numeric",
    minute: "numeric",
  };
  var seconds = Date.parse(date + "T" + time);

  seconds = new Date(seconds);
  return seconds.toLocaleString("ru", options);
};

app.use(store).use(router).mount("#app");

document.body.lang = "ru";
