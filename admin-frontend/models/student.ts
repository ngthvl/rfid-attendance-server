import {Ref} from "vue";
import type { ResponseMeta } from "~/types/meta";
import { ResponseMetaDefaults } from '~/types/meta';
import {defineStore} from "pinia";
import { EducationLevelType, SectionType } from "./curriculum";

export interface Student {
  student_id: string
  first_name: string
  last_name: string
  contact_person: string
  contact_number: string
  contact_address: string
  id?: string
  created_at?: string,
  avatar?: string,
  rfid_tag?: null | {
    id: string,
    tag_data: string
  }
}

export interface SaveMultipleType {
  data: Student[]
  section?: SectionType
  level?: EducationLevelType
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

  const saveMultiple = async (body: SaveMultipleType) => {
    const {data, error} = await useApi('/admin/students/save-multiple', {
      method: 'POST',
      body: body
    })
  }

  const update = async (student: Student) => {
    await useApi(`/admin/students/${student.id}`, {
      method: "PATCH",
      body: student
    })

    listStudents()
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
    update,
    listStudents,
    saveMultiple
  }
})