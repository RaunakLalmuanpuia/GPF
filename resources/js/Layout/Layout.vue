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
          <q-btn-dropdown
                    color="primary"
                    class="ml-4"
                    label="Dealing"
                >
                    <q-list>
                        <q-item clickable v-close-popup @click="profie()">
                            <q-item-section>
                                <q-item-label>Profile</q-item-label>
                            </q-item-section>
                        </q-item>

                        <q-item clickable v-close-popup @click="logout()">
                            <q-item-section>
                                <q-item-label>Logout</q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-btn-dropdown>
        </q-toolbar>
  
        <q-tabs align="left">
          <!-- <q-route-tab to="/gpf" label="GPF" /> -->
          <!-- <q-route-tab to="/page2" label="Page Two" /> -->
          <!-- <q-route-tab to="/logout" label="Logout" />
           -->
          <q-route-tab v-if="isLoggedIn" @click="gpf()" :class="{ 'bg-green-500': $route.path === '/gpf' }" label="GPF"/>
          <q-route-tab v-if="isLoggedIn" @click="individual()" :class="{ 'bg-green-500': $route.path === '/individual' }" label="Individuals"/>
          <q-route-tab v-if="isLoggedIn" @click="departments()" :class="{ 'bg-green-500': $route.path === '/departments' }" label="Departments"/>
          <!-- <q-route-tab v-if="isLoggedIn" @click="profie()" :class="{ 'bg-green-500': $route.path === '/profile' }" label="Profile"/>
          <q-route-tab v-if="isLoggedIn" @click="logout()" label="Logout"/> -->
         
         
          
        </q-tabs>
      </q-header>
  
      <q-drawer v-model="leftDrawerOpen" side="left" overlay bordered>
        <!-- drawer content -->
      </q-drawer>
  
      <q-page-container>
        <slot />
      </q-page-container>
      
      
      <div class="flex items-center justify-center bg-primary">
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
  import axios from 'axios';
  import { ref, onMounted  } from 'vue'
  import { useRouter } from 'vue-router';
  const leftDrawerOpen = ref(false)
  const router = useRouter();
  const isLoggedIn = ref(false);
  const userId = ref(null);
  
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
  try {
        const token = localStorage.getItem('token'); // Get the token from local storage
        if (!token) {
            throw new Error('No token found');
        }
        const config = {
            headers: {
                Authorization: `Bearer ${token}` // Include the token in the request headers
            }
        };

        const response = await axios.get(`/api/user/`, config);
        userId.value = response.data.id;
        // console.log('userID' +userId.value)
        // If token is found, navigate to the edit page with the ID
        router.push('/profile',config);
    } catch (error) {
        console.error('Authentication error:', error);
        // Handle authentication error here, such as redirecting to the login page
    }
  
}
const gpf = async () => {
  router.push('/gpf')
}
const individual = async () => {
  router.push('/individual')
}
const departments = async () => {
  router.push('/departments')
}
const isAuthenticated = () => {

  //check if a token exists in localStorage
  const token = localStorage.getItem('token');
  return !!token; // Return true if token exists, false otherwise
};



onMounted(() => {
  isLoggedIn.value = isAuthenticated();
});
</script>