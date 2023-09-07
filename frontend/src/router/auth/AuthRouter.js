import RegisterView from "../../views/auth/RegisterView.vue";
import LoginView from "../../views/auth/LoginView.vue";

export default [
     {
          path: "/register",
          name: "Register",
          component: RegisterView,
          beforeEnter: () => {
               if (localStorage.getItem("access_token")) {
                    return false
               }
          },
     },

     {
          path: "/login",
          name: "Login",
          component: LoginView,
          beforeEnter: () => {
               if (localStorage.getItem("access_token")) {
                    return false
               }
          },
     },
];