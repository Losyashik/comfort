import axios from "@/api";

function translit(word) {
  var answer = "";
  var converter = {
    а: "a",
    б: "b",
    в: "v",
    г: "g",
    д: "d",
    е: "e",
    ё: "e",
    ж: "zh",
    з: "z",
    и: "i",
    й: "y",
    к: "k",
    л: "l",
    м: "m",
    н: "n",
    о: "o",
    п: "p",
    р: "r",
    с: "s",
    т: "t",
    у: "u",
    ф: "f",
    х: "h",
    ц: "c",
    ч: "ch",
    ш: "sh",
    щ: "sch",
    ь: "",
    ы: "y",
    ъ: "",
    э: "e",
    ю: "yu",
    я: "ya",

    А: "A",
    Б: "B",
    В: "V",
    Г: "G",
    Д: "D",
    Е: "E",
    Ё: "E",
    Ж: "Zh",
    З: "Z",
    И: "I",
    Й: "Y",
    К: "K",
    Л: "L",
    М: "M",
    Н: "N",
    О: "O",
    П: "P",
    Р: "R",
    С: "S",
    Т: "T",
    У: "U",
    Ф: "F",
    Х: "H",
    Ц: "C",
    Ч: "Ch",
    Ш: "Sh",
    Щ: "Sch",
    Ь: "",
    Ы: "Y",
    Ъ: "",
    Э: "E",
    Ю: "Yu",
    Я: "Ya",
  };

  for (var i = 0; i < word.length; ++i) {
    if (converter[word[i]] == undefined) {
      answer += word[i];
    } else {
      answer += converter[word[i]];
    }
  }

  return answer;
}

export default {
  state: {
    catalog: {},
    list: [],
    preview: [],
  },
  getters: {
    GET_LIST(state) {
      return state.list;
    },
    GET_ALL_CATALOG_LIST(state) {
      return state.catalog;
    },
    GET_PREVIEW(state) {
      return state.preview;
    },
  },
  mutations: {
    SORTING_CATALOG(state, { type, data }) {
      state.list = [];
      console.log(data);
      if (Object.keys(data).length) {
        let list = state.catalog[type];
        Object.keys(data).forEach((item) => {
          if (["maker", "collection", "color", "size"].includes(item)) {
            list = list.filter((el) => data[item].includes(el["id_" + item]));
          } else if (["type", "base", "class", "destination"].includes(item)) {
            list = list.filter((el) =>
              data[item].includes(el.collection_data["id_" + item])
            );
          } else if (item == "width") {
            list = list.filter((el) => {
              return el.width.some((e) => {
                return data[item].includes(e.width);
              });
            });
          } else if (item == "add") {
            if (type == "linoleum")
              list = list.filter((el) => (el.show_product ? true : false));
          } else if (item == "search") {
            list = list.filter(
              (el) =>
                el.name.toLowerCase().includes(data.search.toLowerCase()) ||
                el.name
                  .toLowerCase()
                  .includes(translit(data.search.toLowerCase()))
            );
          } else {
            if (type == "linoleum")
              list = list.filter((el) =>
                data[item].includes(el.collection_data[item])
              );
            else list = list.filter((el) => data[item].includes(el[item]));
          }
        });
        if (list.length) state.list = list;
        else state.list = [{ text: "Ничего не найдено" }];
      } else state.list = state.catalog[type];
    },
    SET_CATALOG(state, { catalog, type }) {
      catalog.length != 0 ? (state.catalog = catalog) : false;
      state.list = state.catalog[type];
    },
    UPDATE_PREVIEW(state, id) {
      state.preview = state.list.filter((item) => item.id == id)[0];
    },
  },
  actions: {
    async fetchCatalog(ctx) {
      const data = (await axios.post("catalog/CatalogList.php")).data;
      ctx.commit("SET_CATALOG", { catalog: data, type: "linoleum" });
    },
  },
};
