<script setup lang="ts">
import { useNotificationStore } from '~/models/notification';


const notificationStore = useNotificationStore();

const { notifications } = storeToRefs(notificationStore);

</script>

<template>
  <v-layout>
    <v-main>
      <SideBar />
      <MenuBar />
      <NuxtPage style="margin-top: 1px"/>
    </v-main>

    <v-snackbar 
      v-for="(notif, key) in notifications" 
      :key="key" 
      v-model="notif.shown" 
      :timeout="notif.timeout"
      location="top center"
      color="success"
    >
      {{ notif.title }}
    </v-snackbar>
  </v-layout>
</template>