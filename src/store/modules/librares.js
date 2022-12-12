import api from "./../../api";
export default {
  state: {
    cites: [],
    toc: [],
    payment: [],
    statuses: [],
    modal: { active: false, text: "", error: false },
  },
  getters: {
    GET_MODAL(state) {
      return state.modal;
    },
    GET_CITES(state) {
      return state.cites;
    },
    GET_STATUSES(state) {
      return state.statuses;
    },
    GET_TOC(state) {
      return state.toc;
    },
    GET_PAYMENTS(state) {
      return state.payment;
    },
    GET_CITY_NAME: (state) => (id) => {
      if (id) return state.cites.find((todo) => todo.id === id).name;
      else return "";
    },
    GET_TOC_NAME: (state) => (id) => {
      if (id) return state.toc.find((todo) => todo.id === id).name;
      else return "";
    },
    GET_PAYMENT_NAME: (state) => (id) => {
      if (id) return state.payment.find((todo) => todo.id === id).short_name;
      else return "";
    },
    GET_STATUS_NAME: (state) => (id) => {
      if (id) return state.statuses.find((todo) => todo.id === id).name;
      else return "";
    },
  },
  mutations: {
    setLists(state, data) {
      state.cites = data.cites;
      state.toc = data.toc;
      state.payment = data.payment;
      state.statuses = data.status;
    },
    updateModal(state, data) {
      state.modal = data;
      setTimeout(function () {
        state.modal.active = false;
      }, 5000);
    },
  },
  actions: {
    async getAllLists(ctx) {
      const lists = (await api.get("librares/lists.php")).data;
      ctx.commit("setLists", lists);
    },
  },
};
