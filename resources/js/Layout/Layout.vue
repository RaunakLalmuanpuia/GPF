<template>
    <q-layout view="hHh LpR fFf">
  
      <q-header elevated class="bg-primary text-white" height-hint="98">
        <q-toolbar>
          <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
            
          <q-toolbar-title>
            <q-avatar>
              <img src="https://upload.wikimedia.org/wikipedia/commons/c/c6/Ashok_Emblem_svg.svg">
              <!-- <img src="https://freesvg.org/img/indian-emblem.png" alt="Indian Emblem"> -->
              <!-- https://upload.wikimedia.org/wikipedia/commons/c/c6/Ashok_Emblem_svg.svg -->


                
            </q-avatar>
            GPF
          </q-toolbar-title>
        </q-toolbar>
  
        <q-tabs align="left">
          <q-route-tab to="/gpf" label="GPF" />
          <q-route-tab to="/page2" label="Page Two" />
          <q-route-tab to="/logout" label="Logout" />
        </q-tabs>
      </q-header>
  
      <q-drawer v-model="leftDrawerOpen" side="left" overlay bordered>
        <!-- drawer content -->
      </q-drawer>
  
      <q-page-container>
        <slot />
      </q-page-container>
  
      <q-footer elevated class="bg-grey-8 text-white">
        <q-toolbar>
          <q-toolbar-title>
            <q-avatar>
              <img src="https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg">
            </q-avatar>
            <div>Title</div>
          </q-toolbar-title>
        </q-toolbar>
      </q-footer>
  
    </q-layout>
  </template>
  
  <script>
  import { ref } from 'vue'
  
  export default {
    setup () {
      const leftDrawerOpen = ref(false)
  
      return {
        leftDrawerOpen,
        toggleLeftDrawer () {
          leftDrawerOpen.value = !leftDrawerOpen.value
        }
      }
    }
  }
  </script>