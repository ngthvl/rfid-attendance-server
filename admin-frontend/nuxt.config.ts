// https://nuxt.com/docs/api/configuration/nuxt-config
import fs from 'fs';
import path from 'path';

const pubkeyPath = path.join(__dirname, '.secrets/oauth-public.key');

const pubKeyContent = fs.readFileSync(pubkeyPath, {encoding: "utf8"});

export default defineNuxtConfig({
  devtools: { enabled: true },
  runtimeConfig: {
    public: {
      rsaPubKey: pubKeyContent,
      apiBase: '/api',
      appOwner: 'CODELINES',
      appNameSub: 'Student Attendance System'
    }
  },
  modules: [    
    '@invictus.codes/nuxt-vuetify'  
  ],  
  vuetify: {
    vuetifyOptions: { },
    moduleOptions: {
      treeshaking: true,      
      useIconCDN: false,  
      // styles: true | 'none' | 'expose' | 'sass' | { configFile: string },      
      autoImport: true,      
      // useVuetifyLabs: true | false,     
    }
  }   
})
