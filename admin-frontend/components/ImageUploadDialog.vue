<script setup lang="ts">
import { useFileUploadStore } from "~/models/fileUpload";

const fileUploadStore = useFileUploadStore();

const emit = defineEmits(['upload-success'])

const dialog: Ref<boolean> = ref(false);

const filePickerRef: Ref = ref();

const imgSrc: Ref<string> = ref('');

let fileReader: FileReader | undefined;

const openCamera = () => {
  if ('mediaDevices' in navigator && 'getUserMedia' in navigator.mediaDevices) {
    navigator.mediaDevices.getUserMedia({video: true})
  }
}

const open = () => {
  fileReader = undefined;
  dialog.value = true;
  imgSrc.value = '';
}

const close = () => {
  dialog.value = false;
}

const openFilePicker = () => {
  filePickerRef.value.click();
}

const fileReaderOnChange = (e) => {
  const file = e.target.files[0];

  if (!file.type.match('image.*')) {
    return;
  }

  if(!fileReader){
    fileReader = new FileReader();

    fileReader.onload = ((theFile) => {
      return (e) => {
        imgSrc.value = e.target.result ?? '';
      };
    })(file);

    // Read in the image file as a data URL.
    fileReader.readAsDataURL(file);
  }
}

const doUpload = async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  const url = await fileUploadStore.doUpload(formData);

  if(url){
    emit('upload-success', url.url)
  }
}
defineExpose({
  open,
  close
})
</script>
<template>    
  <v-dialog width="300" v-model="dialog">
    <v-card>
      <v-card-item>
        <form @submit="doUpload">
          <v-img :src="imgSrc"></v-img>
          <v-row>
            <v-col>
              <v-btn @click="openCamera" type="button" color="primary" block>
                <h1><v-icon icon="mdi-camera"></v-icon></h1>
              </v-btn>
            </v-col>
            <v-col>
              <v-btn type="button" @click="openFilePicker" color="primary" block>
                <h1><v-icon icon="mdi-laptop"></v-icon></h1>
              </v-btn>
            </v-col>
          </v-row>
          <input type="file" ref="filePickerRef" hidden @change="fileReaderOnChange" name="file">
          <v-btn type="submit" color="success" variant="elevated">Submit</v-btn>
        </form>
      </v-card-item>
    </v-card>
  </v-dialog>  
</template>