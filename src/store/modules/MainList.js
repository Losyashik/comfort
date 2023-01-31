import store from "..";
import api from "./../../api";

// async function scan(ctx) {
//   const resp = await api.get("mainList.php?edit=1");
//   if (resp.status == "200") {
//     const list = resp.data;/

//     if (list["update"]) {
//       ctx.commit("SELECTIVE_UPDATE_LIST", list.list);
//       ctx.commit("SORTING_MAIN", ctx.state.sortData);
//       ctx.commit("SORTING_BY_COLUMN", ctx.state.sortByColumnDate);
//     }
//   }
//   scan(ctx);
// }

export default {
  state: {
    allList: [],
    list: [],
    sortData: {},
    statuses: [],
    sortByColumnDate: {},
  },
  getters: {
    GET_MAIN_LIST(state) {
      return state.list;
    },
  },
  mutations: {
    SORTING_BY_COLUMN(state, { column, order }) {
      state.sortByColumnDate = {
        column,
        order,
      };
      switch (column) {
        case "city": {
          if (order == "none") {
            state.list.sort((a, b) => {
              return b.id - a.id;
            });
          } else if (order == "up") {
            state.list.sort((a, b) => {
              if (a.city > b.city) return 1;
              if (a.city == b.city) return 0;
              if (a.city < b.city) return -1;
            });
          } else if (order == "down") {
            state.list.sort((a, b) => {
              if (a.city < b.city) return 1;
              if (a.city == b.city) return 0;
              if (a.city > b.city) return -1;
            });
          }

          break;
        }
        case "status": {
          let status = state.statuses;
          if (order == "none") {
            state.list.sort((a, b) => {
              return b.id - a.id;
            });
          } else if (order == "up") {
            state.list.sort((a, b) => {
              if (
                status.indexOf(
                  status.filter((i) => {
                    return i.id == a.status;
                  })[0]
                ) >
                status.indexOf(
                  status.filter((i) => {
                    return i.id == b.status;
                  })[0]
                )
              )
                return 1;
              if (
                status.indexOf(
                  status.filter((i) => {
                    return i.id == a.status;
                  })[0]
                ) ==
                status.indexOf(
                  status.filter((i) => {
                    return i.id == b.status;
                  })[0]
                )
              )
                return 0;
              if (
                status.indexOf(
                  status.filter((i) => {
                    return i.id == a.status;
                  })[0]
                ) <
                status.indexOf(
                  status.filter((i) => {
                    return i.id == b.status;
                  })[0]
                )
              )
                return -1;
            });
          } else if (order == "down") {
            state.list.sort((a, b) => {
              if (
                status.indexOf(
                  status.filter((i) => {
                    return i.id == a.status;
                  })[0]
                ) <
                status.indexOf(
                  status.filter((i) => {
                    return i.id == b.status;
                  })[0]
                )
              )
                return 1;
              if (
                status.indexOf(
                  status.filter((i) => {
                    return i.id == a.status;
                  })[0]
                ) ==
                status.indexOf(
                  status.filter((i) => {
                    return i.id == b.status;
                  })[0]
                )
              )
                return 0;
              if (
                status.indexOf(
                  status.filter((i) => {
                    return i.id == a.status;
                  })[0]
                ) >
                status.indexOf(
                  status.filter((i) => {
                    return i.id == b.status;
                  })[0]
                )
              )
                return -1;
            });
          }
          break;
        }
        case "date": {
          if (order == "none") {
            state.list.sort((a, b) => {
              return b.id - a.id;
            });
          } else if (order == "up") {
            state.list.sort((a, b) => {
              let date_a =
                a.status == 2
                  ? "measuring_date"
                  : a.status == 5 || a.status == 6
                  ? "delivery_date"
                  : "expectation";
              let date_b =
                b.status == 2
                  ? "measuring_date"
                  : b.status == 5 || b.status == 6
                  ? "delivery_date"
                  : "expectation";
              if (a[date_a] > b[date_b] || a[date_a] == undefined) return 1;
              if (a[date_a] == b[date_b]) return 0;
              if (a[date_a] < b[date_b] || b[date_a] == undefined) return -1;
            });
          } else if (order == "down") {
            state.list.sort((a, b) => {
              let date_a =
                a.status == 2
                  ? "measuring_date"
                  : a.status == 5 || a.status == 6
                  ? "delivery_date"
                  : "expectation";
              let date_b =
                b.status == 2
                  ? "measuring_date"
                  : b.status == 5 || b.status == 6
                  ? "delivery_date"
                  : "expectation";
              if (a[date_a] < b[date_b] || b[date_b] == undefined) return 1;
              if (a[date_a] == b[date_b]) return 0;
              if (a[date_a] > b[date_b] || b[date_b] == undefined) return -1;
            });
          }

          break;
        }
      }
    },
    SORTING_MAIN(state, data) {
      state.sortData = data;
      if (Object.keys(data).length) {
        let list = state.allList;
        if (data.opiration.indexOf("sorting") != -1) {
          if (Object.keys(data).indexOf("status") != -1) {
            list = list.filter(
              (item) => data.status.indexOf(item.status) != -1
            );
          }
          if (Object.keys(data).indexOf("city") != -1) {
            list = list.filter((item) => data.city.indexOf(item.city) != -1);
          }
          if (Object.keys(data).indexOf("connection") != -1) {
            list = list.filter(
              (item) => data.connection.indexOf(item.connection) != -1
            );
          }
          if (Object.keys(data).indexOf("select_date") != -1) {
            if (data.select_date.indexOf("0") != -1) {
              list = list.filter(
                (item) => item.status == 2 && item.measuring_date != null
              );
            }
            if (data.select_date.indexOf("1") != -1) {
              list = list.filter(
                (item) => item.status == 2 && item.measuring_date == null
              );
            }
            if (data.select_date.indexOf("2") != -1) {
              list = list.filter(
                (item) => item.status == 5 && item.delivery_date != null
              );
            }
            if (data.select_date.indexOf("3") != -1) {
              list = list.filter(
                (item) => item.status == 5 && item.delivery_date == null
              );
            }
          }
          if (Object.keys(data).indexOf("date") != -1) {
            if (!data.date.end) {
              list = list.filter((item) => {
                let date =
                  item.status == 2
                    ? "measuring_date"
                    : item.status == 5 || item.status == 6
                    ? "delivery_date"
                    : "expectation";
                return item[date] == data.date.start;
              });
            }
            if (data.date.end) {
              list = list.filter((item) => {
                let date =
                  item.status == 2
                    ? "measuring_date"
                    : item.status == 5 || item.status == 6
                    ? "delivery_date"
                    : "expectation";
                return (
                  Date.parse(item[date]) >= Date.parse(data.date.start) &&
                  Date.parse(item[date]) <= Date.parse(data.date.end) &&
                  item[date] != undefined
                );
              });
            }
          }
        }
        if (data.opiration.indexOf("search") != -1) {
          if (data.type == "nick") {
            list = list.filter((item) =>
              item.nick.toLowerCase().includes(data.text.toLowerCase())
            );
          } else if (data.type == "number") {
            list = list.filter((item) =>
              item.number.toLowerCase().includes(data.text.toLowerCase())
            );
          } else if (data.type == "street") {
            list = list.filter((item) =>
              item.street.toLowerCase().includes(data.text.toLowerCase())
            );
          } else if (data.type == "no_order_1c") {
            list = list.filter((item) =>
              item.no_order_1c
                ? item.no_order_1c.includes(data.text.toLowerCase())
                : false
            );
          }
        }

        if (list.length) {
          state.list = list;
        } else state.list = [{ text: "Ничего не найдено" }];
      } else
        state.list = state.allList.filter(
          (item) => item.status != 7 && item.status != 8
        );
      store.commit("SORTING_BY_COLUMN", state.sortByColumnDate);
    },
    UPDATE_STATUSES(state, data) {
      state.statuses = data;
    },
    UPDATE_LIST(state, data) {
      state.allList = data;
    },
    SELECTIVE_UPDATE_LIST(state, data) {
      data.forEach((newItem) => {
        var index = state.allList.indexOf(
          state.allList.filter((item) => item.id == newItem.id)[0]
        );
        if (index != -1) {
          state.allList[index] = newItem;
        } else {
          state.allList.unshift(newItem);
        }
      });
    },
  },
  actions: {
    getStatuses(ctx) {
      ctx.commit("UPDATE_STATUSES", ctx.rootState.librares.statuses);
    },
    async fetchUpdateList(ctx, id) {
      const resp = await api.get("mainList.php?edit=" + id);
      ctx.commit("SELECTIVE_UPDATE_LIST", resp.data.list);
      ctx.commit("SORTING_MAIN", ctx.state.sortData);
      ctx.commit("SORTING_BY_COLUMN", ctx.state.sortByColumnDate);
    },
    async fetchAllList(ctx, limit = true) {
      const list = (await api.get("mainList.php?limit=100")).data;
      ctx.commit("UPDATE_LIST", list.list);
      ctx.commit("SORTING_MAIN", ctx.state.sortData);
      ctx.commit("SORTING_BY_COLUMN", ctx.state.sortByColumnDate);
      if (limit) {
        const list = (await api.get("mainList.php")).data;
        ctx.commit("UPDATE_LIST", list.list);
        ctx.commit("SORTING_MAIN", ctx.state.sortData);
        ctx.commit("SORTING_BY_COLUMN", ctx.state.sortByColumnDate);
      }
      // setTimeout(scan(ctx), 70000);
    },
  },
};
