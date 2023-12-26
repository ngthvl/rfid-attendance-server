<script setup lang="ts">

import {Student, useStudentsStore} from "~/models/student";

const student: Ref<Student | null> = ref(null);

const editMultipleDialog: Ref<boolean> = ref(false);

const show = (st: Student) => {
  student.value = st;
  // editMultipleDialog.value = false;
}

const hide = () => {
  editMultipleDialog.value = false;
}

defineExpose({
  show,
  hide
})
</script>

<template>
  <div>
    <v-dialog v-model="editMultipleDialog" width="700">
      <v-card>
        <v-card-title class="d-flex justify-space-between">
          <span>Edit Students</span>
        </v-card-title>
        <v-card-item>
        </v-card-item>
        <v-card-item class="mb-4">
        </v-card-item>
      </v-card>
    </v-dialog>
  </div>
</template>