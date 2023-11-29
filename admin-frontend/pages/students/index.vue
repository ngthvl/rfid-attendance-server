<script setup lang="ts">

import jwtMiddleware from "../../middleware/jwtMiddleware";

import { list as listStudents, students, filters, meta } from '~/models/student'

listStudents();

interface hardwareParams {
  has_tag: boolean
  data: boolean
  hardware_present?: boolean
}

const config = useRuntimeConfig();
const sampleFile = `${config.public.apiBase}/admin/download-file?file=sample-documents/student-update-form.csv`

const hardwareParameters: Ref<hardwareParams> = ref({
  has_tag: false,
  data: false,
});

const hardwareStatusColor = computed(()=>{
  if (hardwareParameters.value.has_tag) {
    return 'success'
  } else {
    return 'error'
  }
})

const hardwareStatusText = computed(()=>{
  if (hardwareParameters.value.has_tag) {
    return 'Tag Detected'
  } else {
    return 'No Tag Found'
  }
})

const idPrintDialog: Ref<boolean> = ref(false);


const printId = async () => {
  idPrintDialog.value = true

  await fetchHinfo();

  setInterval(fetchHinfo, 500);
}

const fetchHinfo = async () => {
  const hInfo = await useFetch('http://writer.student-attendance.internal:15000', {method: "GET"});
  const data = hInfo?.data?.value as hardwareParams

  hardwareParameters.value.has_tag = data.has_tag
  hardwareParameters.value.data = data.data
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
              <td class="text-right">
                <v-btn color="primary" rounded="xl">
                  <v-icon icon="mdi-dots-vertical"></v-icon>
                  <v-menu activator="parent">
                    <v-list>
                      <v-list-item title="Print ID" @click="printId"></v-list-item>
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

    <v-dialog v-model="idPrintDialog" width="700">
      <v-card>
        <v-card-title>
          <span>Print Student ID</span>
          <span>
            <v-btn :color="hardwareStatusColor" disabled>{{ hardwareStatusText }}</v-btn>
          </span>
        </v-card-title>
      </v-card>
    </v-dialog>
  </v-container>
</template>
