import {Ref} from "vue";
import {useCookie, useRouter} from "nuxt/app";

export const useFetchMe = async () => {
    return useApi(
        '/admin/me',
        {
            method: 'GET',
        },
    );
};