import {Ref} from "vue";
import type { ResponseMeta } from "~/types/meta";
import { ResponseMetaDefaults } from '~/types/meta';
import {ClientErrorType} from "~/types/errortype";

export interface Student {
  student_id: string
  first_name: string
  last_name: string
  contact_person: string
  contact_number: string
  contact_address: string
  id?: string
  created_at?: string,
  rfid_tag: null | {
    id: string,
    tag_data: string
  }
}

interface filterType {
  search: string
  page: number
}

const params: Ref<{
  'filter[search]': string;
  'page': number;
}> = ref({
  'filter[search]': '',
  'page': 1,
})

export const filters: Ref<filterType> = ref({
  search: "",
  page: 1
});

const router = () => useRouter();
const route = () => useRoute();

watch(filters, (newstate: filterType)=>{
  params.value['filter[search]'] = newstate.search;
  params.value['page'] = newstate.page;

  router().push({
    path: route().path,
    query: newstate
  });

  list();
}, {
  deep: true
})

export const students: Ref<Student[]> = ref([]);

export const meta: Ref<ResponseMeta> = ref(ResponseMetaDefaults);

export const list = async () => {
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

export const save = async (student: Student) => {
  useApi('/admin/students', {
    method: "POST",
    body: student
  })
}

export const updateFromCsv = async () => {
  await useApi('/admin/students/import', {
    method: 'POST'
  })
}