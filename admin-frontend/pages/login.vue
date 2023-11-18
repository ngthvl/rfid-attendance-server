<script setup lang="ts">
import { useLogin } from "~/composables/useLogin";

const credentials = ref({
    email: "",
    password: ""
})

const login = ()=>{
  useLogin(credentials)
}

const config = useRuntimeConfig();

definePageMeta({
  layout: 'auth',
});
</script>

<template>
  <v-container class="my-4 px-12" min-width="500">
    <v-row>
      <v-col cols="4" offset="4">
        <v-card>
          <v-card-text class="text-center">
            <h1>{{ config.public.appOwner }}</h1>
            <p>{{ config.public.appNameSub }}</p>
          </v-card-text>
          <v-card-text>
            <v-text-field v-model="credentials.email" label="Email/Username" type="email"></v-text-field>
            <v-text-field v-model="credentials.password" label="Password" type="password"></v-text-field>
            <v-btn color="primary" @click="login">Login</v-btn>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>

  </v-container>
</template>
