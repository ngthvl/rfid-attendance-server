import {Ref} from "vue";
import {useCookie, useRouter} from "nuxt/app";

export const useLogin = async (creds: Ref) => {
    const { data } = await useApi(
        '/admin/auth',
        {
            method: 'POST',
            body: creds.value,
        },
    );
    if(data?.value?.data?.access_token){
        const cookie = useCookie('accessToken');
        const router = useRouter();
        cookie.value = data?.value?.data?.access_token;

        const userData = useState('userData');
        userData.value = data?.value?.data?.user;

        router.push('/');
    }
};

export const useLogout = () => {
  const cookie = () => useCookie('accessToken');
  const router = () => useRouter();

  cookie().value = null;

  router().push('/login');
}
