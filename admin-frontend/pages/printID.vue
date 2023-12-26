<script setup lang="ts">

import jwtMiddleware from "~/middleware/jwtMiddleware";
import { usePrintableStore } from "~/models/printable";
import {asset} from "~/helpers/storage";
import { useSettingsStore } from "~/models/settings";
import { Student, useStudentsStore } from "~/models/student";

const settingsStore = useSettingsStore();
const studentPrintableStore = usePrintableStore();
const studentStore = useStudentsStore();

const { settings } = storeToRefs(settingsStore);
const { printableStudents } = storeToRefs(studentPrintableStore);

const uploadDialog = ref();

onMounted(()=>{
  nextTick(()=>{
    nextTick(()=>{
    print();
    })
  });
});

const student: Ref<Student | undefined> = ref();

const reloadImage = (url:string) => {
  if(student.value){
    student.value.avatar = url;
    studentStore.update(student.value);
    uploadDialog.value.close();
  }
}

onMounted(async ()=>{
  await settingsStore.listSettings()
})

const changeAvatar = (st: Student) => {
  student.value = st;
  uploadDialog.value.open();
}

definePageMeta({
  middleware: jwtMiddleware,
})

</script>

<template>
  <v-row class="d-flex">
    <v-col v-for="(student, key) in printableStudents" :key="key" cols="3">
      <span class="my-4">
        <div cols="12" class="front-id">
          <p id="name">{{ student?.first_name }} {{ student?.last_name }}</p>
          <p id="student-id">{{ student?.student_id }}</p>
          <div id="image-container" :style="`background-image: url(${student?.avatar});background-size: cover;background-position: center;`">
          </div>
          <img :src="asset('/id_card/front.png')" alt=""  rel="preload">
        </div>
        <div class="d-print-none">
          <v-btn color="success" @click="changeAvatar(student)">Change Image</v-btn>
        </div>
        <div cols="12" class="back-id">
          <p id="school-year">School Year {{ settings.curriculum_settings.school_year }}</p>
          <img :src="asset('/id_card/back.png')" alt="" rel="preload">
        </div>
      </span>
    </v-col>
    
    <ImageUploadDialog ref="uploadDialog" @upload-success="reloadImage"></ImageUploadDialog>

  </v-row>
</template>

<style type="text/css" media="print">
  @page { 
    size: A4 landscape;
  }
</style>

<style scoped lang="scss">
* {
    -webkit-print-color-adjust: exact !important;   /* Chrome, Safari 6 – 15.3, Edge */
    color-adjust: exact !important;                 /* Firefox 48 – 96 */
    print-color-adjust: exact !important;           /* Firefox 97+, Safari 15.4+ */
}

.front-id {
  position: relative;
  max-width: 2.125in!important;
  min-width: 2.125in!important;
  max-height: 3.375in!important;
  min-height: 3.375in!important;
  #name {
    position: absolute;
    z-index: 1;
    top: 62%;
    text-align: center;
    width: 100%;
    font-size: 1em;
    font-weight: bold;
  }
  #student-id{
    position: absolute;
    z-index: 1;
    top: 71.4%;
    text-align: center;
    font-size: 0.9em;
    width: 100%;
    font-weight: bold;
  }
  #image-container{
    position: absolute;
    z-index: 4;
    width: 45%;
    top: 25.2%;
    left: 8%;
    overflow: hidden;
    height: 33.5%;
    img{
      max-width: 100%;
    }
  }
  img{
    max-width: 100%;
  }
}

.back-id {
  max-width: 2.125in!important;
  min-width: 2.125in!important;
  max-height: 3.375in!important;
  min-height: 3.375in!important;
  position: relative;
  transform: rotate(180deg);
  margin-bottom: 0.3904in!important;
  #school-year {
    position: absolute;
    z-index: 1;
    top: 21.4%;
    text-align: center;
    width: 100%;
    font-weight: bold;
    font-size: 0.7em;
  }
  img{
    max-width: 100%;
  }
}
</style>