<template>
    <q-layout view="hHh LpR fFf">
  
      <q-header elevated class="text-white bg-primary" height-hint="98">
        <q-toolbar>
          <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />
            
          <q-toolbar-title>
            <q-avatar>
              <img src="https://upload.wikimedia.org/wikipedia/commons/c/c6/Ashok_Emblem_svg.svg">



                
            </q-avatar>
            Finance Department (EC)
          </q-toolbar-title>
        </q-toolbar>
  
        <q-tabs align="left">
          <!-- <q-route-tab to="/gpf" label="GPF" /> -->
          <!-- <q-route-tab to="/page2" label="Page Two" /> -->
          <!-- <q-route-tab to="/logout" label="Logout" />
           -->
          <q-route-tab v-if="isLoggedIn" @click="gpf()" label="GPF"/>
          <q-route-tab v-if="isLoggedIn" @click="profie()" label="Profile"/>
          <q-route-tab v-if="isLoggedIn" @click="logout()" label="Logout"/>
         
          
        </q-tabs>
      </q-header>
  
      <q-drawer v-model="leftDrawerOpen" side="left" overlay bordered>
        <!-- drawer content -->
      </q-drawer>
  
      <q-page-container>
        <slot />
      </q-page-container>
      
      
      <div class="flex items-center justify-center">
        <q-footer elevated class="text-white bg-primary">
        <q-toolbar>
          <q-toolbar-title>
            <!-- <q-avatar>
              <img src="https://upload.wikimedia.org/wikipedia/commons/c/c6/Ashok_Emblem_svg.svg">
            </q-avatar> -->
            <div class="flex items-center justify-center">
            <span>
              Copyright © {{ new Date().getFullYear() }} Created by
              <a href="https://msegs.mizoram.gov.in/" target="_blank" title="Colorlib">MSeGS</a>. All rights reserved.
            </span>
          </div>
            
          </q-toolbar-title>
        </q-toolbar>
          <!-- <div class="flex items-center justify-center">
            <span>
              Copyright © {{ new Date().getFullYear() }} Created by
              <a href="https://msegs.mizoram.gov.in/" target="_blank" title="Colorlib">MSeGS</a>. All rights reserved.
            </span>
          </div> -->
      </q-footer>
  
      </div>
      
      
    </q-layout>
  </template>
  

<script setup>
  import { ref, onMounted  } from 'vue'
  import { useRouter } from 'vue-router';
  const leftDrawerOpen = ref(false)
  const router = useRouter();
  const isLoggedIn = ref(false);
  
  const toggleLeftDrawer = () => {
    leftDrawerOpen.value = !leftDrawerOpen.value
  }

  const logout = async () => {
  try {
    await axios.get('/api/logout', {
      headers: {
        Authorization: localStorage.getItem('token')
      }
    });
    localStorage.removeItem('token');
    alert('Logged out successfully');
    router.push('/');
    // window.location.reload()
    // Reload the page and clear browser cache
    window.location.href = '/';
    console.log('logged out');
    // Redirect or perform any other actions after successful logout
  } 
  catch (error) {
    alert('Error logging out');
    console.error('Error:', error);
  }
};
const profie = async () => {
  router.push('/profile')
}
const gpf = async () => {
  router.push('/gpf')
}
const isAuthenticated = () => {
  // Implement logic to check if the user is logged in
  // For example, you can check if a token exists in localStorage
  const token = localStorage.getItem('token');
  return !!token; // Return true if token exists, false otherwise
};


onMounted(() => {
  isLoggedIn.value = isAuthenticated();
});
</script>