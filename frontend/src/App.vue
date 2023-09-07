<template>
  <NavBar v-show="logged_in" />
  <div class="main">
    <RouterView />
  </div>
</template>

<script>
import { RouterView } from "vue-router";
import NavBar from "./components/NavBar.vue";
import { mapGetters } from "vuex";

export default {
  components: {
    NavBar: NavBar,
  },

  computed: {
    ...mapGetters({
      logged_in: "loginStatus",
      user_role: "userRole",
    }),
  },
};
</script>

<style scoped>
.main {
  width: 90%;
  margin: auto;
}
</style>