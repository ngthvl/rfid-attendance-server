<script setup lang="ts">

import jwtMiddleware from "../../middleware/jwtMiddleware";

import {list as listAdmins, admins} from '~/models/admin'
import {SymbolKind} from "vscode-languageserver-types";
import Array = SymbolKind.Array;

listAdmins();

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

</script>

<template>
  <v-container>
    <v-card class="shadown mt-4 mx-5">
      <v-card-item>
        <v-text-field class="mt-3" prepend-inner-icon="mdi-magnify" variant="outlined" label="Search"></v-text-field>
      </v-card-item>
      <v-card-item>
        <v-table :hover="true">
          <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>-</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="(admin, key) in admins" :key="key">
            <td>{{ admin.name }}</td>
            <td>{{ admin.email }}</td>
            <td></td>
          </tr>
          </tbody>
        </v-table>
      </v-card-item>
      <v-card-item>
        <v-pagination :length="10" total-visible="10"></v-pagination>
      </v-card-item>
    </v-card>
  </v-container>
</template>
