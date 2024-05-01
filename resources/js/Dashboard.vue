<template>
  <GuestLayout>

    <!-- <p>Dashboard</p> -->
    <!-- <q-btn
                        label="GPF"
                        color="primary"
                        class="q-mt-md"
                        @click="Gpf()"
                    /> -->

    <div class="flex">

      <!-- div 1 -->
      <div class="flex-grow q-pa-md">

        <q-carousel v-model="slide" transition-prev="slide-right" transition-next="slide-left" swipeable animated
          control-color="amber" navigation padding arrows height="300px" class="bg-grey-9 shadow-2 rounded-borders">
          <q-carousel-slide :name="1" class="column no-wrap">
            <div class="items-center justify-start row fit q-gutter-xs q-col-gutter no-wrap">
              <q-img class="rounded-borders col-6 full-height" src="https://cdn.quasar.dev/img/mountains.jpg" />
              <q-img class="rounded-borders col-6 full-height" src="https://cdn.quasar.dev/img/parallax1.jpg" />
            </div>
          </q-carousel-slide>
          <q-carousel-slide :name="2" class="column no-wrap">
            <div class="items-center justify-start row fit q-gutter-xs q-col-gutter no-wrap">
              <q-img class="rounded-borders col-6 full-height" src="https://cdn.quasar.dev/img/parallax2.jpg" />
              <q-img class="rounded-borders col-6 full-height" src="https://cdn.quasar.dev/img/quasar.jpg" />
            </div>
          </q-carousel-slide>
          <q-carousel-slide :name="3" class="column no-wrap">
            <div class="items-center justify-start row fit q-gutter-xs q-col-gutter no-wrap">
              <q-img class="rounded-borders col-6 full-height" src="https://cdn.quasar.dev/img/cat.jpg" />
              <q-img class="rounded-borders col-6 full-height" src="https://cdn.quasar.dev/img/linux-avatar.png" />
            </div>
          </q-carousel-slide>
          <q-carousel-slide :name="4" class="column no-wrap">
            <div class="items-center justify-start row fit q-gutter-xs q-col-gutter no-wrap">
              <q-img class="rounded-borders col-6 full-height" src="https://cdn.quasar.dev/img/material.png" />
              <q-img class="rounded-borders col-6 full-height" src="https://cdn.quasar.dev/img/donuts.png" />
            </div>
          </q-carousel-slide>
        </q-carousel>

      </div>


      <!-- div2 -->
        
    </div>
    <div v-if="showOtpCard" class="flex items-center justify-center flex-grow ">
        <div class="w-full max-w-2xl mt-10 ml-10">
          <div class="">
            <div class="">
              <q-card class="my-card">
                <!-- <q-img src="https://cdn.quasar.dev/img/parallax2.jpg">
                                </q-img> -->
                <div class="px-3 text-h6">
                  Check Gpf Statusss
                </div>
                <q-card-section>
                  <q-input rounded filled v-model="phoneNumber" label="Phone Number" />


                </q-card-section>
                <q-card-section v-if="showOTP">

                  <q-input rounded filled v-model="otp" label="OTP" />
                </q-card-section>

                <q-card-actions>
                  <q-btn flat v-if="showResendButton" @click="resendOTP">Resend OTP</q-btn>
                  <q-btn flat v-if="showSendButton" @click="sendOTP">Send OTP</q-btn>
                  <q-btn flat v-if="showSearchButton" @click="search">Search</q-btn>
                </q-card-actions>
              </q-card>
            </div>
          </div>
        </div>
      </div> 

    <!-- <div v-if="showOtpCard" class="flex flex-col items-center w-screen h-auto md:justify-center md:flex-row md:h-75 lg:h-auto z-5 bg-green">
                <div class="flex flex-col items-center justify-center w-4/5 my-2 bg-white rounded-lg md:w-1/3 lg:w-2/6 xl:w-1/3 md:my-4 lg:my-6 md:ml-12 lg:ml-20 sm:h-38 md:h-52 xl:h-62">
                    <div class="px-5 py-2 text-xl font-bold text-textblue"> Track GPF </div>
                    
                    <div class="w-4/5 border rounded-lg mb-7">
                        <label class="absolute px-2 py-2 text-xs font-bold text-blue-500">Phone No</label>
                        <input rounded filled v-model="phoneNumber" class="w-full px-2 pt-6 pb-1 text-gray-900 rounded-lg focus:outlined "/>
                    </div>
                    <div v-if="showOTP" class="w-4/5 border rounded-lg mb-7">
                        <label class="absolute px-2 py-2 text-xs font-bold text-blue-500">OTP</label>
                        <input rounded filled v-model="otp" class="w-full px-2 pt-6 pb-1 text-gray-900 rounded-lg focus:outlined "/>
                    </div>
                    
                    <div class="flex-col items-center justify-center w-full">
                        <button v-if="showSendButton" @click="sendOTP" type="submit">
                            <label class="px-5 py-2 text-white rounded-lg bg-blue hover:bg-red-500 hover:outline-none focus:outline-none">Send OTP</label>
                        </button>
                        <button  v-if="showResendButton" @click="resendOTP" type="submit" class="px-2" >
                            <label class="px-5 py-2 text-white rounded-lg bg-blue hover:bg-red-500 hover:outline-none focus:outline-none">Resend OTP</label>
                        </button>
                        <button  v-if="showSearchButton" @click="search" type="submit">
                            <label class="px-5 py-2 text-white rounded-lg bg-blue hover:bg-red-500 hover:outline-none focus:outline-none">Search</label>
                        </button>
                    </div>
                </div>
    </div> -->
            
    <div v-if="showTable" style="padding-top: 20px; margin-right: 20px; margin-left: 20px; margin-bottom: 20px;">
      <table border="1">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Designation</th>
            <th>File Number</th>
            <th>Account No.</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Department</th>
            <!-- <th>From Designation</th> -->
            <th>Signatory Name</th>
            <th>Signatory Designation</th>
          </tr>
        </thead>
        <tbody>
          <!-- Loop through individualInfos -->
          <tr v-for="(info, index) in form" :key="info.id">
            <td>{{ index + 1 }}</td>
            <td>{{ info.name }}</td>
            <td>{{ info.phone }}</td>
            <td>{{ info.designation }}</td>
            <td>{{ info.entry_info.file_number }}</td>
            <td>{{ info.account }}</td>
            <td>{{ info.status }}</td>
            <td>{{ info.amount }}</td>
            <td>{{ info.entry_info.date }}</td>
            <td>{{ info.entry_info.from_deparment }}</td>
            <td>{{ info.entry_info.signatory.name }}</td>
            <td>{{ info.entry_info.signatory.designation }}</td>
          </tr>
        </tbody>
      </table>
      <div>
        <q-btn class="mt-4 ml-8 bg-green-5" flat @click="searchNew()">Search New</q-btn>
      </div>
    </div>



  </GuestLayout>

