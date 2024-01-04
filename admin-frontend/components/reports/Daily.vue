<script setup lang="ts">
import { transformDate } from '~/helpers/util';
import localeData from 'dayjs/plugin/localeData';
import dayjs, { Dayjs } from 'dayjs';
import { EducationLevelType, SectionType, useCurriculumStore } from '~/models/curriculum';

dayjs.extend(localeData);

const curriculumStore = useCurriculumStore();

// const attendanceStore = useDailyAtten

const { educationLevels } = storeToRefs(curriculumStore);

const selectedEduLevel: Ref<EducationLevelType|undefined> = ref();

const selectedSection: Ref<SectionType|undefined> = ref();

const currentSection: Ref<SectionType[]> = ref([]);

const datePickerDate = ref();

const dateRange: Ref<{
  from: Dayjs,
  to: Dayjs
}> = ref({
  from: dayjs().startOf('month'),
  to: dayjs().endOf('month'),
});

</script>

<template>
  <v-card-item>
    <v-row>
      <v-col cols="2">
        <v-select 
          label="Level" 
          :items="educationLevels" 
          item-title="education_level_name" 
          variant="outlined" 
          v-model="selectedEduLevel" 
          return-object
          density="compact"
        ></v-select>
      </v-col>
      <v-col cols="2">
        <v-select 
          label="Section" 
          :items="currentSection" 
          item-title="section_name" 
          variant="outlined" 
          v-model="selectedSection" 
          return-object
          density="compact"
        ></v-select>
      </v-col>
      <v-col cols="4">
        <v-btn>
          Date: {{ transformDate(dateRange.from) }}
          <v-dialog activator="parent" width="300">
            <v-date-picker v-model="datePickerDate"></v-date-picker>
          </v-dialog>
        </v-btn>
      </v-col>
      <v-col cols="4">
        <v-text-field variant="outlined" label="Search" density="compact"></v-text-field>
      </v-col>
    </v-row>

    <v-table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Time In</th>
          <th>Time Out</th>
          <th>Remarks</th>
        </tr>
      </thead>
    </v-table>
  </v-card-item>
</template>