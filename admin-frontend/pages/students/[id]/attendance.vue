<script setup lang="ts">
import dayjs, { Dayjs } from 'dayjs'
import localeData from 'dayjs/plugin/localeData';

import jwtMiddleware from "~/middleware/jwtMiddleware";
import { Student, studentDefaults, useStudentsStore } from "~/models/student";
import { useCurriculumStore } from "~/models/curriculum";
import { dayCollectionType } from '~/types/dayCollectionType';
import { attendanceOnDay, attendanceOnDay2, getTimeIn, getTimeOut } from '~/composables/useAttendanceOnDay';
import { TimeInOutLog } from '~/models/detectionLog';
import { useStudentDailyAttendanceStore } from '~/models/dailyAttendance';

dayjs.extend(localeData)

const studentData: Ref<Student> = ref(studentDefaults);
const studentAttendance: Ref<TimeInOutLog[]|undefined> = ref();

const curriulumStore = useCurriculumStore();
const studentStore = useStudentsStore();
const studentAttendanceStore = useStudentDailyAttendanceStore();

const route = useRoute();

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
});

const weekdays = computed(()=>{
  const currDay = dayjs()
  const monthLen = currDay.daysInMonth()
  let days: dayCollectionType[] = [];
  for(let d = 1; d<=monthLen; d++){
    const ls = currDay.set('date', d)
    const wkd = ls.format('d');
    if(wkd !== '6' && wkd!=='0'){
      days.push({
        date: ls.format('dddd | DD-MM-YYYY'),
        day: ls.format('ddd'),
        rawDate: ls
      })
    }
  }
  return days;
})


onMounted(async () => {
  const studentFetch = await studentStore.getStudentInfo(route.params['id'] as string);
  

  if(studentFetch){
    studentData.value = studentFetch;
    studentAttendance.value = await studentAttendanceStore.listDailyAttendance(studentFetch);
  }
});

</script>

<template>
  <v-container>
    <v-card class="shadow mt-4 mx-5">
      <v-card-title>Student Attendance</v-card-title>

      <v-card-item>
        <v-table>
          <thead>
            <tr>
              <th colspan="4"><h3>Student: {{ studentData.last_name }}, {{ studentData.first_name }}</h3></th>
            </tr>
            <tr>
              <th>Date</th>
              <th>Attendance</th>
              <th>Time In</th>
              <th>Time Out</th>
            </tr>
          </thead>
          <tbody v-if="studentAttendance">
            <tr v-for="(w, key) in weekdays" :key="key">
              <th>{{ w.date }}</th>
              <td>{{ attendanceOnDay2(w.rawDate, studentAttendance) }}</td>
              <td>{{ getTimeIn(w.rawDate, studentAttendance) }}</td>
              <td>{{ getTimeOut(w.rawDate, studentAttendance) }}</td>
            </tr>
          </tbody>
        </v-table>
      </v-card-item>
      
      <v-card-item class="mb-2">
        <v-btn rounded="xl" class="mr-5" to="/students">Back</v-btn>
      </v-card-item>
    </v-card>
  </v-container>
</template>
