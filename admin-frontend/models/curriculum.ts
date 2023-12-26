import { ResponseMeta } from "~/types/meta"

export interface SectionType {
  id: number;
  education_level_id: number;
  section_name: string;
}

export interface EducationLevelType {
  education_level_name: string;
  id: number;
  created_at: string;
  sections?: SectionType[];
}

interface EducLevelResponseType {
  meta: ResponseMeta;
  data: EducationLevelType[]
}

export const useCurriculumStore = defineStore('curriculum-store', () => {
  const educationLevels: Ref<EducationLevelType[]> = ref([]);

  const listEducationLevels = async () => {
    const {data, error} = await useApi('/admin/education-levels', {
      method: "GET"
    })

    if(!error.value){
      const dt: EducLevelResponseType = data.value as EducLevelResponseType;
      educationLevels.value = dt.data
    }
  }

  return {
    listEducationLevels,
    educationLevels,
  }
});