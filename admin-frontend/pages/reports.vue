<script setup lang="ts">
import dayjs, { Dayjs } from 'dayjs'
import localeData from 'dayjs/plugin/localeData';

import jwtMiddleware from "~/middleware/jwtMiddleware";

import { useStudentAttendanceStore } from '~/models/studentAttendance';

import { DetectionLog } from "~/models/detectionLog";
import { EducationLevelType, SectionType, useCurriculumStore } from '~/models/curriculum';
import { dayCollectionType } from '~/types/dayCollectionType';
import { attendanceOnDay } from '~/composables/useAttendanceOnDay';
import { transformDate } from '~/helpers/util';

dayjs.extend(localeData)

const dateRange: Ref<{
  from: Dayjs,
  to: Dayjs
}> = ref({
  from: dayjs().startOf('month'),
  to: dayjs().endOf('month'),
});
const attendanceStore = useStudentAttendanceStore();
const curriculumStore = useCurriculumStore();

const { educationLevels } = storeToRefs(curriculumStore);
const { attendance, filters } = storeToRefs(attendanceStore);

const selectedEduLevel: Ref<EducationLevelType|undefined> = ref();

const selectedSection: Ref<SectionType|undefined> = ref();

const currentSection: Ref<SectionType[]> = ref([]);

const printable = ref();

const datePickerFrom = ref();
const datePickerTo = ref();

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

const weekdays = computed(()=>{
  let currDay = dayjs(dateRange.value.from.toISOString());
  const daysLen = dateRange.value.to.diff(dateRange.value.from, 'day');
  let days: dayCollectionType[] = [];
  for(let d = 1; d<=daysLen; d++){
    const wkd = currDay.format('d');
    if(wkd !== '6' && wkd!=='0'){
      days.push({
        date: currDay.format('DD-MM-YYYY'),
        day: currDay.format('ddd'),
        rawDate: dayjs(currDay.toISOString())
      });
    }
    currDay = currDay.add(1, 'day');
  }
  return days;
})

const handlePrint = () => {
  const mywindow = window.open('', 'PRINT', 'height=400,width=600');
  console.log(printable.value)
  mywindow?.document.write(printable.value.outerHtml);
}



watch(selectedEduLevel, () => {
  if(selectedEduLevel.value){
    filters.value.level = selectedEduLevel.value.id;
    if(selectedEduLevel.value?.sections){
      currentSection.value = selectedEduLevel.value.sections;
    }
  }
});

watch(selectedSection, () => {
  if(selectedSection.value) filters.value.section = selectedSection.value.id;
});

watch(datePickerFrom, () => {
  dateRange.value.from = dayjs(datePickerFrom.value);
  filters.value.from_date = dateRange.value.from.toISOString();
});
watch(datePickerTo, () => {
  dateRange.value.to = dayjs(datePickerTo.value)
  filters.value.to_date = dateRange.value.to.toISOString();
});
onMounted(()=>{
  attendanceStore.listAttendance();
  curriculumStore.listEducationLevels();
});

</script>

<template>
  <v-container>
    <v-card class="shadow mt-4 mx-5" ref="printable">
      <v-card-title>Student Attendance</v-card-title>

      <v-card-item>
        <v-row>
          <v-col cols="2">
            <v-select label="Level" :items="educationLevels" item-title="education_level_name" variant="outlined" v-model="selectedEduLevel" return-object></v-select>
          </v-col>
          <v-col cols="2">
            <v-select label="Section" :items="currentSection" item-title="section_name" variant="outlined" v-model="selectedSection" return-object></v-select>
          </v-col>
          <v-col cols="4">
            <v-btn>
              From: {{ transformDate(dateRange.from) }}
              <v-dialog activator="parent" width="300">
                <v-date-picker v-model="datePickerFrom" :max="datePickerTo"></v-date-picker>
              </v-dialog>
            </v-btn>
            <v-btn>
              To: {{ transformDate(dateRange.to) }}
              <v-dialog activator="parent" width="300">
                <v-date-picker v-model="datePickerTo" :min="datePickerFrom"></v-date-picker>
              </v-dialog>
            </v-btn>
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
    max-width: 50px!important;
}

th.rotated-text {
  white-space: nowrap;
  padding: 0 !important;
  padding-bottom: 30px !important;
  padding-top: 30px !important;
}
</style>
