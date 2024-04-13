<template>
    <QuasarLayout>
        <!-- <p>Dashboard</p> -->
        <!-- <q-btn
                        label="GPF"
                        color="primary"
                        class="q-mt-md"
                        @click="Gpf()"
                    /> -->
                    <!-- <div class="q-pa-md">
                        <q-carousel
                        v-model="slide"
                        transition-prev="slide-right"
                        transition-next="slide-left"
                        animated
                        control-color="primary"
                        class="rounded-borders"
                        >
                        <q-carousel-slide name="style" class="column no-wrap flex-center">
                            <q-icon name="style" color="primary" size="56px" />
                            <div class="q-mt-md text-center">
                            {{ lorem }}
                            </div>
                        </q-carousel-slide>
                        <q-carousel-slide name="tv" class="column no-wrap flex-center">
                            <q-icon name="live_tv" color="primary" size="56px" />
                            <div class="q-mt-md text-center">
                            {{ lorem }}
                            </div>
                        </q-carousel-slide>
                        <q-carousel-slide name="layers" class="column no-wrap flex-center">
                            <q-icon name="layers" color="primary" size="56px" />
                            <div class="q-mt-md text-center">
                            {{ lorem }}
                            </div>
                        </q-carousel-slide>
                        <q-carousel-slide name="map" class="column no-wrap flex-center">
                            <q-icon name="terrain" color="primary" size="56px" />
                            <div class="q-mt-md text-center">
                            {{ lorem }}
                            </div>
                        </q-carousel-slide>
                        </q-carousel>

                        <div class="row justify-center">
                        <q-btn-toggle
                            glossy
                            v-model="slide"
                            :options="[
                            { label: 1, value: 'style' },
                            { label: 2, value: 'tv' },
                            { label: 3, value: 'layers' },
                            { label: 4, value: 'map' }
                            ]"
                        />
                        </div>
                    </div> -->

                    
                    <!-- <div class="q-pa-md row items-middle q-gutter-md">
                        <div class="px-20">
                            <q-card class="my-card">
                                <div class="px-3 text-h6">
                                    Check Gpf Status
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
                    </div> -->
                    <div v-if="showOtpCard" class="flex justify-center items-center ">
                        <div class="w-full max-w-xl ml-10">
                        <div class="q-pa-md row items-middle q-gutter-md">
                            <div class="px-20">
                            <q-card class="my-card">
                                <!-- <q-img src="https://cdn.quasar.dev/img/parallax2.jpg">
                                </q-img> -->
                                <div class="px-3 text-h6">
                                Check Gpf Status
                                </div>  
                                <q-card-section>
                                <q-input rounded filled v-model="phoneNumber" label="Phone Number" />
                                <!-- {{ lorem }} -->
                                </q-card-section>
                                <q-card-section v-if="showOTP">
                                <!-- {{ lorem }} -->
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

                    <div v-if="showTable">
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
                                <!-- <td>{{ info.entry_info.from_designation }}</td> -->
                                <td>{{ info.entry_info.signatory.name }}</td>
                                <td>{{ info.entry_info.signatory.designation }}</td>
                            </tr>
                        </tbody>
                        </table>
                        <div>
                            <q-btn flat @click="searchNew()">Search New</q-btn>
                        </div>
                    </div>

                    

    </QuasarLayout>

</template>
<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { useRouter } from 'vue-router';
import {ref} from 'vue'

const router = useRouter();

// const lorem = ref('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')

// const Gpf =async() => {
//     router.push('/gpf')
// }
const slide = ref('style')
const showOtpCard = ref(true);
const phoneNumber = ref('');
const otp = ref('');
const showOTP = ref(false);
const showResendButton = ref(false);
const showSendButton = ref(true);
const showSearchButton = ref(false);
const showTable = ref(false);

let form = ref({id:''})

// const sendOTP = () => {
//   // Logic to send OTP
//   // Prepare data for entry_info
//   const otpData = {
//             phoneNumber: phoneNumber.value,
//         };
//     console.log(otpData.phoneNumber);
//         // Make API request to save the entry
//     axios.post('/api/send_otp', otpData);

//     phoneNumber.value = '';
//     // Show the OTP input field and resend button
//     showOTP.value = true;
//     showResendButton.value = true;
//     // Hide the send OTP button
//     showSendButton.value = false;
//     showSearchButton.value = true;
// };
const sendOTP = () => {
  // Prepare data for sending OTP
  const otpData = {
    phone_number: phoneNumber.value,
    send_otp: 'true' // Include the send_otp field
  };
  
  // Make API request to send OTP
  axios.post('/api/send_otp', otpData)
    .then(response => {
      console.log(response.data.message);
      // Show the OTP input field and resend button
      showOTP.value = true;
      showResendButton.value = true;
      // Hide the send OTP button
      showSendButton.value = false;
      showSearchButton.value = true;
    })
    .catch(error => {
      console.error('Failed to send OTP:', error);
      // Handle error if necessary
    });
};
const resendOTP = () => {
  // Logic to resend OTP
};
const search = () => {
  // Logic to search
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
};
</script>