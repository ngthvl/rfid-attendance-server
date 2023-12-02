<script setup lang="ts">

import jwtMiddleware from "../../middleware/jwtMiddleware";

import {Student as StudentType, useStudentsStore} from '~/models/student'
import {storeToRefs} from "pinia";

const studentstore = useStudentsStore();

const { students, filters, meta } = storeToRefs(studentstore)

studentstore.listStudents();

const config = useRuntimeConfig();
const sampleFile = `${config.public.apiBase}/admin/download-file?file=sample-documents/student-update-form.csv`

const printDialog = ref();

const printId = (student: StudentType) => {
  printDialog.value.show(student)
}

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

</script>

<template>
  <v-container>
    <v-card class="shadow mt-4 mx-5">
      <v-card-title>Students</v-card-title>
      <v-card-item>
        <v-text-field v-model="filters.search" class="mt-3" prepend-inner-icon="mdi-magnify" variant="outlined" label="Search"></v-text-field>
      </v-card-item>
      <v-card-item>
        <v-btn rounded="xl" color="primary" class="mr-3">
          Add Student
          <v-menu activator="parent">
            <v-list>
              <v-list-item title="Add One" to="/students/create"></v-list-item>
              <v-list-item title="Add By Section" to="/students/createbysection"></v-list-item>
              <v-list-item title="Add By Level" to="/students/createbylevel"></v-list-item>
            </v-list>
          </v-menu>
        </v-btn>
        <v-btn rounded="xl" color="success" :flat="true">
          Import/Update from CSV
          <v-menu activator="parent">
            <v-list>
              <v-list-item title="Download Template" :href="sampleFile"></v-list-item>
              <v-list-item title="Upload file"></v-list-item>
            </v-list>
          </v-menu>
        </v-btn>
      </v-card-item>
      <v-card-item>
        <v-table :hover="true" style="font-family: monospace">
          <thead>
          <tr>
            <th>Student ID</th>
            <th>Family Name</th>
            <th>Given Name</th>
            <th>Contact #</th>
            <th>Contact Person</th>
            <th>Tagged</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(student, key) in students">
              <td><small>{{ student.student_id }}</small></td>
              <td>{{ student.last_name }}</td>
              <td>{{ student.first_name }}</td>
              <td>{{ student.contact_number }}</td>
              <td>{{ student.contact_person }}</td>
              <td>{{ student.rfid_tag ? 'YES' : 'NO' }}</td>
              <td class="text-right">
                <v-btn color="primary" rounded="xl">
                  <v-icon icon="mdi-dots-vertical"></v-icon>
                  <v-menu activator="parent">
                    <v-list>
                      <v-list-item title="Print ID" @click="printId(student)"></v-list-item>
                      <v-list-item title="Update Profile"></v-list-item>
                      <v-list-item title="Attendance"></v-list-item>
                      <v-list-item title="Detections"></v-list-item>
                    </v-list>
                  </v-menu>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-card-item>
      <v-card-item>
        <v-pagination :length="meta.last_page" :total-visible="10" :model-value="meta.current_page" v-model="filters.page"></v-pagination>
      </v-card-item>
    </v-card>

    <students-print-i-d-dialog ref="printDialog"></students-print-i-d-dialog>
  </v-container>
</template>
