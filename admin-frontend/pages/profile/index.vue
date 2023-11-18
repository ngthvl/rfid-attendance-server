<script setup lang="ts">

import jwtMiddleware from "../middleware/jwtMiddleware";

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

</script>

<template>
  <v-container>
    <v-row>
      <v-col cols="3">
        <v-card class="text-center">
          <v-card-title>Personal Details</v-card-title>
        </v-card>
      </v-col>
      <v-col cols="3">
        <v-card class="text-center">
          <v-card-title>Update Password</v-card-title>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>
