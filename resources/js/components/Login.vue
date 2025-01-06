<template>
  <q-page class="q-pa-md flex flex-center">
    <q-card class="q-pa-lg" style="width: 400px">
      <q-card-section>
        <div class="text-h6 text-primary">Login</div>
      </q-card-section>

      <q-card-section>
        <q-form @submit.prevent="login">

          <q-input
            filled
            v-model="email"
            label="Email"
            type="email"
            :rules="[val => !!val || 'Email is required']"
            class="q-mb-md"
          />

          <q-input
            filled
            v-model="password"
            label="Password"
            type="password"
            :rules="[val => !!val || 'Password is required']"
            class="q-mb-md"
          />

          <q-btn
            type="submit"
            label="Login"
            color="primary"
            class="q-mt-md full-width"
          />
        </q-form>
      </q-card-section>
      <q-card-actions align="center">
        <q-btn
          flat
          label="Don't have an account? Register"
          class="text-primary"
          @click="goToRegister"
        />
      </q-card-actions>
    </q-card>
  </q-page>
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      email: "",
      password: "",
    };
  },
  methods: {
    async login() {
      try {
        const response = await axios.post("/api/login", {
          email: this.email,
          password: this.password,
        });
        localStorage.setItem("authToken", response.data.token);
        axios.defaults.headers.common["Authorization"] = `Bearer ${response.data.token}`;
        this.$router.push("/chat");
      } catch (error) {
        this.$q.notify({
          type: "negative",
          position: "top-right",
          message: "Login failed. Please check your credentials.",
        });
      }
    },
    goToRegister() {
      this.$router.push("/register");
    },
  },
};
</script>

<style scoped>
.text-primary {
  color: #027be3;
}
</style>

  