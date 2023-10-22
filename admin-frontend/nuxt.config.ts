// https://nuxt.com/docs/api/configuration/nuxt-config
import fs from 'fs';
import path from 'path';

const pubkeyPath = path.join(__dirname, '.secrets/oauth-public.key');

const pubKeyContent = fs.readFileSync(pubkeyPath, {encoding: "utf8"});

export default defineNuxtConfig({
  devtools: { enabled: true },
  runtimeConfig: {
    rsaPubKey: pubKeyContent,
  },
})
