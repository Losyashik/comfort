import { createRouter, createWebHistory } from "vue-router";
// import store from "@/store";

import Main from "./../views/MainView";

const routes = [
  {
    path: "/",
    name: "Main",
    component: Main,
    meta: { title: "Вход" },
  },
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
