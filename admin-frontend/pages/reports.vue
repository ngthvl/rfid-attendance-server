<script setup lang="ts">
import jwtMiddleware from "~/middleware/jwtMiddleware";

const tab = ref();

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

</script>

<template>
  <v-container>
    <v-card class="shadow mx-5" ref="printable">
      <v-card-title>Student Attendance</v-card-title>
      <v-tabs v-model="tab">
        <v-tab :value="1">Date Range</v-tab>
        <v-tab :value="2">Daily</v-tab>
      </v-tabs>
      <v-window v-model="tab">
        <v-window-item :value="1">
          <ReportsRange></ReportsRange>
        </v-window-item>
        <v-window-item :value="2">
          <ReportsDaily></ReportsDaily>
        </v-window-item>
      </v-window>
      
    </v-card>
  </v-container>
</template>


