import {Ref} from "vue";
import {ResponseMeta} from "~/types/meta";

export interface Admin {
  id: number
  name: String
  email: String
}

interface ResponseType {
  data: Admin[],
  meta: ResponseMeta
}

export const filters: Ref = ref({
  search: "",
  page: 1
});

export const admins: Ref<Admin[]> = ref([]);

export const meta: Ref<ResponseMeta | undefined> = ref(undefined);

export const list = async () => {
  const {data, error} = await useApi('/admin/admins', {
    params: filters.value,
    method: "GET"
  });

  if(!error.value){
    const response:ResponseType = data.value as ResponseType;
    admins.value = response.data as Admin[];
    meta.value = response.meta;
  }  
}