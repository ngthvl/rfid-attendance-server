import {Ref} from "vue";
import {ResponseMeta} from "~/types/meta";

export interface Admin {
  id: number
  name: String
  email: String
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

  admins.value = data?.value?.data;
  meta.value = data?.meta;
}