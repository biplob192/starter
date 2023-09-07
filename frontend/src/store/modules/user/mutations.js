export const SET_USERS = (state, response) => {
  state.users = response.data;
  // state.users = response.data.data;
};

export const SET_USERS_GET_DATA = (state, response) => {
  state.users = response.data.data;
};

export const SET_USER = (state, response) => {
  state.user = response.data;
};

export const ADD_USER = (state, response) => {
  if (state.users.push(response.data.data)) {
    state.success_message = response.data.message;
  } else {
    state.success_message = "";
  }
};

export const UPDATE_USER = (state, response) => {
  const index = state.users.findIndex(user => user.id === response.data.data.id);

  if (index !== -1) {
    if (state.users.splice(index, 1, response.data.data)) {
      state.success_message = response.data.message;
    } else {
      state.success_message = "";
    }
  }
};

export const DELETE_USER = (state, response) => {
  var id = response.id;
  if (id) {
    state.users = state.users.filter((item) => {
      return id !== item.id;
    });

    state.success_message = response.data.message;
  } else {
    state.success_message = "";
  }
};