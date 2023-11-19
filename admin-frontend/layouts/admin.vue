<script setup lang="ts">

import SideBar from "~/components/SideBar.vue";
</script>

<template>
  <v-layout>
    <v-main>
      <SideBar />
      <MenuBar />
      <NuxtPage />
    </v-main>
  </v-layout>
</template>