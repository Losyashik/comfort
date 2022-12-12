import { createRouter, createWebHistory } from "vue-router";
import store from "@/store";
import Auth from "./../components/AuthWindow";
import Main from "./../components/Main/MainPage";
import Admin from "./../components/AdminPanel/NavBlock";
import AddProducts from "./../components/AdminPanel/add/AddProducts";
import Pricing from "./../components/AdminPanel/pricing/EditPricing";
import Providers from "./../components/AdminPanel/pricing/EditProviders";
import AddPrice from "./../components/AdminPanel/pricing/AddPrice";
import NotRight from "./../components/AdminPanel/NotRights";
import AddMain from "./../components/AdminPanel/add/AddMain";
import Form from "./../components/AdminPanel/add/FormBody";
import Users from "./../components/AdminPanel/users/ManagementUsers";
import AddAllowances from "./../components/AdminPanel/pricing/AddAllowances";
import SchedulesMain from "./../components/Schedules/SchedulesMain";
import SchedulesBody from "./../components/Schedules/SchedulesBody";
import Accounting from "./../components/AdminPanel/accounting/AccountingBody";
import ScriptBody from "./../components/Scripts/ScriptBody";
import ScriptMain from "./../components/Scripts/ScriptMain";
import PersonalBody from "./../components/Scripts/PersonalBody";

async function loadData() {
  if (
    !store.state.librares.cites.length &&
    !store.state.librares.statuses.length &&
    !store.state.librares.payment.length &&
    !store.state.librares.toc.length
  ) {
    await store.dispatch("getAllLists");
    store.dispatch("getStatuses");
  }
  if (!store.state.MainList.allList.length) {
    await store.dispatch("fetchAllList");
  }
  if (!store.state.CatalogList.catalog.length) {
    await store.dispatch("fetchCatalog");
  }

  return true;
}

const routes = [
  {
    path: "/login",
    name: "Auth",
    component: Auth,
    meta: { title: "Вход" },
  },
  {
    path: "/",
    children: [
      {
        path: "",
        name: "Main",
        component: Main,
        meta: { title: "Главная" },
      },
      {
        path: "application-:id",
        name: "App",
        component: Main,
        meta: { title: "Заявка №" },
      },
    ],
  },
  {
    path: "/admin",
    name: "Admin",
    component: Admin,
    children: [
      {
        path: "add",
        component: AddProducts,
        children: [
          {
            path: "",
            name: "Add",
            component: AddMain,
            meta: { title: "Добавление" },
          },
          {
            path: ":product-:type",
            name: "Form",
            component: Form,
            meta: { title: "Добавление " },
            props: true,
          },
        ],
      },
      {
        path: "users",
        name: "Users",
        component: Users,
        meta: { title: "Пользователи" },
      },
      {
        path: "accounting",
        name: "Accounting",
        component: Accounting,
        meta: { title: "Бухгалтерия" },
      },

      {
        path: "pricing",
        name: "Pricing",
        component: Pricing,
        meta: { title: "Ценообразования" },
        children: [
          { path: "providers", name: "Providers", component: Providers },
          { path: "add-price", name: "AddPrice", component: AddPrice },
          {
            path: "add-allowances",
            name: "AddAllowances",
            component: AddAllowances,
          },
        ],
      },
    ],
  },
  {
    path: "/schedules",
    name: "Schedules",
    component: SchedulesMain,
    meta: { title: "Графики" },
    children: [
      {
        path: ":schedules",
        name: "SchedulesBody",
        component: SchedulesBody,
        meta: { title: "График " },
      },
    ],
  },
  {
    path: "/no_rights",
    name: "NotRight",
    component: NotRight,
    meta: { title: "Нет прав" },
  },
  {
    path: "/scripts",
    name: "Scripts",
    component: ScriptBody,
    meta: { title: "Скрипты" },
    children: [
      {
        path: "",
        name: "ScriptMain",
        component: ScriptMain,
        meta: { title: "Скрипты" },
      },
      {
        path: "./sctipt-:id",
        name: "PersonalBody",
        component: PersonalBody,
        meta: { title: "Скрипты" },
        props: true,
      },
    ],
  },
  {
    path: "/:pathMatch(.*)*",
    redirect: "/",
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});
router.beforeEach((to, from, next) => {
  switch (to.name) {
    case "App": {
      if (to.params.id == "new") {
        document.title = "Новая заявка";
      } else document.title = to.meta.title + to.params.id;
      break;
    }
    case "Form": {
      document.title = to.meta.title;
      break;
    }
    default: {
      document.title = to.meta.title;
      break;
    }
  }
  if (to.name !== "Auth" && !localStorage.user) next({ name: "Auth" });
  else {
    if (to.name != "Auth" && localStorage.user) {
      loadData();
    }
    if (to.name == "Admin") {
      if (JSON.parse(localStorage.user).rights.includes("3")) {
        next({ name: "Add" });
      } else if (JSON.parse(localStorage.user).rights.includes("4")) {
        next({ name: "Providers" });
      } else if (JSON.parse(localStorage.user).rights.includes("2")) {
        next({ name: "AddPrice" });
      } else if (JSON.parse(localStorage.user).rights.includes("5")) {
        next({ name: "Users" });
      } else {
        next({ name: "NotRight" });
      }
    } else {
      switch (to.name) {
        case "Add":
        case "Form": {
          if (!JSON.parse(localStorage.user).rights.includes("3")) {
            next({ name: "NotRight" });
          }
          break;
        }
        case "Providers": {
          if (!JSON.parse(localStorage.user).rights.includes("4")) {
            next({ name: "NotRight" });
          }
          break;
        }
        case "AddPrice":
        case "AddAllowances": {
          if (!JSON.parse(localStorage.user).rights.includes("2")) {
            next({ name: "NotRight" });
          }
          break;
        }
        case "Users": {
          if (!JSON.parse(localStorage.user).rights.includes("5")) {
            next({ name: "NotRight" });
          }
          break;
        }
      }
      next();
    }
  }
});

export default router;