</template>
<script setup>
import GuestLayout from "@/Layout/GuestLayout.vue";
import { useRouter } from 'vue-router';
import { ref, onMounted } from 'vue'
import { useQuasar } from 'quasar';


onMounted(() => {
  document.title = 'GPF';
});
const q = useQuasar()

const router = useRouter();


const slide = ref('style')
const showOtpCard = ref(true);
const phoneNumber = ref('');
const otp = ref('');
const showOTP = ref(false);
const showResendButton = ref(false);
const showSendButton = ref(true);
const showSearchButton = ref(false);
const showTable = ref(false);

const showError = ref(false);

let form = ref({ id: '' })


const sendOTP = () => {
  // Prepare data for sending OTP
  if (!phoneNumber.value) {
    showError.value = true;
    q.notify({
      type: 'negative',
      message: 'Phone Number is required'
    });
    return;
  }
  const otpData = {
    phone_number: phoneNumber.value,
    send_otp: 'true' // Include the send_otp field
  };

  // Make API request to send OTP
  axios.post('/api/send_otp', otpData)
    .then(response => {
      // console.log(response.data.message);

      // Show the OTP input field and resend button
      showOTP.value = true;
      showResendButton.value = true;
      // Hide the send OTP button
      showSendButton.value = false;
      showSearchButton.value = true;

      q.notify({
        type: 'positive',
        message: 'OTP sent successfully'
      });
    })
    .catch(error => {
      // console.log(error.message)
      // console.error('Failed to send OTP:', error);
      q.notify({
        type: 'negative',
        message: error.response.data.message
      });
    });
};
const resendOTP = () => {
  const otpData = {
    phone_number: phoneNumber.value,
    send_otp: 'true' // Include the send_otp field
  };

  // Make API request to send OTP
  axios.post('/api/send_otp', otpData)
    .then(response => {
     
      q.notify({
        type: 'positive',
        message: 'OTP Resend successfully'
      });
    })
    .catch(error => {
      // console.log(error.message)
      // console.error('Failed to send OTP:', error);
      q.notify({
        type: 'negative',
        message: error.response.data.message
      });
    });
};
const search = () => {
  // Logic to search
  if (!otp.value) {
    showError.value = true;
    q.notify({
      type: 'negative',
      message: 'OTP is required'
    });
    return;
  }
  const otpValue = {
    phone_number: phoneNumber.value,
    otp: otp.value,
  };
  axios.post('/api/verify_otp_and_search', otpValue)
    .then(response => {
      console.log(response.data.individualInfos);
      // Show the OTP input field and resend button
      form.value = response.data.individualInfos;
      showTable.value = true;
      showOtpCard.value = false;

    })
    .catch(error => {
      console.error('Failed to send OTP:', error);
      // Handle error if necessary
    });
};
const searchNew = () => {
  form.value = ''
  showOtpCard.value = true;
  phoneNumber.value = '',
  otp.value = ''
  showTable.value = false;
  showOTP.value=false;
  showSendButton.value = true;
  showResendButton.value=false;
  showSearchButton.value=false;
  // showSendButton.value=false;
};
</script>