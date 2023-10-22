import {CookieRef, navigateTo, useCookie, useRuntimeConfig} from "nuxt/app";

import jwt from 'jsonwebtoken';

export default defineNuxtRouteMiddleware((to, from) => {
    const runtimeConfig = useRuntimeConfig();
    const cookieStore = useCookie("token");

    const pubkey = runtimeConfig.rsaPubKey;

    if(!cookieStore.value){
        return navigateTo("/login");
    }

    try{
        const jw = jwt.verify(cookieStore.value, pubkey);
        console.log(jw);
    } catch(err) {
        return navigateTo('/login');
    }
})
