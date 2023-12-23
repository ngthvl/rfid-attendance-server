export interface PhonebookType {
  id: string
  contact_number: string
  contact_name: string
  last_message: string
}

export interface MessagesType {
  id: string
  message: string
  type: string
  status: string
}

export const useMessengerStore = defineStore('messenger-store', ()=>{
  const phonebook: Ref<PhonebookType[]> = ref([]);
  const currentMessages: Ref<MessagesType[]> = ref([]);

  const getPhonebook = async () => {
    const {data, error} = await useApi('/admin/phonebook', {
      method: 'GET'
    })

    if(!error.value){
      phonebook.value = data.value.data
    }
  }

  const getContactMessages = async (contact: PhonebookType) => {
    const {data, error} = await useApi(`/admin/phonebook/${contact.id}/messages`, {
      method: 'GET'
    })

    if(!error.value){
      currentMessages.value = data.value.data
    }
  }
  
  return {
    phonebook,
    currentMessages,
    getPhonebook,
    getContactMessages
  }
})