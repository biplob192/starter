<template>
  <div class="d-flex align-center justify-center" style="height: 100vh">
    <v-card class="mx-auto pa-12 pb-8" elevation="8" rounded="lg">
      <v-sheet width="400" class="mx-auto">
        <v-form fast-fail @submit.prevent="handleRegister()">
          <v-text-field v-model="loginData.name" :rules="[rules.required]" density="compact" placeholder="Enter full name" prepend-inner-icon="mdi-account-outline" variant="outlined"></v-text-field>
          <v-text-field v-model="loginData.email" :rules="[rules.required, rules.email]" density="compact" placeholder="Email address" prepend-inner-icon="mdi-email-outline" :type="'email'"
            variant="outlined"></v-text-field>
          <v-text-field v-model="loginData.mobile" :rules="[rules.required]" density="compact" placeholder="Mobile number" prepend-inner-icon="mdi-phone-outline" :type="'number'"
            variant="outlined"></v-text-field>
          <v-text-field v-model="loginData.password" :rules="[rules.required]" :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'" :type="visible ? 'text' : 'password'" density="compact"
            placeholder="Enter password" prepend-inner-icon="mdi-lock-outline" variant="outlined" @click:append-inner="visible = !visible"></v-text-field>
          <v-text-field v-model="loginData.password_confirmation" :rules="[rules.required]" :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'" :type="visible ? 'text' : 'password'"
            density="compact" placeholder="Confirm password" prepend-inner-icon="mdi-lock-outline" variant="outlined" @click:append-inner="visible = !visible"></v-text-field>

          <v-btn type="submit" color="primary" block class="mt-2">Sign Up</v-btn>
        </v-form>
        <div class="mt-2">
          <p class="text-body-2">Already have an account? <RouterLink to="/login">Sign in</RouterLink>
          </p>
        </div>
      </v-sheet>
    </v-card>
  </div>
</template>

<script>
import { mapActions } from "vuex";

export default {
  name: "RegisterView",
  components: {},
  data() {
    return {
      loginData: {
        name: "",
        email: "",
        mobile: "",
        password: "",
        password_confirmation: "",
      },

      rules: {
        required: value => !!value || 'Field is required',
        email: value => !!/^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(value) || 'E-mail must be valid',
      },
      visible: false,

      errors: {},
    };
  },

  methods: {
    ...mapActions("auth", ["register"]),

    handleRegister: async function () {
      if (this.loginData.name != '' && this.loginData.email != '' && this.loginData.mobile != '' && this.loginData.password != '' && this.loginData.password_confirmation != '') {
        let formData = new FormData();
        formData.append("name", this.loginData.name);
        formData.append("email", this.loginData.email);
        formData.append("mobile", this.loginData.mobile);
        formData.append("password", this.loginData.password);
        formData.append("password_confirmation", this.loginData.password_confirmation);

        try {
          let info = await this.register(formData);
          let response = JSON.parse(info);

          if (response.status == 201) {
            this.$router.push({ name: "Home" });
          }
        } catch (e) {
          alert(e.response.data.message);
        }
      }
    },
  },
};
</script>

<style scoped></style>
