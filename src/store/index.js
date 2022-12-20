import { createStore } from "vuex";
import axios from "@/api";
import MainList from "./modules/MainList";
import librares from "./modules/librares";
import CatalogList from "./modules/CatalogList";

export default createStore({
  state: {
    user: {},
  },
  getters: {
    GET_OPENED_TABS(state) {
      return JSON.parse(state.user.tabs);
    },
    GET_USER_RIGHTS(state) {
      return state.user.rights;
    },
    GET_USER_NAME(state) {
      return state.user.name.split(" ");
    },
  },
  mutations: {
    addUsers(state, data) {
      state.user = data;
    },
  },
  actions: {
    autorization(ctx, data) {
      const dataUser = axios.post("authorization.php", data);
      ctx.commit("addUser", dataUser);
    },
    exit() {},
  },
  modules: {
    librares,
    MainList,
    CatalogList,
  },
});
