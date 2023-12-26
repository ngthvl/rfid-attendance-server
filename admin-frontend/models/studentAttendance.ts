import {Ref} from "vue";
import type { JsonResourceType, ResponseMeta } from "~/types/meta";
import { ResponseMetaDefaults } from '~/types/meta';
import {defineStore} from "pinia";
import { DetectionLog } from "~/models/detectionLog";

export interface StudentAttendance {
  student_id: string;
  first_name: string;
  last_name: string;
  contact_person: string;
  contact_number: string;
  contact_address: string;
  id?: string;
  created_at?: string;
  attendance?: DetectionLog[];
}

interface filterType {
  search: string;
  page: number;
  level?: number;
  section?: number;
}

interface StudentAttendanceResponse extends JsonResourceType {
  data: StudentAttendance[]
}


export const useStudentAttendanceStore = defineStore('attendance', () => {
  const attendance: Ref<StudentAttendance[]> = ref([]);

  const params: Ref<{
    // 'filter[search]': string;
    'filter[section_id]'?: number;
    'filter[education_level_id]'?: number;
    'page': number;
  }> = ref({
    // 'filter[search]': '',
    'page': 1,
  })

  const router = () => useRouter();
  const route = () => useRoute();

  const listAttendance = async () => {
    const {data, error} = await useApi('/admin/students/attendance', {
      params: params.value,
      method: "GET"
    });

    if(!error.value){
      const response: StudentAttendanceResponse = data.value as StudentAttendanceResponse;
      attendance.value = response.data
      meta.value = response.meta
    }
  }

  const meta: Ref<ResponseMeta> = ref(ResponseMetaDefaults);

  const filters: Ref<filterType> = ref({
    search: "",
    page: 1
  });

  watch(filters, (newstate: filterType)=>{
    // params.value['filter[search]'] = newstate.search;
    params.value['page'] = newstate.page;
    params.value['filter[section_id]'] = newstate.level;
    params.value['filter[education_level_id]'] = newstate.section;

    router().push({
      path: route().path,
      query: newstate
    });

    listAttendance();
  }, {
    deep: true
  })

  return {
    attendance,
    meta,
    filters,
    listAttendance
  }
})