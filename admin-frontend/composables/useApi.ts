import {R} from "vite-node/types-516036fa";
import {FetchContext, FetchResponse} from "ofetch";
import {useRouter} from "nuxt/app";

export const isApiLoading = ref(false);
export const useApi = (
    path: string,
    {
      method,
      params,
      body,
      tempToken,
    }: {
      method: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
      params?: any
      body?: any
      tempToken?: string
    }
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
