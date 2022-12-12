import { createStore } from "vuex";
import axios from "@/api";
import MainList from "./modules/MainList";
import librares from "./modules/librares";
import CatalogList from "./modules/CatalogList";

export default createStore({
  state: {
    user: {},
  },
  mutations: {
    addUsers() {},
  },
  actions: {
    autorization(ctx, data) {
      const dataUser = axios.post("authorization.php", data);
      ctx.commit("addUser", dataUser);
    },
  },
  modules: {
    librares,
    MainList,
    CatalogList,
  },
});
