<template>
    <q-layout view="lHh Lpr lff">
  
      <q-header elevated class="text-white bg-primary" height-hint="98">
        <q-toolbar>
          <q-btn v-if="isLoggedIn" dense flat round icon="menu" @click="toggleLeftDrawer" />
            
          <q-toolbar-title>
            <q-avatar>
              <img src="https://upload.wikimedia.org/wikipedia/commons/c/c6/Ashok_Emblem_svg.svg">
            </q-avatar>
            Finance Department (EC)
          </q-toolbar-title>
          <!-- <q-btn-dropdown
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
                </q-btn-dropdown> -->
        </q-toolbar>
  
        <q-tabs align="left">
          <!-- <q-route-tab to="/gpf" label="GPF" /> -->
          <!-- <q-route-tab to="/page2" label="Page Two" /> -->
          <!-- <q-route-tab to="/logout" label="Logout" />
           -->
          <!-- <q-route-tab v-if="isLoggedIn" @click="gpf()" :class="{ 'bg-green-500': $route.path === '/gpf' }" label="GPF"/>
          <q-route-tab v-if="isLoggedIn" @click="individual()" :class="{ 'bg-green-500': $route.path === '/individual' }" label="Individuals"/>
          <q-route-tab v-if="isLoggedIn" @click="departments()" :class="{ 'bg-green-500': $route.path === '/departments' }" label="Departments"/>
          <q-route-tab v-if="isLoggedIn" @click="reports()" :class="{ 'bg-green-500': $route.path === '/report' }" label="Report"/> -->
          <!-- <q-route-tab v-if="isLoggedIn" @click="profie()" :class="{ 'bg-green-500': $route.path === '/profile' }" label="Profile"/>
          <q-route-tab v-if="isLoggedIn" @click="logout()" label="Logout"/> -->
         
         
          
        </q-tabs>
      </q-header>
  
      <!-- <q-drawer v-model="leftDrawerOpen" side="left" overlay bordered>
       
      </q-drawer> -->
      <q-drawer v-if="isLoggedIn" show-if-above v-model="leftDrawerOpen" side="left" bordered>
      <!-- drawer content -->
      <q-scroll-area class="fit">
        <div class="ml-3  mt-3">

          <q-list>
           
            
              <!-- end of admin -->
              <q-item  clickable @click="gpf()" v-ripple class="no-padding" :class="{ 'bg-green-500': $route.path === '/gpf' }">
          
  
                <div class="mt-3 ml-5 row">
                  <q-item-section avatar>
                  <i class="q-icon material-icons-outlined">
                    article
                  </i>
                  </q-item-section>
                  
                  <q-item-section>
                    GPF
              </q-item-section>
                </div>
  
              </q-item>

              <q-item clickable @click="individual()" v-ripple class="no-padding" :class="{ 'bg-green-500': $route.path === '/individual' }">
  
                <div class="mt-3 ml-5 row">
                  <q-item-section avatar>
                  <i class="q-icon material-icons-outlined">
                    person
                  </i>
                  </q-item-section>
                  
                  <q-item-section>
                    INDIVIDUALS
              </q-item-section>
                </div>

              </q-item>

              <hr>
              <q-expansion-item
                :content-inset-level="0.5"
                  expand-separator
                  icon="settings"
                
                  label="System"
                  caption=""
                  >
    
                  <q-item clickable @click ='reports()' v-ripple class="no-padding" :class="{ 'bg-green-500': $route.path === '/report' }" >
                    
                      <div class="mt-3 ml-5 row">
                        <q-item-section avatar>
                        <i class="q-icon material-icons-outlined">
                          summarize
                        </i>
                        </q-item-section>
                        <q-item-section>
                          Reports
                    </q-item-section>
                      </div>
                  
                  </q-item>
                  
    
                  <q-item clickable @click="departments()" v-ripple class="no-padding" :class="{ 'bg-green-500': $route.path === '/departments' }">
                      
                      <div class="mt-3 ml-5 row">
                          <q-item-section avatar>
                          <i class="q-icon material-icons-outlined">
                          house
                          </i>
                          </q-item-section>
                          <q-item-section>
                              <q-item-label>Departments</q-item-label>
                              <!-- <q-item-label caption>And transferred</q-item-label> -->

                          </q-item-section>
                      </div>
                    
                  </q-item>

                  <q-item clickable  v-ripple class="no-padding">
                      
                      <div class="mt-3 ml-5 row">
                          <q-item-section avatar>
                          <i class="q-icon material-icons-outlined">
                          group
                          </i>
                          </q-item-section>
                          <q-item-section>
                              <q-item-label>Users</q-item-label>
                              <!-- <q-item-label caption>And transferred</q-item-label> -->

                          </q-item-section>
                      </div>
                    
                  </q-item>

              </q-expansion-item>
              
           

         

            <q-item-label header>System</q-item-label>

            <q-item clickable @click="profie()" v-ripple class="no-padding">
              
                  <div class="row mt-3 ml-5">
                    <q-item-section avatar>
                    <i class="q-icon material-icons-outlined">
                      manage_accounts
                    </i>
                    </q-item-section>
                    <q-item-section>
                      User Profile
                    </q-item-section>
                  </div>
                
              </q-item>
              
              <q-item clickable  @click="logout()" v-ripple class="no-padding mb-10">
              
                <div class="row mt-3 ml-5">
                  <q-item-section avatar>
                  <i class="q-icon material-icons-outlined">
                    logout
                  </i>
                  </q-item-section>
                  <q-item-section>
                    Logout
                    
                  </q-item-section>
                </div>
              
            </q-item>
          </q-list>
        </div>
      </q-scroll-area>
    </q-drawer>
  
      <q-page-container>
        <slot />
      </q-page-container>
      
      
      <!-- <div class="flex items-center justify-center bg-primary"> -->
        
      
        <q-footer>
        
          
          
            
          
          <div class="flex column flex-wrap q-pa-md bg-primary text-white" >
            
            <p class=" text-h4 text-weight-bold ">Finance Department (EC) GPF</p>


            <div>
              <div
                  class="flex row q-mt-md footer-small-font justify-between q-pb-md"
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

                    <div
                      class="q-mt-sm"
                      style="font-size: 14px; font-weight: normal !important"
                    >
                    <a
                        style="text-decoration: none; color: #fdffff"
                        href="https://gpf.msegs.in/"
                        target="_blank"

                        >GPF Online</a
                      >

                      <br />
                    </div>

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

              <!-- <div class="col-xs-2 col-sm-2 col-md-4">
                <q-avatar>
                   <img src="https://upload.wikimedia.org/wikipedia/commons/c/c6/Ashok_Emblem_svg.svg">
                </q-avatar>
              </div> -->

              <div style="display: flex; justify-content: flex-end; margin-bottom: 4px;" class=" col-xs-2 col-sm-2 col-md-4" >
               
                  <!-- <img src="/public/msegs.svg" alt="asd"/> -->
                 
                  <img :src="'/msegs.svg'"  alt="msegs">
                
              </div>

            </div>
             
              <div class="copyright text-center">
                <q-separator class="q-mb-sm" color="white"></q-separator>
                  <p>&copy; {{ new Date().getFullYear() }} Finance Department, Mizoram. All rights reserved.</p>
                  
              </div>
          </div>
        </q-footer>
  
      <!-- </div> -->
      
      
    </q-layout>
  </template>
  

<script setup>
  import axios from 'axios';
  import { ref, onMounted, watch  } from 'vue'
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
const reports = async () => {
  router.push('/report')
}
const isAuthenticated = () => {

  //check if a token exists in localStorage
  const token = localStorage.getItem('token');
  return !!token; // Return true if token exists, false otherwise
};


watch(router.currentRoute, () => {
    leftDrawerOpen.value = false;
  });

onMounted(() => {
  isLoggedIn.value = isAuthenticated();
});
</script>