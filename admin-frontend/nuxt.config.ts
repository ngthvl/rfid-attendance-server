// https://nuxt.com/docs/api/configuration/nuxt-config
import fs from 'fs';
import path from 'path';

const pubkeyPath = path.join(__dirname, '.secrets/oauth-public.key');

const pubKeyContent = fs.readFileSync(pubkeyPath, {encoding: "utf8"});

export default defineNuxtConfig({
  ssr: false,
  devtools: {
    enabled: true,

    timeline: {
      enabled: true
    }
  },
  runtimeConfig: {
    public: {
      rsaPubKey: pubKeyContent,
      apiBase: 'http://api.student-attendance.internal/api/v1',
      storageBase: 'http://api.student-attendance.internal/storage',
      assetBase: 'http://api.student-attendance.internal/assets',
      appOwner: 'CODELINES',
      appNameSub: 'Student Attendance System'
    }
  },
  modules: [    
    '@invictus.codes/nuxt-vuetify',
    '@pinia/nuxt',
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