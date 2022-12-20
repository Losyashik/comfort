export default createStore({
  state: {
    dialogs: [],
    openedDialog: {},
  },
  getters: {
    GET_ONE_DIALOG(state) {
      return state.openedDialog;
    },
    GET_ALL_DIALOGS(state) {
      return state.dialogs;
    },
  },
  mutations: {},
  actions: {},
});
