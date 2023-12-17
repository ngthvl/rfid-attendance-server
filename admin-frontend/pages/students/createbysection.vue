<script setup lang="ts">
import { Student } from "~/models/student";
import jwtMiddleware from "../../middleware/jwtMiddleware";
definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

const defaults = {
  student_id: '',
  first_name: '',
  last_name: '',
  contact_person: '',
  contact_number: '',
  contact_address: '',
};

const data: Ref<Student[]> = ref([])

const addRow = () => {
  const len = data.value.length
  data.value[len] = defaults
  // data.value.append(defaults)
}
</script>

<template>
<v-container>
    <v-card class="shadow mt-4 mx-5">
      <v-card-title>Add Students By Section</v-card-title>
      <v-card-item>
        <v-table :hover="true" style="font-family: monospace">
          <thead>
          <tr>
            <th>LRN</th>
            <th>Family Name</th>
            <th>Given Name</th>
            <th>Contact #</th>
            <th>Contact Person</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(item, key) in data" :key="key">
              <td><v-text-field v-model="data[key].student_id"></v-text-field></td>
              <td><v-text-field v-model="data[key].first_name"></v-text-field></td>
              <td><v-text-field v-model="data[key].last_name"></v-text-field></td>
              <td><v-text-field v-model="data[key].contact_number"></v-text-field></td>
              <td><v-text-field v-model="data[key].contact_person"></v-text-field></td>
              <td class="text-right">

              </td>
            </tr>
            <tr>
              <td colspan="6">
                <v-btn @click="addRow">Add</v-btn>
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-card-item>
    </v-card>

    <students-print-i-d-dialog ref="printDialog"></students-print-i-d-dialog>
  </v-container>
</template>

<style scoped>

</style>