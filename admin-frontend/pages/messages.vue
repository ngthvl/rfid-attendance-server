<script setup lang="ts">

import jwtMiddleware from "~/middleware/jwtMiddleware";
import { useMessengerStore } from "~/models/messenger";
import { transformDateTime } from '~/helpers/util';

const messengerStore = useMessengerStore();

const { phonebook, currentMessages } = storeToRefs(messengerStore);

onMounted(async ()=>{
  messengerStore.getPhonebook();
})

definePageMeta({
  middleware: jwtMiddleware,
  layout: 'admin',
})

</script>

<template>
  <div>
    <v-row class="p-4">
      <v-col cols="2" offset="1">
        <v-card variant="elevated" style="min-height: 700px;">
          <v-list variant="elevated">
            <v-list-item 
              v-for="(contact, key) in phonebook" 
              :key="key" 
              :title="contact.contact_name ?? 'Unknown Number'" 
              :subtitle="contact.contact_number"
              @click="messengerStore.getContactMessages(contact)"
            ></v-list-item>
          </v-list>
        </v-card>
        
      </v-col>
      <v-col cols="8" class="overflow-y-auto px-5" style="max-height: 700px;">
        <div 
          v-for="(message, key) in currentMessages" 
          class="d-flex flex-column align-end mt-4"
          :key="key"
        >
          <v-card variant="elevated" color="primary" style="max-width:500px!important">
            <v-card-text>{{ message.message }}</v-card-text>
            <v-card-text>
              <small class="status-text">{{ message.status }}</small>&nbsp;
              <small>{{ transformDateTime(message.created_at) }}</small>
            </v-card-text>
          </v-card>
        </div>
        <!-- <div class="d-flex flex-column align-start">
          <v-card variant="elevated" color="secondary" style="max-width:500px!important">
            <v-card-text>
              Lorem ipsum dolor sit amet consectetur adipisicing elit. Similique perferendis minus ut sequi, totam voluptatum illum quasi harum neque odit qui facilis vitae ex itaque natus laudantium. Quaerat, corrupti? Eum?
            </v-card-text>
          </v-card>
        </div> -->
      </v-col>
    </v-row>
  </div>
  
</template>

<style scoped lang="scss">
.status-text {
  text-transform: capitalize;
}
</style>
