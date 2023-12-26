<script setup lang="ts">
import { CredentialType, useAuthStore } from "~/models/auth";

const credentials: Ref<CredentialType> = ref({
    email: "",
    password: ""
})

const authStore = useAuthStore();

const { errors } = storeToRefs(authStore);

const login = ()=>{
  authStore.useLogin(Object.assign({}, credentials.value))
}

const config = useRuntimeConfig();

definePageMeta({
  layout: 'auth',
});
</script>

<template>
  <v-container class="my-4 px-12" min-width="500">
    <form @submit.prevent="login">
      <v-row>
        <v-col cols="4" offset="4">
          <v-card>
            <v-card-text class="text-center">
              <h1>{{ config.public.appOwner }}</h1>
              <p>{{ config.public.appNameSub }}</p>
            </v-card-text>
            <v-card-text>
              <v-alert color="error" v-if="errors">{{ errors.message }}</v-alert>
            </v-card-text>
            <v-card-text>
              <v-text-field :error-messages="errors?.errors.email" v-model="credentials.email" label="Email/Username" type="email"></v-text-field>
              <v-text-field :error-messages="errors?.errors.password" v-model="credentials.password" label="Password" type="password"></v-text-field>
              <v-btn color="primary" type="submit" class="mt-4" :loading="isApiLoading">Login</v-btn>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </form>
  </v-container>
</template>
