<template>
  <div class="q-pa-md row justify-center">
    <q-form
      @submit="onSubmit"
      @reset="onReset"
      class="q-gutter-lg col col-sm-8 col-md-6"
    >
      <h3 class="text-center">Register Form</h3>
      <q-input
        filled
        v-model="name"
        label="Your name *"
        lazy-rules
        square
        :rules="rules.name"
      />

      <q-input
        filled
        type="email"
        v-model="email"
        label="Your email *"
        lazy-rules
        square
        :rules="rules.email"
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
        <label>
          <router-link to="/login">Login from here</router-link>
        </label>
      </div>
    </q-form>
  </div>
</template>

<script>
import { storeToken } from '../auth';

export default {
  data() {
    return {
      name: null,
      email: null,
      password: null,
      rules: {
        name: [val => (val && val.length > 0) || 'Please enter your name'],
        email: [val => (val && val.length > 0) || 'Please enter your email'],
        password: [
          val => (val && val.length > 0) || 'Please enter your password',
        ],
      },
    };
  },

  methods: {
    onSubmit() {
      const { name, email, password } = this;
      this.$axios
        .post('/api/auth/register', { name, email, password })
        .then(response => {
          const { access_token, error } = response.data;

          if (error) {
            this.$q.notify({
              color: 'red-4',
              textColor: 'white',
              icon: 'error',
              message: error,
            });
            return;
          }

          /* Registeration is successful and user is logged in  */
          storeToken(access_token);
          this.$q.notify({
            color: 'green-4',
            textColor: 'white',
            icon: 'check',
            message: 'Created a new account. You can log in now.',
          });
        })
        .catch(error => {
          console.log(error);
        });
    },

    onReset() {
      this.name = null;
      this.password = null;
    },
  },
};
</script>

<style scoped>
label {
  font-size: 1rem;
}
</style>
