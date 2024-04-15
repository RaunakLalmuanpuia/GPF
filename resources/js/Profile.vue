<template>
    <QuasarLayout>

        <div class="flex justify-center items-center q-h-full" style="max-width: 1000px">
            <div>
                <h4 class="ml-4">Update Profile</h4>

            
                <div class="q-pa-md">

                <q-form
                @submit="onSubmit"
                @reset="onReset"
                class="q-gutter-md"
                >
                <q-input
                    filled
                    v-model="form.name"
                    label="Your name *"
                    hint="Name and surname"
                    lazy-rules
                    
                />

                <q-input
                    filled
                    v-model="form.email"
                    label="Email *"
                    lazy-rules
                />
                <q-input
                    filled
                    v-model="password"
                    label="Password *"
                    lazy-rules
                />

                <q-toggle v-model="accept" label="Please Confirm" />

                <div>
                    <q-btn label="Submit" type="submit" color="primary"/>
                    <q-btn label="Reset" type="reset" color="primary" flat class="q-ml-sm" />
                </div>
                </q-form>

                </div>
            </div>
        </div>

        
    </QuasarLayout>
</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";

import { onMounted,ref } from 'vue';
import { useRouter } from 'vue-router';
import { useQuasar } from 'quasar';

const q = useQuasar()
const router = useRouter();
const accept = ref(false)
onMounted(async () =>{
    getUser()
    document.title = 'GPF - Profile'
})

let form = ref({id:''})
const password = ref('')
const props = defineProps({
    id:{
        type:String,
        default:''
    }
})

const getUser = async () => {
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
        // Make API request to fetch GPF data
        let response = await axios.get('/api/user/', config);
        form.value = response.data.user;
        console.log(form.value);
    } catch (error) {
        console.error('Error:', error);
        // Handle error here, such as redirecting the user to the login page
    }
};

const onSubmit = async () => {
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
    // Prepare data for entry_info
    const entryInfoData = {
      id: form.value.id,
      name: form.value.name,
      email: form.value.email,
      password: password.value,
      
    };
    axios.post('/api/update/', entryInfoData, config)
    .then(response => {

      q.notify({
        type: 'positive',
        message: 'Profile Updated successfully'
      });
    })
    router.push(router.currentRoute.value.path)
  } catch (error) {
    console.error('Error:', error);
    q.notify({
        type: 'negetive',
        message: 'Failed to update Profile'
      });
  }

}
const onReset = () => {
    getUser()
    password.value = ''
}
</script>
