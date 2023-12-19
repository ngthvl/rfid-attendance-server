import {defineStore} from "pinia";
import {Ref} from "vue/dist/vue";
import {ResponseMeta, ResponseMetaDefaults} from "~/types/meta";


export interface RfidTerminalType {
  id: string
  terminal_id: string
  device_name: string
  ip_address: string
  devices_status: unknown
  created_at: string
}

interface filterType {
  search: string
  page: number
}


export const useRfidTerminalStore = defineStore('rfid_terminal', () => {
  const terminals: Ref<RfidTerminalType[]> = ref([]);

  const params: Ref<{
    'filter[search]': string;
    'page': number;
  }> = ref({
    'filter[search]': '',
    'page': 1,
  })

  const meta: Ref<ResponseMeta> = ref(ResponseMetaDefaults);

  const filters: Ref<filterType> = ref({
    search: "",
    page: 1
  });

  const router = () => useRouter();
  const route = () => useRoute();

  const listTerminals = async () => {
    const {data, error} = await useApi('/admin/terminals', {
      params: filters.value,
      method: "GET"
    });

    if (data?.value?.data) {
      terminals.value = data?.value?.data;
    }

    if (data?.value?.meta) {
      meta.value = data?.value?.meta as ResponseMeta;
    }
  }

  const authorizeTerminal = async (terminal: RfidTerminalType) => {
    const { data, error } = await useApi(`/admin/terminals/${terminal.id}/authorize`, {
      method: "POST",
    })
  }

  return {
    terminals,
    filters,
    meta,
    listTerminals,
    authorizeTerminal,
  }
})