<template>
    <QuasarLayout>
    <div>
        <div class="mb-2 ml-4">
            <div>
                <h4 class="invoice__title">Edit GPF</h4>
            </div>
           
        </div>
        <!-- Entry_info -->
        <div class="flex">
            <div class="flex flex-grow ml-4 mr-4 column">
                <div>

                    <!-- <p class="my-1">Name of Fund</p>
                      <input v-model="form.gpf_name" type="text" class="input">  -->

                    <q-input outlined  v-model="form.gpf_name" label="Name of Fund" class="q-py-md" />
                    <q-input outlined  v-model="form.file_number" label="File Number" class="q-py-md"/>

                    <q-input outlined  v-model="form.from_deparment" label="Department" class="q-py-md" />

                    <q-input outlined  v-model="form.from_designation" label="Designation" class="q-py-md" />
                </div>
            </div>

            <div class="flex-grow mr-20">
                <div>
                    <p class="my-1">Date</p> 
                    <!-- <input v-model="form.date" type="text" class="input">   -->
                    
                    <q-input filled v-model="form.date" mask="date" :rules="['date']">
                      <template v-slot:append>
                        <q-icon name="event" class="cursor-pointer">
                          <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                            <q-date v-model="form.date">
                              <div class="items-center justify-end row">
                                <q-btn v-close-popup label="Close" color="primary" flat />
                              </div>
                            </q-date>
                          </q-popup-proxy>
                        </q-icon>
                      </template>
                    </q-input>


                    <p class="my-1">Signatory</p> 
                    <div>
                    <p v-if="form.signatory">{{ form.signatory.name }} / {{ form.signatory.designation }} </p>
                      <select v-model="form.signatory_id" class="input dropdown">
                        <option disabled value="">Select a Signatory</option>
                        <option v-for="(item, i) in signatory" :key="item.id" :value="item.id" :selected="item.id === form.signatory.name">
                          {{ i+1 }}. {{ item.name }} {{ item.designation }} {{ item.deleted_at ? '(Deleted)' : '' }}
                        </option>
                      </select>


                    
                   
                    </div>
                      
                </div>
            </div>
        </div>
        <!-- Individual info -->
        <div class="items-start q-pa-md row q-gutter-md">
            <q-card flat bordered class="my-card">
            <q-card-section>
                <div class="text-h6">Individual Informations</div>
            </q-card-section>

            <q-card-section class="q-pt-none">
                <div v-if="form.individual_infos" class="individual-info-container">
                  <!-- Loop through individualInfoLines and render each line -->
                  <div v-for="(individualInfo, index) in form.individual_infos" :key="index" class="individual-info-line">
                      <!-- Individual info input fields -->
                      <q-input filled v-model="individualInfo.name" label="Name" />
                
                      <q-input filled v-model="individualInfo.designation" label="Designation"/>
                     
                      <q-input filled v-model="individualInfo.account" label="GPF Account"/>
                     
                      <q-input filled v-model="individualInfo.amount" label="Amount"/>
                     
                      <q-input filled v-model="individualInfo.phone" label="Mobile"/>
                    
                      <!-- <q-input filled v-model="individualInfo.status" label="Status"/> -->
                      <select v-model="individualInfo.status" class="input">
                                <option value="" disabled selected>Select Status</option>
                                <option value="Approved">Approved</option>
                                <option value="Rejected">Rejected</option>
                                <option value="Pending">Pending</option>
                            </select>
                        <!-- Delete Individual Info -->
                      <button class="remove-button" @click="deleteIndividual(individualInfo.id, index)">Remove</button>
                  </div>
              </div>
            </q-card-section>

            <q-separator inset />

            <q-card-section>
                <button class="add-button" @click="addNewIndividualInfoLine">Add New Individual</button>
            </q-card-section>
            </q-card>
        </div>
         <!-- Buttons -->
        <div class="flex" style="margin-top: 40px;">
            <div class="px-2">
                <q-btn color="white" text-color="black" label="Print Aprroval" @click="OnPrint(form.id)"/>
                <!-- <a class="btn btn-secondary" @click="OnPrint(form.id)">Print Aprroval</a> -->
            </div>
            <!-- <div>
                <q-btn color="white" text-color="black" label="Print Rejected"  @click="OnPrint(form.id)"/>
                
            </div> -->
            <div>
                <q-btn color="white" text-color="black" label="Save"  @click="onEdit(form.id)"/>
                <!-- <a @click="onEdit(form.id)" class="btn btn-secondary">
                    Save
                </a> -->
            </div>
        </div>
    </div>
</QuasarLayout>
</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
let signatory = ref([]);


let form = ref({
    id: ''
})

const props = defineProps({
    id:{
        type:String,
        default:''
    }
})

onMounted(async () =>{
    getGpf()
    getsignatory()
    document.title = 'GPF - Edit'
})



const getsignatory = async () => {
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

        const response = await axios.get('/api/signatory', config);
        signatory.value = response.data.signatory;
    } catch (error) {
        console.error('Error fetching signatory:', error);
    }
};

const addNewIndividualInfoLine = () => {
    form.value.individual_infos.push({
        name: '',
        designation: '',
        account: '',
        amount: '',
        phone: '',
        status: ''
    });
};


// const getGpf = async () => {
//     let response = await axios.get(`/api/edit_gpf/${props.id}`)
//     // console.log('form', response.data.entry_info);
//     form.value = response.data.entry_info
// }
const getGpf = async () => {
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
        let response = await axios.get(`/api/edit_gpf/${props.id}`, config);

        // Set the form data after successful fetch
        form.value = response.data.entry_info;
    } catch (error) {
        console.error('Error:', error);
        // Handle error here, such as redirecting the user to the login page
    }
};


const deleteIndividual = (id, i) => {
    // form.value.individual_infos.split(i,1)
    form.value.individual_infos.splice(i, 1); // Remove 1 element at index i
    if( id != undefined){
        axios.get('/api/delete_individual/' +id)
    }
}

const onEdit = (id) => {
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
      file_number: form.value.file_number,
      date: form.value.date,
      amount: form.value.amount,
      status: form.value.status,
      individual_infos: form.value.individual_infos,
      selectedSignatory: form.value.signatory_id,
      department: form.value.from_deparment,
      designation: form.value.from_designation,
      name: form.value.gpf_name
    };
    axios.post(`/api/update_gpf/${form.value.id}`, entryInfoData, config);
    router.push(router.currentRoute.value.path)
    alert('success');
  } catch (error) {
    console.error('Error:', error);
  }
}

const OnPrint = (id) => {
    router.push('/gpf/print/'+id);
}
</script>



<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
}

.header {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 10px;
    padding: 10px;
    background-color: #f2f2f2;
}

.individual-info-container {
    padding: 10px;
}

.individual-info-line {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 10px;
    margin-bottom: 10px;
}

.input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.remove-button {
    background-color: #ff5555;
    color: white;
    border: none;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
}

.remove-button:hover {
    background-color: #ff0000;
}

.add-button {
    background-color: #4caf50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
}

.add-button:hover {
    background-color: #45a049;
}

</style>