import {Ref} from "vue";
import type { JsonResourceType, ResponseMeta } from "~/types/meta";
import { ResponseMetaDefaults } from '~/types/meta';
import {defineStore} from "pinia";
import { DetectionLog } from "~/models/detectionLog";
import { LocationQueryRaw } from "vue-router";

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
  search?: string;
  page: number;
  per_page?: number;
  level?: number;
  section?: number;
  from_date?: string;
  to_date?: string;
}

interface StudentAttendanceResponse extends JsonResourceType {
  data: StudentAttendance[]
}


export const useStudentAttendanceStore = defineStore('attendance', () => {
  const attendance: Ref<StudentAttendance[]> = ref([]);

  const params: Ref<{
    'filter[search]'?: string;
    'filter[section_id]'?: number;
    'filter[education_level_id]'?: number;
    'filter[from_date]'?: string;
    'filter[to_date]'?: string;
    'per_page'?: number;
    'page': number;
  }> = ref({
    'page': 1,
  })

  const router = () => useRouter();
  const route = () => useRoute();

  const listAttendance = async () => {
    const {data, error} = await useApi('/admin/students/attendance', {
      params: Object.assign({}, params.value),
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
    page: 1,
    per_page: 15,
  });

  const setPerPage = (limit: number) => {
    filters.value.per_page = limit;
    filters.value.page = 1;
  }

  watch(filters, (old, newRec)=>{
    params.value['filter[search]'] = filters.value.search;
    params.value['page'] = filters.value.page;
    params.value['per_page'] = filters.value.per_page;
    params.value['filter[section_id]'] = filters.value.level;
    params.value['filter[education_level_id]'] = filters.value.section;
    params.value['filter[from_date]'] = filters.value.from_date;
    params.value['filter[to_date]'] = filters.value.to_date;

    router().push({
      path: route().path,
      query: filters.value
    });

    listAttendance();
  }, {
    deep: true
  })

  return {
    attendance,
    meta,
    filters,
    listAttendance,
    setPerPage
  }
})