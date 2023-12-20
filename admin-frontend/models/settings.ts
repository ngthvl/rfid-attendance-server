import {Ref} from "vue";
import type { ResponseMeta } from "~/types/meta";
import { ResponseMetaDefaults } from '~/types/meta';
import {defineStore} from "pinia";

export interface SettingsType {
  curriculum_settings: {
    school_year?: string
  }
}

interface filterType {
  search: string
  page: number
}


export const useSettingsStore = defineStore('settings', () => {
  const settings: Ref<SettingsType> = ref({
    curriculum_settings: {
      school_year: ''
    }
  });

  const params: Ref<{
    'filter[search]': string;
    'page': number;
  }> = ref({
    'filter[search]': '',
    'page': 1,
  })

  const router = () => useRouter();
  const route = () => useRoute();

  const listSettings = async () => {
    const {data, error} = await useApi('/admin/settings', {
      params: filters.value,
      method: "GET"
    });

    if (data?.value?.data) {
      settings.value = data?.value?.data;
    }

    if (data?.value?.meta) {
      meta.value = data?.value?.meta;
    }
  }

  const updateSettings = async () => {
    const {data, error} = await useApi('/admin/settings', {
      body: settings.value,
      method: "POST"
    });
  };

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

    listSettings();
  }, {
    deep: true
  })

  return {
    settings,
    meta,
    filters,
    listSettings,
    updateSettings
  }
})