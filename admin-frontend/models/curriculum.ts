export interface SectionType {
  id: number
  education_level_id: number
  section_name: string
}

export interface EducationLevelType {
  education_level_name: string
  id: string
  created_at: string
  sections?: SectionType[]
}

export const useCurriculumStore = defineStore('curriculum-store', () => {
  const educationLevels: Ref<EducationLevelType[]> = ref([]);

  const listEducationLevels = async () => {
    const {data, error} = await useApi('/admin/education-levels', {
      method: "GET"
    })

    if(!error.value){
      educationLevels.value = data.value.data
    }
  }

  return {
    listEducationLevels,
    educationLevels,
  }
});