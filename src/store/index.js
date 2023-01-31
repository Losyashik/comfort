import { createStore } from "vuex";
import axios from "@/api";
import MainList from "./modules/MainList";
import librares from "./modules/librares";
import CatalogList from "./modules/CatalogList";
import StatisticData from "./modules/StatisticData";

export default createStore({
  state: {
    user: {},
    error: "",
  },
  getters: {
    GET_ERROR(state) {
      return state.error;
    },
    GET_OPENED_TABS(state) {
      return state.user.opened_tabs;
    },
    GET_USER_RIGHTS(state) {
      return state.user.rights;
    },
    GET_USER_NAME(state) {
      return state.user.name.split(" ");
    },
  },
  mutations: {
    updateTabs(state, data) {
      state.user.opened_tabs = data;
      if (state.user.id) localStorage.user = JSON.stringify(state.user);
    },
    writeError(state, data) {
      state.error = data;
    },
    addUser(state, data) {
      state.user = data;
      localStorage.setItem("user", JSON.stringify(data));
    },
    deleteUser(state) {
      axios.get("user.php?exit=1");
      state.user = {};
      localStorage.clear("user");
    },
  },
  actions: {
    async autorization(ctx, data) {
      const dataUser = await axios.post("user.php", {
        userAuthorizationData: data,
      });
      console.log(dataUser);
      if (dataUser.status == "200") {
        ctx.commit("addUser", dataUser.data);
        window.location.replace("/");
      } else if (dataUser.status == "203") {
        ctx.commit("writeError", dataUser.data);
      }
    },
    async fetchTabs(ctx, data) {
      // let response = await axios.post("user.php", {
      //   tabs: data,
      // });
      // if (response.status == "201") {
      ctx.commit("updateTabs", data);
      // }
    },
    exit(ctx) {
      axios.get("autorization.php?exit=1");
      ctx.commit("deleteUser");
      window.location.replace("/login");
    },
  },
  modules: {
    librares,
    MainList,
    CatalogList,
    StatisticData,
  },
});
