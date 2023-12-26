<script setup lang="ts">

import jwtMiddleware from "~/middleware/jwtMiddleware";
import { Student, studentDefaults, useStudentsStore } from "~/models/student";
import { EducationLevelType, SectionType, useCurriculumStore } from "~/models/curriculum";
import { useNotificationStore } from "~/models/notification";

const studentData: Ref<Student> = ref(studentDefaults);

const curriculumStore = useCurriculumStore();
const studentStore = useStudentsStore();
const notificationStore = useNotificationStore();

const { educationLevels } = storeToRefs(curriculumStore);

const selectedEduLevel: Ref<EducationLevelType|undefined> = ref();

const selectedSection: Ref<SectionType|undefined> = ref();

const currentSection: Ref<SectionType[]> = ref([]);

const { errors } = storeToRefs(studentStore);

const route = useRoute();

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
});

const saveStudent = async () => {
  const tgt = Object.assign({}, studentData.value);
  const {data, error} = await studentStore.update(tgt);

  if(!error.value){
    notificationStore.pushNotification("Successfully Saved")
  }
};

watch(selectedEduLevel, () => {
  if(selectedEduLevel.value?.sections){
    currentSection.value = selectedEduLevel.value.sections;
    studentData.value.level = selectedEduLevel.value;
  }
});

watch(selectedSection, ()=>{
  if(selectedSection.value){
    studentData.value.section = selectedSection.value
  }
});

onMounted(async () => {
  curriculumStore.listEducationLevels();

  const studentFetch = await studentStore.getStudentInfo(route.params['id'] as string)

  if(studentFetch){
    studentData.value = studentFetch;
  }
});

</script>

<template>
  <v-container>
    <v-card class="shadow mt-4 mx-5">
      <v-card-title>Edit Student</v-card-title>
      <v-card-item>
        <h4>Student Info</h4>
        <hr>
        <v-row class="mt-3">
          <v-col cols="4">
            <v-text-field type="text" label="First Name" v-model="studentData.first_name" :error-messages="errors?.errors?.first_name"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-text-field type="text" label="Last Name" v-model="studentData.last_name" :error-messages="errors?.errors?.last_name"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-text-field type="text" label="LRN" v-model="studentData.student_id" :error-messages="errors?.errors?.student_id"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-select label="Level" :items="educationLevels" item-title="education_level_name" v-model="selectedEduLevel" return-object></v-select>
          </v-col>
          <v-col cols="4">
            <v-select label="Section" :items="currentSection" item-title="section_name" v-model="selectedSection" return-object></v-select>
          </v-col>
        </v-row>
      </v-card-item>
      <v-card-item>
        <h4>Contact Info</h4>
        <hr>
        <v-row class="mt-3">
          <v-col cols="4">
            <v-text-field type="text" label="Contact Name" v-model="studentData.contact_person" :error-messages="errors?.errors?.contact_person"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-text-field type="text" label="Contact Number" v-model="studentData.contact_number" :error-messages="errors?.errors?.contact_number"></v-text-field>
          </v-col>
          <v-col cols="4">
            <v-text-field type="text" label="Contact Address" v-model="studentData.contact_address" :error-messages="errors?.errors?.contact_address"></v-text-field>
          </v-col>
        </v-row>
      </v-card-item>
      <v-card-item class="mb-2">
        <v-btn rounded="xl" class="mr-5" to="/students">Back</v-btn>
        <v-btn rounded="xl" color="success" @click="saveStudent">Save</v-btn>
      </v-card-item>
    </v-card>
  </v-container>
</template>
