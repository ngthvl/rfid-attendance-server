<script setup lang="ts">

import jwtMiddleware from "../../middleware/jwtMiddleware";

import {Student as StudentType, useStudentsStore} from '~/models/student'
import {storeToRefs} from "pinia";
import { usePrintableStore } from "~/models/printable";
import { EducationLevelType, SectionType, useCurriculumStore } from "~/models/curriculum";
import { useNotificationStore } from "~/models/notification";

const studentstore = useStudentsStore();
const printable = usePrintableStore();
const curriculumStore = useCurriculumStore();
const notificationStore = useNotificationStore();

const { educationLevels } = storeToRefs(curriculumStore);

const { students, filters, meta, uploadingCsv } = storeToRefs(studentstore)
const { printableStudents } = storeToRefs(printable)

const config = useRuntimeConfig();
const sampleFile = `${config.public.apiBase}/admin/download-file?file=sample-documents/student-update-form.csv`

const selectedStudents: Ref<StudentType[]> = ref([]);

const selectedEduLevel: Ref<EducationLevelType|undefined> = ref();

const selectedSection: Ref<SectionType|undefined> = ref();

const currentSection: Ref<SectionType[]> = ref([]);

const printDialog = ref();

const csvUploadRef = ref();

const csvFormUploadRef = ref();

onMounted(async ()=>{
  curriculumStore.listEducationLevels();
  studentstore.listStudents();
})

const printId = (student: StudentType) => {
  printDialog.value.show(student)
}

const printMultiple = () => {
  printableStudents.value = selectedStudents.value
  const router = useRouter();

  router.push('/printID');
}

const submitFormAction = async (e) => {
  const formData = new FormData(e.target);
  const {data, error} = await studentstore.updateFromCsv(formData);

  if(!error.value){
    notificationStore.pushNotification('Successfully Saved', '', 'success');
  }
}

const editMultiple = () => {

}

const triggerUploadCsv = () => {
  const conf = confirm('Confirm Uploading file?');
  if(conf){
    csvFormUploadRef.value.click();
  }
}

watch(selectedEduLevel, ()=>{
  if(selectedEduLevel.value){
    filters.value.level = selectedEduLevel.value.id
    if(selectedEduLevel.value?.sections){
      currentSection.value = selectedEduLevel.value.sections
    }
  }
})

watch(selectedSection, ()=>{
  if(selectedSection.value){
    filters.value.section = selectedSection.value.id
  }
})

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
        <v-row>
          <v-col cols="6">
            <v-text-field 
              v-model="filters.search" 
              class="mt-3" 
              prepend-inner-icon="mdi-magnify" 
              variant="outlined" 
              label="Search"
            ></v-text-field>
          </v-col>
          <v-col cols="2">
            <v-select 
              label="Level" 
              class="mt-3" 
              :items="educationLevels" 
              item-title="education_level_name" 
              item-value="id" 
              variant="outlined" 
              v-model="selectedEduLevel" 
              return-object
            ></v-select>
          </v-col>
          <v-col cols="2">
            <v-select 
              label="Section" class="mt-3" 
              :items="currentSection" 
              item-title="section_name" 
              item-value="id" 
              variant="outlined" 
              v-model="selectedSection" 
              return-object
            ></v-select>
          </v-col>
        </v-row>
      </v-card-item>
      <v-card-item>
        <form @submit.prevent="submitFormAction" hidden>
          <input type="file" ref="csvUploadRef" @change="triggerUploadCsv" name="file" hidden>
          <button ref="csvFormUploadRef" type="submit" hidden></button>
        </form>
        <div class="d-flex flex-row justify-space-between">
          <div>
            <v-btn rounded="xl" color="primary" class="mr-3" :disabled="uploadingCsv">
              Add Student
              <v-menu activator="parent">
                <v-list>
                  <v-list-item title="Add One" to="/students/create"></v-list-item>
                  <v-list-item title="Add By Section" to="/students/createbysection"></v-list-item>
                </v-list>
              </v-menu>
            </v-btn>
            <v-btn rounded="xl" color="success" :flat="true" class="mr-3" :loading="uploadingCsv">
              Import/Update from CSV
              <v-menu activator="parent">
                <v-list>
                  <v-list-item title="Download Template" :href="sampleFile"></v-list-item>
                  <v-list-item title="Upload file" @click="csvUploadRef.click()"></v-list-item>
                </v-list>
              </v-menu>
            </v-btn>
            <v-btn-group>
              <v-btn v-if="selectedStudents.length > 0" @click="printMultiple" rounded="xl" color="light" :flat="true" class="mr-3">Print</v-btn>
              <v-btn v-if="selectedStudents.length > 0" @click="editMultiple" rounded="xl" color="light" :flat="true">Edit</v-btn>
            </v-btn-group>
            
          </div>
          <div v-if="selectedStudents.length > 0">
            <span>Selected: {{ selectedStudents.length }}</span>
          </div>
        </div>
        
      </v-card-item>
      <v-card-item>
        <v-table :hover="true" style="font-family: monospace">
          <thead>
          <tr>
            <th>
              <v-checkbox></v-checkbox>
            </th>
            <th>LRN</th>
            <th>Family Name</th>
            <th>Given Name</th>
            <th>Contact #</th>
            <th>Contact Person</th>
            <th>Tagged</th>
            <th>Level</th>
            <th>Section</th>
            <th></th>
          </tr>
          </thead>
          <tbody>
            <tr v-for="(student, key) in students" :key="key">
              <td>
                <v-checkbox v-model="selectedStudents" :value="student"></v-checkbox>
              </td>
              <td><small>{{ student.student_id }}</small></td>
              <td>{{ student.last_name }}</td>
              <td>{{ student.first_name }}</td>
              <td>{{ student.contact_number }}</td>
              <td>{{ student.contact_person }}</td>
              <td>{{ student.rfid_tag ? 'YES' : 'NO' }}</td>
              <td>{{ student.level?.education_level_name ?? 'N/A' }}</td>
              <td>{{ student.section?.section_name ?? 'N/A' }}</td>
              <td class="text-right">
                <v-btn color="primary" rounded="xl">
                  <v-icon icon="mdi-dots-vertical"></v-icon>
                  <v-menu activator="parent">
                    <v-list>
                      <v-list-item title="Print ID" @click="printId(student)"></v-list-item>
                      <v-list-item title="Update Profile" :to="`/students/${student.id}`"></v-list-item>
                      <v-list-item title="Attendance" :to="`/students/${student.id}/attendance`"></v-list-item>
                      <v-list-item title="Detections" :to="`/students/${student.id}/detections`"></v-list-item>
                    </v-list>
                  </v-menu>
                </v-btn>
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-card-item>
      <v-card-item>
        <v-pagination :length="meta.last_page" :total-visible="10" v-model="filters.page"></v-pagination>
      </v-card-item>
    </v-card>

    <students-print-i-d-dialog ref="printDialog"></students-print-i-d-dialog>
  </v-container>
</template>
