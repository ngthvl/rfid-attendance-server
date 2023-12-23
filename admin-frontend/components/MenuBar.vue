<script setup lang="ts">
import { useLogout } from "~/composables/useLogin";
import {isApiLoading} from "~/composables/useApi";
</script>

<template>
  <v-app-bar :elevation="2">
    <template #default>
      <v-app-bar-title>Admininstration</v-app-bar-title>
      <v-app-bar-nav-icon>
        <v-icon icon="mdi-account-circle-outline"></v-icon>
        <v-menu activator="parent">
          <v-list>
            <v-list-item title="Account Settings" to="/profile"></v-list-item>
            <v-list-item title="Logout" @click="useLogout"></v-list-item>
          </v-list>
        </v-menu>
      </v-app-bar-nav-icon>
    </template>
    <template #extension>
      <v-progress-linear
          v-if="isApiLoading"
          indeterminate
          color="cyan"
      ></v-progress-linear>
    </template>
  </v-app-bar>
</template>

<style>
.v-toolbar__extension{
  max-height: 10px!important;
}
</style>