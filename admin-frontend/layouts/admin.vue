<script setup lang="ts">

import SideBar from "~/components/SideBar.vue";
</script>

<template>
  <v-layout>
    <v-main>
      <SideBar />
      <MenuBar />
      <NuxtPage style="margin-top: 1px"/>
    </v-main>
  </v-layout>
</template>