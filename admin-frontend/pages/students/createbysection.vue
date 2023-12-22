<script setup lang="ts">
import { SaveMultipleType, Student, useStudentsStore } from "~/models/student";
import jwtMiddleware from "../../middleware/jwtMiddleware";
import { EducationLevelType, SectionType, useCurriculumStore } from "~/models/curriculum";

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

const curriculumStore = useCurriculumStore();
const studentStore = useStudentsStore();

const { educationLevels } = storeToRefs(curriculumStore);

const defaults = {
  student_id: '',
  first_name: '',
  last_name: '',
  contact_person: '',
  contact_number: '',
  contact_address: '',
};

const selectedEduLevel: Ref<EducationLevelType|undefined> = ref();

const selectedSection: Ref<SectionType|undefined> = ref();

const currentSection: Ref<SectionType[]> = ref([]);

const data: Ref<Student[]> = ref([])

const addRow = () => {
  data.value.push(defaults)
}

const saveStudents = () => {
  const requestBody: SaveMultipleType = {
    data: data.value,
    section: selectedSection?.value,
    level: selectedEduLevel?.value
  }

  studentStore.saveMultiple(requestBody);
}

curriculumStore.listEducationLevels()

watch(selectedEduLevel, ()=>{
  if(selectedEduLevel.value){
    if(selectedEduLevel.value?.sections){
      currentSection.value = selectedEduLevel.value.sections
    }
  }
})
</script>

<template>
<v-container>
    <v-card class="shadow mt-4 mx-5">
      <form @submit.prevent="saveStudents">
        <v-card-title>Add Students By Section</v-card-title>
        <v-card-item>
          <v-row>
            <v-col cols="2">
              <v-select label="Level" :items="educationLevels" item-title="education_level_name" v-model="selectedEduLevel" return-object></v-select>
            </v-col>
            <v-col cols="2">
              <v-select label="Section" :items="currentSection" item-title="section_name" v-model="selectedSection" return-object></v-select>
            </v-col>
          </v-row>
        </v-card-item>
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
                <td><v-text-field v-model="item.student_id"></v-text-field></td>
                <td><v-text-field v-model="item.first_name"></v-text-field></td>
                <td><v-text-field v-model="item.last_name"></v-text-field></td>
                <td><v-text-field v-model="item.contact_number"></v-text-field></td>
                <td><v-text-field v-model="item.contact_person"></v-text-field></td>
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

          <v-btn color="primary" type="submit">Save</v-btn>
          
        </v-card-item>
      </form>
    </v-card>

    <students-print-i-d-dialog ref="printDialog"></students-print-i-d-dialog>
  </v-container>
</template>

<style scoped>

</style>