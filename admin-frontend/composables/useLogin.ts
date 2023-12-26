import {Ref} from "vue";
import {useCookie, useRouter} from "nuxt/app";
import { Admin } from "~/models/admin";

interface tokenResponseType {
    data: {
        access_token: string,
        user: Admin
    }
}

export const useLogin = async (creds: Ref) => {
    const { data, error } = await useApi(
        '/admin/auth',
        {
            method: 'POST',
            body: creds.value,
        },
    );
    if(error.value){

    }
    if(!error.value && data.value){
        const response: tokenResponseType = data.value as tokenResponseType
        const cookie = useCookie('accessToken');
        const router = useRouter();
        cookie.value = response.data.access_token;

        const userData = useState('userData');
        userData.value = response.data.user;

        router.push('/');
    }
};

export const useLogout = () => {
  const cookie = () => useCookie('accessToken');
  const router = () => useRouter();

  cookie().value = null;

  router().push('/login');
}
