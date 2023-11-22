import {R} from "vite-node/types-516036fa";
import {FetchContext, FetchResponse} from "ofetch";
import {useRouter} from "nuxt/app";
import {Ref} from "vue";
import {ClientErrorType} from "~/types/errortype";

interface apiParams{
  method: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  params?: any
  body?: any
  tempToken?: string,
  headers?: object
}
export const isApiLoading = ref(false);

export const clientErrors: Ref<ClientErrorType> = ref({});

export const useApi = (
  path: string,
  {
    method,
    params,
    body,
    tempToken,
    headers
  }: apiParams
) => {
  const config = useRuntimeConfig()
  const cookies = useCookie('accessToken')

  return useFetch(path, {
    method,
    baseURL: config.public.apiBase,
    body,
    params,
    headers: {
      Accept: 'application/json',
      Authorization: cookies.value || tempToken ? `Bearer ${cookies.value || tempToken}` : undefined,
    } as HeadersInit,
    onRequest(context: FetchContext): Promise<void> | void {
      isApiLoading.value = true;
    },
    onResponse({ request, response, options }) {
      isApiLoading.value = false;
      if(response.status == 401){
        const rt = useRouter();
        rt.push('/login');
      }

      if(response.status == 422){
        clientErrors.value = response._data
      }
    },
  })
}

export const useApiSSR = <T>(
    path: string,
    {
        method,
        params = {},
        body,
        transform,
    }: {
        method: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
        params?: any
        body?: any
        transform?: any
    }
) => {
  const config = useRuntimeConfig()
  const cookies = useCookie('accessToken')

  return useFetch<T>(path, {
    baseURL: config.public.apiBase,
    body,
    params: params,
    headers: {
      Authorization: cookies.value ? `Bearer ${cookies.value}` : undefined,
    } as HeadersInit,
    transform,
  })
}
