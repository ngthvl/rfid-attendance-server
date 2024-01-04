import { LocationQueryRaw } from "vue-router"
import { JsonResourceType, ResponseMeta, ResponseMetaDefaults } from "~/types/meta"

export interface DailyAttendanceReport {
  id: string
  student_id: string
  first_name: string
  last_name: string
  tags: number
  date_detected?: string
  time_in?: string
  time_out?: string
}

interface reportResponse extends JsonResourceType {
  data: DailyAttendanceReport[]
}

interface filterType {
  search?: string
  date?: string
  page: number
}


export const useDailyAttendanceReport = defineStore('daily-attendance-report', ()=>{

  const attendance: Ref<DailyAttendanceReport[]> = ref([])

  const params: Ref<{
    'filter[search]'?: string;
    'filter[date]'?: string;
    'page': number;
  }> = ref({
    'page': 1,
  });

  const meta: Ref<ResponseMeta> = ref(ResponseMetaDefaults);

  const filters: Ref<filterType> = ref({
    search: "",
    page: 1
  });

  const router = () => useRouter();
  const route = () => useRoute();

  watch(filters, (newstate: filterType)=>{
    // params.value['filter[search]'] = newstate.search;
    params.value['filter[date]'] = newstate.date;
    params.value['page'] = newstate.page;

    router().push({
      path: route().path,
      query: (newstate as unknown) as LocationQueryRaw
    });

    listAttendance();
  }, {
    deep: true
  })
  
  const listAttendance = () => {
    const { data, error } = useApi('/admin/students/attendance/daily', {
      method: 'GET',
      params: params.value,
    })

    if(!error.value){
      const response: reportResponse = data.value as reportResponse;
      attendance.value = response.data
    }
  }

  return {
    attendance,
    listAttendance
  }
})