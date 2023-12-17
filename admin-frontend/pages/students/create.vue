<script setup lang="ts">

import jwtMiddleware from "../../middleware/jwtMiddleware";
import {Student, useStudentsStore} from "~/models/student";

const studentData: Ref<Student> = ref({
  student_id: '',
  first_name: '',
  last_name: '',
  contact_person: '',
  contact_number: '',
  contact_address: '',
});

const studentStore = useStudentsStore();

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

const saveStudent = async () => {
  const tgt = Object.assign({}, studentData.value)
  studentStore.save(tgt)
}

</script>

<template>
  <v-container>
    <v-card class="shadow mt-4 mx-5">
      <v-card-title>Create New Student</v-card-title>
      <v-card-item>
        <h4>Student Info</h4>
        <hr>
        <v-row class="mt-3">
          <v-col cols="4">
            <v-text-field type="text" label="First Name" v-model="studentData.first_name" :error-messages="clientErrors.errors?.first_name"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-text-field type="text" label="Last Name" v-model="studentData.last_name" :error-messages="clientErrors.errors?.last_name"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-text-field type="text" label="LRN" v-model="studentData.student_id" :error-messages="clientErrors.errors?.student_id"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-select label="Level"></v-select>
          </v-col>
          <v-col cols="4">
            <v-select label="Section"></v-select>
          </v-col>
        </v-row>
      </v-card-item>
      <v-card-item>
        <h4>Contact Info</h4>
        <hr>
        <v-row class="mt-3">
          <v-col cols="4">
            <v-text-field type="text" label="Contact Name" v-model="studentData.contact_person" :error-messages="clientErrors.errors?.contact_person"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-text-field type="text" label="Contact Number" v-model="studentData.contact_number" :error-messages="clientErrors.errors?.contact_number"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-text-field type="text" label="Contact Address" v-model="studentData.contact_address" :error-messages="clientErrors.errors?.contact_address"></v-text-field>
          </v-col>
        </v-row>
      </v-card-item>
      <v-card-item class="mb-2">
        <v-btn rounded="xl" class="mr-5">Back</v-btn>
        <v-btn rounded="xl" color="success" @click="saveStudent">Save</v-btn>
      </v-card-item>
    </v-card>
  </v-container>
</template>
