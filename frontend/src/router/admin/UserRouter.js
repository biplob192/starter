import UsersIndex from "../../views/admin/user/UsersIndexView.vue";
import UsersCreate from "../../views/admin/user/UsersCreateView.vue";
import UsersEdit from "../../views/admin/user/UsersEditView.vue";
import UsersShow from "../../views/admin/user/UsersShowView.vue";

export default [
  {
    path: "/users",
    name: "UsersIndex",
    component: UsersIndex,
  },

  {
    path: "/users/create",
    name: "UsersCreate",
    component: UsersCreate,
  },

  {
    path: "/users/:id",
    name: "UsersShow",
    component: UsersShow,
  },

  {
    path: "/users/:id/edit",
    name: "UsersEdit",
    component: UsersEdit,
  },
];
