import userState from "./user/state";
import * as userGetters from "./user/getters";
import * as userMutations from "./user/mutations";
import * as userActions from "./user/actions";

const store = {
  namespaced: true,
  state: {
    ...userState,
  },
  getters: {
    ...userGetters
  },
  mutations: {
    ...userMutations
  },
  actions: {
    ...userActions
  },
};

export default store;