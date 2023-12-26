import type { ResponseMeta } from "~/types/meta";
import { ResponseMetaDefaults } from '~/types/meta';
import {defineStore} from "pinia";
import { EducationLevelType, SectionType } from "./curriculum";
import { LocationQueryRaw } from "vue-router";
import { ClientErrorType } from "~/types/errortype";
import { StudentAttendance } from "./studentAttendance";
import { DetectionLog } from "./detectionLog";

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
  },
  section?: SectionType,
  level?: EducationLevelType
  attendance?: DetectionLog[]
}

export interface SaveMultipleType {
  data: Student[]
  section?: SectionType
  level?: EducationLevelType
}

interface filterType {
  search?: string
  page?: number
  section?: number,
  level?: number
}

interface ResponseType {
  data: Student[]
  meta: ResponseMeta
}

interface StudentInfoResponseType {
  data: Student
}

export const studentDefaults = {
  student_id: '',
  first_name: '',
  last_name: '',
  contact_person: '',
  contact_number: '',
  contact_address: '',
};

interface StudentCreateErrorType extends ClientErrorType {
  errors: {
    student_id?: string
    first_name?: string
    last_name?: string
    contact_person?: string
    contact_number?: string
    contact_address?: string
    created_at?: string
    avatar?: string
  }
}

export const useStudentsStore = defineStore('students', () => {
  const students: Ref<Student[]> = ref([]);

  const errors: Ref<StudentCreateErrorType|undefined> = ref();

  const uploadingCsv: Ref<boolean> = ref(false);

  const params: Ref<{
    'filter[search]'?: string;
    'filter[section_id]'?: number;
    'filter[education_level_id]'?: number;
    'page': number;
  }> = ref({
    'filter[search]': '',
    'page': 1,
  })

  const router = () => useRouter();
  const route = () => useRoute();

  const listStudents = async () => {
    const {data, error} = await useApi('/admin/students', {
      params: Object.assign({}, params.value),
      method: "GET"
    });

    if(!error.value){
      const response: ResponseType = data.value as ResponseType;
      students.value = response.data
      meta.value = response.meta
    }
  }

  const save = async (student: Student) => {
    errors.value = undefined;
    const {data, error} = await useApi('/admin/students', {
      method: "POST",
      body: student
    })

    if(error.value?.data){
      errors.value = error.value.data
    }

    return {data, error}
  }

  const saveMultiple = async (body: SaveMultipleType) => {
    const {data, error} = await useApi('/admin/students/save-multiple', {
      method: 'POST',
      body: body
    })
  }

  const update = async (student: Student) => {
    const {data, error} = await useApi(`/admin/students/${student.id}`, {
      method: "PATCH",
      body: student
    });

    return {data, error};
  }

  const getStudentInfo = async (id: string) => {
    const { data, error } = await useApi(`/admin/students/${id}`, {
      method: "GET",
    })

    let student: Student | undefined;

    if(!error.value){
      const response = data.value as StudentInfoResponseType
      student = response.data
    }

    return student;
  }

  const updateFromCsv = async (req: FormData) => {
    uploadingCsv.value = true;
    const {data, error} = await useApi('/admin/students/import', {
      method: 'POST',
      body: req
    })
    uploadingCsv.value = false;

    if(!error.value){
      listStudents();
    }

    return {data, error};
  }

  const meta: Ref<ResponseMeta> = ref(ResponseMetaDefaults);

  const filters: Ref<filterType> = ref({
    search: "",
    page: 1
  });

  watch(filters, (newstate: filterType)=>{
    params.value['filter[search]'] = newstate.search;
    params.value['filter[section_id]'] = newstate.section;
    params.value['filter[education_level_id]'] = newstate.level;
    params.value['page'] = newstate.page ?? 1;

    router().push({
      path: route().path,
      query: newstate as LocationQueryRaw
    });

    listStudents();
  }, {
    deep: true
  })

  return {
    uploadingCsv,
    students,
    meta,
    filters,
    errors,
    save,
    update,
    listStudents,
    saveMultiple,
    getStudentInfo,
    updateFromCsv
  }
})