import { createApp } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

store.dispatch("auth/attempt", localStorage.getItem("access_token"));

// Vuetify
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import '@mdi/font/css/materialdesignicons.css'

const vuetify = createVuetify({
     components,
     directives,
     icons: {
          defaultSet: 'mdi',
          aliases,
          sets: {
               mdi,
          },
     },
})


const app = createApp(App);
app.use(router);
app.use(store);
app.use(vuetify);
app.mount("#app");