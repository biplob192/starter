import { createStore } from "vuex";
import state from "./state";
import * as getters from "./getters";
import * as mutations from "./mutations";
import * as actions from "./actions";

// Import Modules
import auth from "./modules/auth";
import item from "./modules/item";
import requisition from "./modules/requisition";
import stock from "./modules/stock";
import user from "./modules/user";

const store = createStore({
  namespaced: true,
  state,
  getters,
  mutations,
  actions,

  modules: {
    auth,
    item,
    requisition,
    stock,
    user,
  },
});

export default store;
