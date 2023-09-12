<template>
  <div>
    <h2>Home page content</h2>
  </div>
</template>
<script>
import { mapState, mapGetters, mapActions } from "vuex";

export default {
  name: "HomeView",

  data() {
    return {
      base_url: this.$store.state.baseUrl,
      access_token_local: localStorage.getItem("access_token"),
    };
  },

  computed: {
    ...mapGetters({
      logged_in: "loginStatus",
    }),
  },

  mounted() {
    if (this.logged_in) {
      this.getUserInfo();
    }
  },

  methods: {
    ...mapActions("admin", ["getUserInfo"]),
  },
};
</script>
