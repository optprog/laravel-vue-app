<template>
  <div class="q-pa-md row justify-center">
    <q-form
      @submit="onSubmit"
      @reset="onReset"
      class="q-gutter-lg col col-sm-8 col-md-6"
    >
      <h3 class="text-center">Login form</h3>
      <q-input
        filled
        type="email"
        v-model="email"
        label="Your email *"
        lazy-rules
        square
        :rules="rules.name"
      />

      <q-input
        filled
        type="password"
        v-model="password"
        label="Your password *"
        lazy-rules
        square
        :rules="rules.password"
      />

      <div>
        <q-btn label="Submit" type="submit" color="primary" />
        <q-btn
          label="Reset"
          type="reset"
          color="primary"
          flat
          class="q-ml-sm"
        />
      </div>
      <div>
        <label
          ><router-link to="/register">Register from here</router-link>
        </label>
      </div>
    </q-form>
  </div>
</template>

<script>
import { sotoreToken } from '../auth';
export default {
  data() {
    return {
      email: null,
      password: null,
      rules: {
        name: [val => (val && val.length > 0) || 'Please enter your email'],
        password: [
          val => (val && val.length > 0) || 'Please enter your password',
        ],
      },
    };
  },

  methods: {
    onSubmit() {
      const { email, password } = this;
      this.$axios
        .post('/api/auth/login', { email, password })
        .then(response => {
          const { access_token } = response.data;
          storeToken(access_token);
          /* Login is successful -- Redirerct user or something */
          this.$q.notify({
            color: 'green-4',
            textColor: 'white',
            icon: 'cloud_done',
            message: 'Logged in successfully.',
          });
        })
        .catch(error => {
          console.log(error.response.data);
        });
    },

    onReset() {
      this.name = null;
      this.age = null;
    },
  },
};
</script>

<style scoped>
label {
  font-size: 1rem;
}
</style>
