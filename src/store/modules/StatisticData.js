import api from "./../../api";
export default {
  state: {
    viewData: [],
    allStatistics: {},
    generalStatistics: {},
  },
  getters: {
    GET_STATISTICS(state) {
      return state.viewData;
    },
    GET_ALL_STATISTICS(state) {
      return state.allStatistics;
    },
    GET_GENERAL_STATISTICS(state) {
      return state.generalStatistics;
    },
  },
  mutations: {
    ASSIGN_DATA(state, data) {
      state.allStatistics = data;
    },
    ASSIGN_GENERAL_STATISTICS(state, data) {
      state.generalStatistics = data;
    },
    SELECT_VIEW_DATA(state, name) {
      state.viewData = state.allStatistics[name];
    },
  },
  actions: {
    async FETCH_GENERAL_STATISTICS(ctx) {
      let data = await api.get("statistics/generalData.php");
      ctx.commit("ASSIGN_GENERAL_STATISTICS", data.data.general);
      ctx.commit("ASSIGN_DATA", data.data.all);
      ctx.commit("SELECT_VIEW_DATA", "orders");
    },
    async FETCH_STATISTICS_IN_DATE(ctx, date) {
      let data = date.end
        ? await api.get(
            "statistics/generalData.php?start=" +
              date.start +
              "&end=" +
              date.end
          )
        : await api.get("statistics/generalData.php?start=" + date.start);
      ctx.commit("ASSIGN_GENERAL_STATISTICS", data.data.general);
      ctx.commit("ASSIGN_DATA", data.data.all);
      ctx.commit("SELECT_VIEW_DATA", "orders");
    },
  },
};
