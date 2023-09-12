<template>
  <div>
    <nav>
      <RouterLink to="/">Home</RouterLink>
      <RouterLink v-show="logged_in" v-if="user_role != 'Users'" to="/users">Users</RouterLink>

      <RouterLink v-show="!logged_in" to="/register">Registration</RouterLink>
      <RouterLink v-show="!logged_in" to="/login">Login</RouterLink>
      <a v-show="logged_in" href="#" @click="logout">Logout</a>
      {{ (user_role) }}
      {{ (userInfo.phone) }}
      {{ (userInfo.roles[0].guard_name ? userInfo.roles[0].guard_name : '') }}
    </nav>
    <hr />
  </div>
</template>
<script>
import { mapState, mapGetters, mapActions } from "vuex";
import { RouterLink } from "vue-router";

export default {
  name: "NavBar",

  data() {
    return {
      // logged_in: this.$store.state.logged_in_status,
    };
  },

  mounted() {
  },

  computed: {
    ...mapGetters({
      logged_in: "loginStatus",
      user_role: "userRole",
    }),
    ...mapGetters("admin", ["userInfo",]),
  },

  methods: {
    logout() {
      console.log("Logout Clicked!!");

      localStorage.removeItem("access_token");
      localStorage.removeItem("refresh_token");
      localStorage.removeItem("user_info");

      window.location.assign("/login");
    },
  },
};
</script>
<style scoped>
nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  padding: 10px;
}

nav a.router-link-exact-active {
  color: #fff;
  background-color: orangered;
  border-radius: 5px;
}

a:hover {
  color: orangered;
  text-decoration: underline;
}

nav a.router-link-exact-active:hover {
  text-decoration: none;
}

nav a {
  display: inline-block;
  font-size: 21px;
  text-decoration: none;
  padding: 5px;
  margin: 5px;
  border-left: 1px solid var(--color-border);
}

.main {
  width: 80%;
  margin: auto;
}
</style>
