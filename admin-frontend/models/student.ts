import {Ref} from "vue";
import type { ResponseMeta } from "~/types/meta";
import { ResponseMetaDefaults } from '~/types/meta';
import {defineStore} from "pinia";

export interface Student {
  student_id: string
  first_name: string
  last_name: string
  contact_person: string
  contact_number: string
  contact_address: string
  id?: string
  created_at?: string,
  rfid_tag?: null | {
    id: string,
    tag_data: string
  }
}

interface filterType {
  search: string
  page: number
}



export const useStudentsStore = defineStore('students', () => {
  const students: Ref<Student[]> = ref([]);

  const params: Ref<{
    'filter[search]': string;
    'page': number;
  }> = ref({
    'filter[search]': '',
    'page': 1,
  })

  const router = () => useRouter();
  const route = () => useRoute();

  const listStudents = async () => {
    const {data, error} = await useApi('/admin/students', {
      params: filters.value,
      method: "GET"
    });

    if (data?.value?.data) {
      students.value = data?.value?.data;
    }

    if (data?.value?.meta) {
      meta.value = data?.value?.meta;
    }
  }

  const save = async (student: Student) => {
    useApi('/admin/students', {
      method: "POST",
      body: student
    })
  }

  const updateFromCsv = async () => {
    await useApi('/admin/students/import', {
      method: 'POST'
    })
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

    listStudents();
  }, {
    deep: true
  })

  return {
    students,
    meta,
    filters,
    save,
    listStudents
  }
})