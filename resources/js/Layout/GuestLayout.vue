<template>
    <q-layout view="lHh Lpr lff">
  
      <q-header elevated class="text-white bg-primary" height-hint="98">
        <q-toolbar>
 
            
          <q-toolbar-title>
            <q-avatar>
              <img src="https://upload.wikimedia.org/wikipedia/commons/c/c6/Ashok_Emblem_svg.svg">
            </q-avatar>
            Finance Department (EC)
          </q-toolbar-title>
        </q-toolbar>
  
        <q-tabs align="left">
         
         
         
         
          
        </q-tabs>
      </q-header>
  
      <q-drawer v-model="leftDrawerOpen" side="left" overlay bordered>
        <!-- drawer content -->
      </q-drawer>
  
      <q-page-container>
        <slot />
      </q-page-container>
      
      
      
        <!-- <q-footer>
        
          <div class="flex flex-wrap text-white column q-pa-md bg-primary" >
            
            <p class=" text-h4 text-weight-bold">Finance Department (EC) GPF</p>


            <div>
              <div
                  class="flex justify-between row q-mt-md footer-small-font q-pb-md"
                >
                  <div>
                    <span
                      style="
                        /* font-family: 'Poppins'; */
                        font-size: 16px;
                        font-weight: 600;
                        color: #fdffff;
                      "
                      >SERVICES</span
                    >
                    <br />

                    

                    <div class="q-mt-sm" style="font-size: 14px">
                      <router-link
                        style="text-decoration: none; color: #fdffff"
                        to="/reports/"
                        > Reports</router-link
                      >
                    </div>
                  </div>
                  <div>
                    <span
                      style="
                        /* font-family: 'Poppins'; */
                        font-size: 16px;
                        font-weight: 600;
                        color: #fdffff;
                      "
                      >About</span
                    >

                    <br />

                    <div class="q-mt-sm column" style="font-size: 14px">

                      <router-link
                        style="text-decoration: none; color: #fdffff"
                        to="/pages/about"
                        >GPF</router-link
                      >
                      <a
                        style="text-decoration: none; color: #fdffff"
                        href="https://finance.mizoram.gov.in/"
                        target="_blank"

                        >Finance Department</a
                      >

                    </div>
                  </div>
                  <div>
                    <span
                      style="
                        /* font-family: 'Poppins'; */
                        font-size: 16px;
                        font-weight: 600;
                        color: #fdffff;
                      "
                      >LEGAL</span
                    >
                    <br />
                    <div class="q-mt-sm" style="font-size: 14px">
                      <router-link
                        style="text-decoration: none; color: #fdffff"
                        to="/pages/terms"
                        >Terms and Condition</router-link
                      >
                    </div>
                  </div>
                </div>
            </div>

            <div class="footerRows q-gutter-md">

            
              <div style="display: flex; justify-content: flex-end; margin-bottom: 4px;" class=" col-xs-2 col-sm-2 col-md-4" >
               
                
                 
                  <img :src="'/msegs.svg'"  alt="msegs">
                
              </div>

            </div>
             
              <div class="text-center copyright">
                <q-separator class="q-mb-sm" color="white"></q-separator>
                  <p>&copy; {{ new Date().getFullYear() }} Finance Department, Mizoram. All rights reserved.</p>
                  
              </div>
          </div>
      </q-footer>
   -->
   <!-- <q-footer>
  <div class="flex flex-wrap text-white column q-pa-sm bg-primary">
    <p class="text-h4 text-weight-bold">Finance Department (EC) GPF</p>

    <div>
      <div class="flex justify-between row q-mt-sm footer-small-font q-pb-sm">
        <div>
          <span style="font-size: 14px; font-weight: 600; color: #fdffff;">SERVICES</span>
          <br />
          <div class="q-mt-xs" style="font-size: 12px;">
            <router-link style="text-decoration: none; color: #fdffff;" to="/reports/">Reports</router-link>
          </div>
        </div>
        <div>
          <span style="font-size: 14px; font-weight: 600; color: #fdffff;">About</span>
          <br />
          <div class="q-mt-xs column" style="font-size: 12px;">
            <router-link style="text-decoration: none; color: #fdffff;" to="/pages/about">GPF</router-link>
            <a style="text-decoration: none; color: #fdffff;" href="https://finance.mizoram.gov.in/" target="_blank">Finance Department</a>
          </div>
        </div>
        <div  class="mr-5">
          <span style="font-size: 14px; font-weight: 600; color: #fdffff;">LEGAL</span>
          <br />
          <div class="q-mt-xs" style="font-size: 12px;">
            <router-link style="text-decoration: none; color: #fdffff; margin-left: 2px;" to="/pages/terms">Terms and Condition</router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="footerRows q-gutter-sm">
      <div style="display: flex; justify-content: flex-end; margin-bottom: 2px;" class="col-xs-2 col-sm-2 col-md-4">
        <img :src="'/msegs.svg'" alt="msegs">
      </div>
    </div>

    <div class="text-center copyright">
      <q-separator class="q-mb-xs" color="white"></q-separator>
      <p style="font-size: 12px;">&copy; {{ new Date().getFullYear() }} Finance Department, Mizoram. All rights reserved.</p>
    </div>
  </div>
</q-footer> -->
<q-footer class="q-pb-xs">
  <div class="flex flex-wrap text-white column q-pa-xs bg-primary">
    <p class="text-h5 text-weight-bold">Finance Department (EC) GPF</p>

    <div>
      <div class="flex justify-between row q-mt-xs footer-small-font q-pb-xs">
        <div>
          <span style="font-size: 14px; font-weight: 600; color: #fdffff;">SERVICES</span>
          <br />
          <div class="q-mt-xs" style="font-size: 12px;">
            <router-link style="text-decoration: none; color: #fdffff;" to="/reports/">Reports</router-link>
          </div>
        </div>
        <div>
          <span style="font-size: 14px; font-weight: 600; color: #fdffff;">About</span>
          <br />
          <div class="q-mt-xs column" style="font-size: 12px;">
            <router-link style="text-decoration: none; color: #fdffff;" to="/pages/about">GPF</router-link>
            <a style="text-decoration: none; color: #fdffff;" href="https://finance.mizoram.gov.in/" target="_blank">Finance Department</a>
          </div>
        </div>
        <div  class="mr-5">
          <span style="font-size: 14px; font-weight: 600; color: #fdffff;">LEGAL</span>
          <br />
          <div class="q-mt-xs" style="font-size: 12px;">
            <router-link style="text-decoration: none; color: #fdffff; margin-left: 2px;" to="/pages/terms">Terms and Condition</router-link>
          </div>
        </div>
      </div>
    </div>

    <div class="mr-5 footerRows q-gutter-xs">
      <div style="display: flex; justify-content: flex-end; margin-bottom: 1px;" class="col-xs-2 col-sm-2 col-md-4">
        <img :src="'/msegs.svg'" alt="msegs">
      </div>
    </div>

    <div class="text-center copyright">
      <q-separator class="q-mb-xs" color="white"></q-separator>
      <p style="font-size: 14px;">&copy; {{ new Date().getFullYear() }} Finance Department, Mizoram. All rights reserved.</p>
    </div>
  </div>
</q-footer>

      
      
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
        console.log('userID' +userId.value)
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
const isAuthenticated = () => {

  //check if a token exists in localStorage
  const token = localStorage.getItem('token');
  return !!token; // Return true if token exists, false otherwise
};



onMounted(() => {
  isLoggedIn.value = isAuthenticated();
});
</script>