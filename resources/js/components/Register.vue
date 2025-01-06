<template>
  <q-page class="q-pa-md flex flex-center">
    <q-card class="q-pa-lg" style="width: 400px">
      <q-card-section>
        <div class="text-h6 text-primary">Register</div>
      </q-card-section>

      <q-card-section>
        <q-form @submit.prevent="register">
          <q-input
            filled
            v-model="name"
            label="Name"
            type="text"
            :rules="[val => !!val || 'Name is required']"
            class="q-mb-md"
          />

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
            label="Register"
            color="primary"
            class="q-mt-md full-width"
          />
        </q-form>
      </q-card-section>

      <q-card-actions align="center">
        <q-btn
          flat
          label="Already have an account? Login"
          class="text-primary"
          @click="goToLogin"
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
      name: "",
      email: "",
      password: "",
    };
  },
  methods: {
    async register() {
      try {
        const response = await axios.post("/api/register", {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password,
        });
        localStorage.setItem("authToken", response.data.token);
        axios.defaults.headers.common["Authorization"] = `Bearer ${response.data.token}`;
        this.$router.push("/chat");
      } catch (error) {
        this.$q.notify({
          type: "negative",
          message: "Registration failed. Please try again.",
        });
      }
    },
    goToLogin() {
      this.$router.push("/login");
    },
  },
};
</script>

<style scoped>
.text-primary {
  color: #027be3;
}
</style>
