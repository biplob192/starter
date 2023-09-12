import User from "../../../../services/api/UserApi";


export const getUserInfo = ({ commit }) => {
  return User.info().then((response) => {
    commit("SET_USER_INFO", response.data);

    return JSON.stringify(response);
  });
};

// export const getUsers = ({ commit }) => {
//   return User.all().then((response) => {
//     commit("SET_USERS", response.data);

//     return JSON.stringify(response);
//   });
// };

// export const getUsersData = ({ commit }, params) => {
//   return User.getData(params).then((response) => {
//     commit("SET_USERS_GET_DATA", response.data);

//     return JSON.stringify(response);
//   });
// };

// export const getUser = ({ commit }, id) => {
//   User.show(id).then((response) => {
//     commit("SET_USER", response.data);
//   });
// };

// export const storeUser = ({ commit, dispatch, rootState }, data) => {
//   return User.store(data).then((response) => {
//     var data = response.data;
//     commit("ADD_USER", { data });

//     return JSON.stringify(response);
//   });
// };

// export const updateUser = ({ commit }, data) => {
//   return User.update(data["0"], data["1"]).then((response) => {
//     var data = response.data;
//     commit("UPDATE_USER", { data });

//     return JSON.stringify(response);
//   });
// };

// export const deleteUser = ({ commit }, id) => {
//   return User.delete(id).then((response) => {
//     commit("DELETE_USER", { id: id, data: response.data });

//     return JSON.stringify(response);
//   });
// };

// export const exportUsers = () => {
//   return User.export().then((response) => {
//     const url = window.URL.createObjectURL(new Blob([response.data]));
//     const link = document.createElement('a');

//     link.href = url;
//     link.setAttribute('download', 'users.xlsx');
//     document.body.appendChild(link);

//     link.click();
//     document.body.removeChild(link);
//   });
// };