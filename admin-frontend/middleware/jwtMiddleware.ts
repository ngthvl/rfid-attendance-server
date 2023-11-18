import {CookieRef, navigateTo, useCookie, useRuntimeConfig} from "nuxt/app";

// import jwt from 'jsonwebtoken';
import {jwtDecode} from "jwt-decode";

export default defineNuxtRouteMiddleware((to, from) => {
  const runtimeConfig = useRuntimeConfig();
  const cookieStore = useCookie("accessToken");

  const pubkey = runtimeConfig.public.rsaPubKey;

  const userState = () => useState('userData');

  if(!cookieStore.value){
      return navigateTo("/login");
  }else{
    try{
      const jwt = jwtDecode(cookieStore.value);

      if(!userState().value){
        useFetchMe().then((res) => {
          if(res.data.value?.data){

            userState().value = res.data.value?.data;
          }
        })
      }

    } catch(err) {
      return navigateTo('/login');
    }
  }
    
})
