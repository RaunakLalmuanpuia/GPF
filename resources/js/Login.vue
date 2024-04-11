<template>
    <div class=" window-height window-width row justify-center items-center">
      <div class="column">
        <div class="row">
          <h5 class="text-h5 text-white q-my-md">Company & Co</h5>
        </div>
        <div class="row">
          <q-card square bordered class="q-pa-lg shadow-1">
            <q-card-section>
              <q-form class="q-gutter-md">
                <q-input square filled clearable v-model="dealing.email" type="email" label="Email" />
                <q-input square filled clearable v-model="dealing.password" type="password" label="Password" />
                <!-- <q-input square filled clearable v-model="otp" type="text" label="OTP" /> -->
              </q-form>
            </q-card-section>
            <q-card-actions class="q-px-md">
              <!-- <q-btn unelevated color="primary" size="lg" class="full-width" label="Send OTP" @click="sendOTP" /> -->
              <q-btn unelevated color="primary" size="lg" class="full-width" label="Login" @click="LoginData()" />
            </q-card-actions>
          </q-card>
        </div>
      </div>
    </div>
  </template> 
  
  <!-- <template>

    <div class="row">
    
    <div class="col-sm-4" >
     <h2 align="center"> Login</h2>
   
     <form @submit.prevent="LoginData">
     
   
     <div class="form-group" align="left">
       <label>Email</label>
       <input type="email" v-model="dealing.email" class="form-control"  placeholder="Email">
     </div>


    <div class="form-group" align="left">
    <label>Password</label>
    <input type="password" v-model="dealing.password" class="form-control"  placeholder="Password">
  </div>

     <button type="submit" class="btn btn-primary">Login</button>
     </form>
   </div>
   </div>

</template> -->
   
<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const dealing = ref({
  email: '',
  password: ''
});

const router = useRouter();

const LoginData = () => {
  axios.post("/api/login", dealing.value)
    .then(({ data }) => {
      console.log(data);
      try {
        if (data.status === true) {
          alert("Login Successfully");
          router.push('/gpf');
        } else {
          alert("Login failed");
        }
      } catch (err) {
        alert("Error, please try again");
      }
    });
};
</script>
