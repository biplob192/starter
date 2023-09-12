import Api, { http, httpFile } from "./Api";
const END_POINT = "users";

export default {
  store(data) {
    // return Api.post(END_POINT, data);
    return http().post(END_POINT, data);
  },

  show(id) {
    return http().get(`${END_POINT}/${id}`);
  },

  all() {
    return http().get(END_POINT);
  },

  getData(params) {
    return http().get(`${END_POINT}/get/data?${params}`);
  },

  update(id, data) {
    return http().post(`${END_POINT}/${id}`, data);
  },

  delete(id) {
    return http().delete(`${END_POINT}/${id}`);
  },

  export() {
    return httpFile().get(`export/${END_POINT}`);
  },

  info() {
    return http().get(`${END_POINT}/current/info`);
  },
};
