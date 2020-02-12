<template>
  <div>
    <form>
      <div>
        <label for="email">Email</label>
        <input type="email" v-model="email" />
        <label for="password">Password</label>
        <input type="password" v-model="password" />
      </div>
      <button type="submit" v-on:click.prevent="submitLogin">Login</button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Login',
  data: function() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    submitLogin: function() {
      const { email, password } = this;

      /* 
        Example on how to send a request to Laravel API.
      */
      axios
        .post('/api/auth/login', { email, password })
        .then(response => {
          console.log(response.data);
          localStorage.setItem('token', response.data.access_token);
        })
        .catch(err => console.log(err.response.data));
    },
  },
};
</script>

<style scoped>
input {
  margin: 20px;
}
button {
  padding: 15px 40px;
}
</style>
