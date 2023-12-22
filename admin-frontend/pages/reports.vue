<script setup lang="ts">
import dayjs, { Dayjs } from 'dayjs'
import localeData from 'dayjs/plugin/localeData';

import jwtMiddleware from "~/middleware/jwtMiddleware";

import {useStudentAttendanceStore} from '~/models/studentAttendance';

import { DetectionLog } from "~/models/detectionLog";
import { EducationLevelType, SectionType, useCurriculumStore } from '~/models/curriculum';

dayjs.extend(localeData)

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

interface dayCollectionType {
  date: string,
  day: string,
  rawDate: Dayjs
}

const weekdays = computed(()=>{
  const currDay = dayjs()
  const monthLen = currDay.daysInMonth()
  let days: dayCollectionType[] = [];
  for(let d = 1; d<=monthLen; d++){
    const ls = currDay.set('date', d)
    const wkd = ls.format('d');
    if(wkd !== '6' && wkd!=='0'){
      days.push({
        date: ls.format('DD-MM-YYYY'),
        day: ls.format('ddd'),
        rawDate: ls
      })
    }
  }
  return days;
})

const attendanceOnDay = (date_time: Dayjs, detections?:DetectionLog[]) => {
  const currDay = date_time;
  let attendance = 'A';
  if(detections){
    detections.forEach((v, i)=>{
      const ddate = dayjs(v.detection_dt)
      if (ddate.isSame(currDay, 'date')){
        attendance = 'P';
      }
    })
  }
  return attendance
}

const attendanceStore = useStudentAttendanceStore();
const curriculumStore = useCurriculumStore();

const { educationLevels } = storeToRefs(curriculumStore);
const { attendance } = storeToRefs(attendanceStore);

const selectedEduLevel: Ref<EducationLevelType|undefined> = ref();

const selectedSection: Ref<SectionType|undefined> = ref();

const currentSection: Ref<SectionType[]> = ref([]);

watch(selectedEduLevel, ()=>{
  if(selectedEduLevel.value){
    if(selectedEduLevel.value?.sections){
      currentSection.value = selectedEduLevel.value.sections
    }
  }
})

onMounted(()=>{
  attendanceStore.listAttendance()
})

</script>

<template>
  <v-container>
    <v-card class="shadow mt-4 mx-5">
      <v-card-title>RFID Terminals</v-card-title>

      <v-card-item>
        <v-row>
          <v-col cols="2">
            <v-select label="Level" :items="educationLevels" item-title="education_level_name" v-model="selectedEduLevel" return-object></v-select>
          </v-col>
          <v-col cols="2">
            <v-select label="Section" :items="currentSection" item-title="section_name" v-model="selectedSection" return-object></v-select>
          </v-col>
          <v-col cols="4">
            <v-btn color="success">Print</v-btn>
          </v-col>
        </v-row>
      </v-card-item>

      <v-card-item>
        <v-table density="compact" :hover="true">
          <thead>
            <tr>
              <th>Name</th>
              <th v-for="(w, key) in weekdays" :key="key" class="rotated-text"><div>{{ w.date }}</div></th>
            </tr>
          </thead>
          <tbody class="mb-4">
            <tr v-for="(a, key) in attendance" :key="key">
              <td>{{ a.last_name }}, {{ a.first_name }}</td>
              <td v-for="(w, key2) in weekdays" :key="key2">
                {{ attendanceOnDay(w.rawDate, a.attendance) }}
              </td>
            </tr>
          </tbody>
        </v-table>
      </v-card-item>
    </v-card>
  </v-container>
</template>

<style scoped>
th.rotated-text > div {
    transform:
      /* translate(2px, 0px) */
      rotate(300deg);
    font-size: 10px;
    font-weight: bolder;
    color: black;
}

th.rotated-text {
  white-space: nowrap;
  padding: 0 !important;
  padding-bottom: 30px !important;
  padding-top: 30px !important;
}
</style>
