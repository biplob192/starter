<template>
  <div class="d-flex align-center justify-center" style="height: 100vh">
    <v-card class="mx-auto pa-12 pb-8" elevation="8" rounded="lg">
      <v-sheet width="400" class="mx-auto">
        <v-form fast-fail @submit.prevent="handleLogin()">
          <v-text-field v-model="loginData.email" :rules="[rules.required, rules.email]" density="compact" placeholder="Email" prepend-inner-icon="mdi-email-outline" variant="outlined"></v-text-field>
          <v-text-field v-model="loginData.password" :rules="[rules.required]" :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'" :type="visible ? 'text' : 'password'" density="compact"
            placeholder="Password" prepend-inner-icon="mdi-lock-outline" variant="outlined" @click:append-inner="visible = !visible"></v-text-field>

          <a href="#" class="text-body-2 font-weight-regular">Forgot Password?</a>
          <v-btn type="submit" color="primary" block class="mt-2">Sign in</v-btn>
        </v-form>
        <div class="mt-2">
          <p class="text-body-2">Don't have an account? <RouterLink to="/register">Sign Up</RouterLink>
          </p>
        </div>
      </v-sheet>
    </v-card>
  </div>
</template>

<script>
import Auth from "../../services/api/AuthApi";
import { mapState, mapGetters, mapActions, mapMutations } from "vuex";

export default {
  name: "LoginView",
  components: {},
  data() {
    return {
      loginData: {
        email: "",
        password: "",
      },
      rules: {
        required: value => !!value || 'Field is required',
        email: value => !!/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(value) || 'E-mail must be valid',
      },
      visible: false,
    };
  },

  computed: {
    ...mapState(["access_token", "user_info"]),
    ...mapState("auth", ["login_response"]),
  },


  methods: {
    ...mapActions("auth", ["login"]),

    handleLogin: async function () {
      if (this.loginData.email != '' && this.loginData.password != '') {
        let formData = new FormData();
        formData.append("email", this.loginData.email);
        formData.append("password", this.loginData.password);

        try {
          let info = await this.login(formData);
          let response = JSON.parse(info);

          if (response.status == 200) {
            this.$router.push({ name: "Home" });
          }

        } catch (e) {
          // console.log(e);
          alert(e.response.data.message);
        }
      }
    },
  },
};
</script>

<style scoped>
.fill-height {
  min-height: 100vh;
}

.login_text {
  text-align: center;
  padding: 5px;
  color: gray;
}
</style>