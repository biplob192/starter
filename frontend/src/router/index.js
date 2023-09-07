import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";

/*import router*/
import AuthRouter from "./auth/AuthRouter";
import UserRouter from "./user/UserRouter";
import AdminRouter from "./admin/AdminRouter";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "Home",
      component: HomeView,
    },
    {
      path: "/home",
      redirect: "/",
    },
    {
      path: "/about",
      name: "About",
      component: () => import("../views/AboutView.vue"),
    },

    ...AuthRouter,
    ...AdminRouter,
    ...UserRouter,
  ],
});

router.beforeEach((to, from, next) => {
  if (to.name !== "Login" && !localStorage.getItem("access_token") && to.name !== "Register") next({ name: "Login" });
  else next();
});

export default router;
