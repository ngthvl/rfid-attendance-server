<script setup lang="ts">

import {asset} from "~/helpers/storage";
import { useSettingsStore } from "~/models/settings";
import {Student, useStudentsStore} from "~/models/student";

interface hardwareParams {
  has_tag: boolean
  data?: string | null
  hardware_present?: boolean
  device_connected?: boolean
  server_exists?: boolean
}

const errors: Ref<{
  message: string
} | undefined> = ref()

const settingsStore = useSettingsStore();
const {settings} = storeToRefs(settingsStore);

const student: Ref<Student | null> = ref(null)

const config = useRuntimeConfig();

const studentStore = useStudentsStore();

const hardwareParameters: Ref<hardwareParams> = ref({
  has_tag: false,
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

const interval = ref();

const tagAssigned: Ref<string | undefined> = ref();

watch(idPrintDialog, async (nw, old)=>{
  if(nw && !student.value?.rfid_tag){
    hardwareParameters.value.server_exists = false;
    fetchHinfo();
    interval.value = setInterval(fetchHinfo, 500);
  }else{
    clearInterval(interval.value);
  }
})

const fetchHinfo = async () => {
  if (idPrintDialog.value && !student.rfid_tag) {
    let t = null;

    await (async () => {
      t = setTimeout(()=>{
        hardwareParameters.value.server_exists = false
      }, 5000)
    })()

    const {data, error} = await useFetch('http://writer.student-attendance.internal:15000', {method: "GET"});

    if (!error.value) {
      if(t !== null) {
        clearTimeout(t)
      }
      hardwareParameters.value.server_exists = true
      const content = data.value as hardwareParams

      hardwareParameters.value.has_tag = content.has_tag
      hardwareParameters.value.data = content.data
      hardwareParameters.value.device_connected = content.device_connected
    }
  }
}

const assignCurrentTag = async () => {
  errors.value = undefined;
  const {data, error} = await useApi('/admin/rfid-tags/allocate', {
    method: "POST",
    body:{
      rfid_data: hardwareParameters.value.data,
      allocation_id: student.value?.id,
      allocation_type: 'student'
    }
  })

  if(!error.value){
    tagAssigned.value = hardwareParameters.value.data as string | undefined;

    studentStore.listStudents();

    clearInterval(interval.value)
  }else{
    errors.value = error.value.data
  }
}

const show = (st: Student) => {
  student.value = st;
  idPrintDialog.value = true;
  tagAssigned.value = undefined;

  if(interval.value){
    clearInterval(interval.value)
  }
}

const hide = () => {
  idPrintDialog.value = false;
  clearInterval(interval.value)
}

defineExpose({
  show,
  hide
})
</script>

<template>
  <v-dialog v-model="idPrintDialog" width="700">
    <v-card>
      <v-card-title class="d-flex justify-space-between">
        <span>Print Student ID</span>
        <span>
          <v-chip color="primary" v-if="student?.rfid_tag">Student has existing tag.</v-chip>
          <v-chip v-else :color="hardwareStatusColor">{{ hardwareStatusText }}</v-chip>
        </span>
      </v-card-title>
      <v-card-item>
        <v-alert color="warning" v-if="hardwareParameters.device_connected === false && hardwareParameters.server_exists">Warning: Please check RFID Writer Connection or try reconnecting the device.</v-alert>
        <v-alert color="warning" v-if="hardwareParameters.server_exists === false">
          Please Start Companion app by <a href="codelines-rfid://companion-app.open">clicking this link</a>, or download by <a :href="asset('/binary/rfid-writer.exe')">clicking this link.</a>
        </v-alert>
        <v-alert color="warning" v-if="errors">{{ errors.message }}</v-alert>
        <v-row class="my-4">
          <v-col class="front-id">
            <p id="name">{{ student?.first_name }} {{ student?.last_name }}</p>
            <p id="student-id">{{ student?.student_id }}</p>
            <div id="image-container">
              <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
            </div>
            <v-img :src="asset('/id_card/front.png')" alt=""></v-img>
          </v-col>
          <v-col class="back-id">
            <p id="school-year">School Year {{ settings.curriculum_settings.school_year }}</p>
            <v-img :src="asset('/id_card/back.png')" alt=""></v-img>
          </v-col>
        </v-row>
      </v-card-item>
      <v-card-item class="mb-4">
        <v-btn
          v-if="hardwareParameters.has_tag || !!tagAssigned"
          :disabled="!!tagAssigned"
          color="success"
          @click="assignCurrentTag"
          class="mr-3"
          :append-icon="!!tagAssigned ? 'mdi-check-all' : ''"
        >{{ !!tagAssigned ? 'Tag Assigned' : 'Assign Tag' }}</v-btn>
        <v-btn color="primary" class="mr-3">Print ID</v-btn>
        <v-btn color="error" v-if="student?.rfid_tag">Clear Tag Assignment</v-btn>
      </v-card-item>
    </v-card>
  </v-dialog>
</template>

<style scoped lang="scss">
.front-id {
  position: relative;

  #name {
    position: absolute;
    z-index: 1;
    top: 65%;
    text-align: center;
    width: 100%;
    font-size: 1.3em;
    padding-right: 24px;
    font-weight: bold;
  }
  #student-id{
    position: absolute;
    z-index: 1;
    top: 73%;
    text-align: center;
    font-size: 1em;
    padding-right: 24px;
    width: 100%;
    font-weight: bold;
  }
  #image-container{
    position: absolute;
    z-index: 4;
    width: 42%;
    top: 27.2%;
    left: 11%;
    overflow: hidden;
    height: 32.5%;
    img{
      max-width: 100%;
    }
  }
}

.back-id {
  position: relative;
  #school-year {
    position: absolute;
    z-index: 1;
    top: 24%;
    text-align: center;
    width: 100%;
    font-weight: bold;
    padding-right: 24px;
  }
}
</style>
