<template>
    <q-layout>
      <q-page-container>
        <router-view>
          <template #default="{ Component }">
            <component :is="Component" />
          </template>
        </router-view>
      </q-page-container>
    </q-layout>
  </template>
  
  <script>
  export default {
    name: 'App',
  };
  </script>
  
 
  