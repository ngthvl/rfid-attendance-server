import {Ref} from "vue";
import type { ResponseMeta } from "~/types/meta";
import { ResponseMetaDefaults } from '~/types/meta';
import {defineStore} from "pinia";
import { Student } from "./student";
import { RfidTerminalType } from "./rfidTerminal";

export interface DetectionLog {
  detected_uid: string;
  detection_dt: string;
  allocated: Student;
  terminal?: RfidTerminalType;
}

export interface TimeInOutLog {
  time_in: string;
  time_out: string;
  detection_dt: string;
}

export interface DailyTimeInOutLog {
  time_in: string;
  time_out: string;
  attendance_dt: string;
}

interface filterType {
  search: string
  page: number
}

export const useDetectionLogStore = defineStore('students', () => {
  const detections: Ref<DetectionLog[]> = ref([]);

  const params: Ref<{
    'filter[search]': string;
    'page': number;
  }> = ref({
    'filter[search]': '',
    'page': 1,
  })

  const router = () => useRouter();
  const route = () => useRoute();

  const listDetections = async () => {
    const {data, error} = await useApi('/admin/rfid-detections', {
      params: filters.value,
      method: "GET"
    });

    if (data?.value?.data) {
      detections.value = data?.value?.data;
    }

    if (data?.value?.meta) {
      meta.value = data?.value?.meta;
    }
  }

  const meta: Ref<ResponseMeta> = ref(ResponseMetaDefaults);

  const filters: Ref<filterType> = ref({
    search: "",
    page: 1
  });

  watch(filters, (newstate: filterType)=>{
    params.value['filter[search]'] = newstate.search;
    params.value['page'] = newstate.page;

    router().push({
      path: route().path,
      query: newstate
    });

    listDetections();
  }, {
    deep: true
  })

  return {
    detections,
    meta,
    filters,
    listDetections
  }
})