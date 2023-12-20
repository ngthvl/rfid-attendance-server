<script setup lang="ts">

import jwtMiddleware from "~/middleware/jwtMiddleware";
import { useSettingsStore } from "~/models/settings";

const settingsStore = useSettingsStore();

const { settings } = storeToRefs(settingsStore);

onMounted(()=>{
  settingsStore.listSettings()
});

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
});

</script>

<template>
  <v-container>
    <v-card class="shadow mt-4 mx-5">
      <v-card-title>Settings</v-card-title>

      <v-card-item>
        <v-text-field label="Current School Year" v-model="settings.curriculum_settings.school_year"></v-text-field>
      </v-card-item>

      <v-card-item>
        <v-btn color="success" @click="settingsStore.updateSettings">Save Settings</v-btn>
      </v-card-item>
    </v-card>
  </v-container>
</template>
