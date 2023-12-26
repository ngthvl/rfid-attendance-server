import { ClientErrorType } from "~/types/errortype";
import { Admin } from "./admin";

interface tokenResponseType {
  data: {
      access_token: string,
      user: Admin
  }
}

interface AuthClientErrorType extends ClientErrorType {
  errors: {
    email: string[]
    password: string[]
  }
}

export interface CredentialType {
  email: string
  password: string
}

export const useAuthStore = defineStore('auth', () => {
  const errors: Ref<AuthClientErrorType|undefined> = ref();

  const useLogin = async (creds: CredentialType) => {
    errors.value = undefined;
    const { data, error } = await useApi(
        '/admin/auth',
        {
            method: 'POST',
            body: creds,
        },
    );
    if(error.value){
      if(error.value.data){
        errors.value = error.value.data
      }
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
  
  const useLogout = () => {
    const cookie = () => useCookie('accessToken');
    const router = () => useRouter();
  
    cookie().value = null;
  
    router().push('/login');
  }

  return {
    useLogin,
    useLogout,
    errors
  }
});