
export const useFileUploadStore = defineStore('file-upload', () => {
  const doUpload = async (form: FormData) => {
    const uploadUrl: null|string = await getSignedUploadURL();
    if(uploadUrl){
      const {data, error} = await usePostFormData(uploadUrl, form)
      if(!error.value && data.value?.data){
        return data.value?.data;
      }
    }

    return null;
  }

  const getSignedUploadURL = async () => {
    const {data, error} = await useApi('file/upload', {
        method: 'GET'
    })

    if(!error.value && data.value?.data){
      return data.value?.data?.url
    }

    return null;
  }

  return {
    doUpload,
    getSignedUploadURL
  }
})