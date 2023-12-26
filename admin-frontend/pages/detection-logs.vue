<script setup lang="ts">

import jwtMiddleware from "~/middleware/jwtMiddleware";

import {DetectionLog as DetectionLogType, useDetectionLogStore} from '~/models/detectionLog'
import {storeToRefs} from "pinia";

const detectionStore = useDetectionLogStore();

const { detections, filters, meta } = storeToRefs(detectionStore)

onMounted(async ()=>{
  detectionStore.listDetections();
})

const config = useRuntimeConfig();

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

</script>

<template>
  <v-container>
    <v-card class="shadow mt-4 mx-5">
      <v-card-title>Detection Logs</v-card-title>
      <v-card-item>
        <v-text-field v-model="filters.search" class="mt-3" prepend-inner-icon="mdi-magnify" variant="outlined" label="Search"></v-text-field>
      </v-card-item>
      <v-card-item>
        <v-table :hover="true" style="font-family: monospace">
          <thead>
          <tr>
            <th>Date</th>
            <th>Family Name</th>
            <th>Given Name</th>
            <th>Contact #</th>
            <th>Contact Person</th>
            <th>Terminal</th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(detection, key) in detections" :key="key">
              <td><small>{{ detection.detection_dt }}</small></td>
              <td>{{ detection.allocated.last_name }}</td>
              <td>{{ detection.allocated.first_name }}</td>
              <td>{{ detection.allocated.contact_number }}</td>
              <td>{{ detection.allocated.contact_person }}</td>
              <td>{{ detection.terminal.device_name }}</td>
            </tr>
          </tbody>
        </v-table>
      </v-card-item>
      <v-card-item>
        <v-pagination :length="meta.last_page" :total-visible="10" :model-value="meta.current_page" v-model="filters.page"></v-pagination>
      </v-card-item>
    </v-card>
  </v-container>
</template>
