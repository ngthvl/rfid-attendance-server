<script setup lang="ts">
import { useFileUploadStore } from "~/models/fileUpload";
import { useNotificationStore } from "~/models/notification";
import Camera from "simple-vue-camera";

const fileUploadStore = useFileUploadStore();
const notificationStore = useNotificationStore();

const emit = defineEmits(['upload-success'])

const dialog: Ref<boolean> = ref(false);

const filePickerRef: Ref = ref();

const imgSrc: Ref<string> = ref('');

let fileReader: FileReader | undefined;

const camera = ref<InstanceType<typeof Camera>>();

const openCamera = () => {
  
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
        imgSrc.value = e.target?.result as string ?? '';
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
    emit('upload-success', url.url);
    notificationStore.pushNotification("Successfully Uploaded!");
  }
}

const snapshot = async () => {
    const blob = await camera.value?.snapshot();

    // To show the screenshot with an image tag, create a url
    const url = URL.createObjectURL(blob);
}

defineExpose({
  open,
  close
})
</script>
<template>    
  <v-dialog width="600" v-model="dialog">
    <v-card>
      <v-card-item class="mb-5">
        <form @submit="doUpload">
          <v-row>
            <v-col cols="5" class="text-center">
              <div class="d-flex">
                <v-img :src="imgSrc"></v-img>

                <camera :resolution="{ width: 375, height: 812 }" autoplay></camera>
              </div>

              <v-btn block class="mt-5" type="submit" color="success" variant="elevated" size="x-large" :loading="isApiLoading">Submit</v-btn>
            </v-col>
            <v-col cols="7">
              <v-row>
                <v-col cols="12">
                  <v-btn class="selection-btn" @click="openCamera" type="button" variant="elevated" block>
                    <div>
                      <h1><v-icon icon="mdi-camera"></v-icon></h1>
                      <p>Use Camera</p>
                    </div>
                  </v-btn>
                </v-col>
                <v-col cols="12">
                  <v-btn class="selection-btn" type="button" @click="openFilePicker" variant="elevated" block>
                    <div>
                      <h1><v-icon icon="mdi-laptop"></v-icon></h1>
                      <p>Choose from device</p>
                    </div>
                  </v-btn>
                </v-col>
              </v-row>
              <input type="file" ref="filePickerRef" hidden @change="fileReaderOnChange" name="file">
            </v-col>
          </v-row>
        </form>
      </v-card-item>
    </v-card>
  </v-dialog>  
</template>

<style scoped>
.selection-btn {
  padding-top: 100px!important;
  padding-bottom: 100px!important;
}
</style>