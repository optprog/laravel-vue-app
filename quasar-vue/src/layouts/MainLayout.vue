<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          @click="leftDrawerOpen = !leftDrawerOpen"
          icon="menu"
          aria-label="Menu"
        />

        <q-toolbar-title>
          Design and Learn
        </q-toolbar-title>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      show-if-above
      bordered
      content-class="bg-grey-1"
    >
      <q-list>
        <q-item />
        <SideMenuLink
          v-for="link in sideMenuLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import SideMenuLink from 'components/SideMenuLink';

export default {
  name: 'MainLayout',

  components: {
    SideMenuLink,
  },

  data() {
    return {
      leftDrawerOpen: false,
      sideMenuLinks: [
        {
          title: 'Home',
          caption: '',
          icon: '',
          link: '/',
        },
        {
          title: 'Login',
          caption: '',
          icon: '',
          link: '/login',
        },
        {
          title: 'Create new account',
          caption: '',
          icon: '',
          link: '/register',
        },
      ],
    };
  },
};
</script>
