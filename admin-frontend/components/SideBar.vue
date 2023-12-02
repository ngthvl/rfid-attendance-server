<script setup lang="ts">
const config = useRuntimeConfig();
</script>

<template>
  <v-navigation-drawer>
    <v-list-item :title="config.public.appOwner" :subtitle="config.public.appNameSub"></v-list-item>
    <v-divider class="my-3"></v-divider>
    <v-list-item link to="/" title="Dashboard"></v-list-item>
    <v-list-item link to="/admins" title="Admins"></v-list-item>
    <v-list-item link to="/students" title="Students"></v-list-item>
    <v-list-item link to="/messages" title="Messages"></v-list-item>
    <v-list-item link to="/reports" title="Reports"></v-list-item>
    <v-list-item link to="/terminals" title="Terminals"></v-list-item>
  </v-navigation-drawer>
</template>

<style scoped>
.sidebarcard{
  min-height: 100vh;
}
</style>