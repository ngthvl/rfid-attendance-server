<script setup lang="ts">

import jwtMiddleware from "~/middleware/jwtMiddleware";
import {useRfidTerminalStore} from "~/models/rfidTerminal";
import {storeToRefs} from "pinia";

const terminalStore = useRfidTerminalStore();

const { terminals } = storeToRefs(terminalStore);

terminalStore.listTerminals();

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

</script>

<template>
  <v-container>
    <v-card class="shadow mt-4 mx-5">
      <v-card-title>RFID Terminals</v-card-title>

      <v-card-item>
        <v-row>
          <v-col cols="4" v-for="terminal in terminals">
            <v-card class="shadow">
              <v-card-item>
                <v-icon icon="mdi-access-point"></v-icon>
                <h5>Device UID: {{ terminal.id }}</h5>
                <h6 class="mt-3">IP: {{ terminal.ip_address }}</h6>
              </v-card-item>
              <v-card-item class="my-3">
                <v-btn color="success" size="x-small" text="Re-Authenticate" @click="terminalStore.authorizeTerminal(terminal)"></v-btn>
                <v-btn color="info" size="x-small" text="Detections" class="ml-2"></v-btn>
              </v-card-item>
            </v-card>
          </v-col>
        </v-row>
      </v-card-item>
    </v-card>
  </v-container>
</template>
