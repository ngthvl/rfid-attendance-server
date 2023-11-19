<script setup lang="ts">

import SideBar from "~/components/SideBar.vue";
import {isApiLoading} from "~/composables/useApi";
</script>

<template>
  <v-layout>
    <v-main>
      <SideBar />
      <v-progress-linear
          v-if="isApiLoading"
          indeterminate
          color="cyan"
      ></v-progress-linear>
      <MenuBar />
      <NuxtPage />
    </v-main>
  </v-layout>
</template>