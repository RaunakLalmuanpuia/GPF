<template>
    <QuasarLayout>
    <!-- <div class="q-ma-lg"> -->
        <div class="mb-2 ml-4">
            <div >
                <q-btn label="back" icon="arrow_back" unelevated class="q-py-md" @click="router.go(-1)"></q-btn>
                <h4 class="text-center invoice__title q-ma-md">Edit GPF</h4>
            </div>
           
        </div>
        <!-- {{ form }} -->
        <!-- Entry_info -->
        <div class="card__content q-pa-md">
                <div class="card__content--header">
                    <div class="flex flex-wrap justify-center row q-gutter-md">
                        <!-- <p class="my-1">Name of Fund</p> -->
                        <q-input clearable v-model="form.gpf_name" type="text" label="Name of Fund" class=" col-md-8 col-lg-3" outlined />
                        <!-- <p class="my-1">File Number</p> -->
                        <!-- <input v-model="fileNumber" type="text" class="input col-md-4"> -->
                        <q-input clearable v-model="form.file_number" type="text" label="File Number" class=" col-md-8 col-lg-3"
                            outlined />
                        <!-- <p class="my-1">Department</p>
                        <input v-model="department" type="text" class="input col-md-4"> -->
                        <!-- <q-input clearable v-model="form.from_deparment" type="text" label="Department" class=" col-md-8 col-lg-3"
                            outlined /> -->

                            <!-- <q-select v-model="form.department_id" :options="formattedSignatory" label="Department"
                                emit-value map-options use-input input-debounce="0" clearable filled
                                class=" col-md-8 col-lg-3" /> -->

                                <q-select v-if="form.departments" v-model="form.departments.name" :options="options" label="Department"
                                emit-value map-options use-input input-debounce="0" clearable filled
                                class=" col-md-8 col-lg-3"  @filter="filterFn"    @update:model-value="updateDepartment"/>
                        <!-- <p class="my-1">Designation</p>
                        <input v-model="designation" type="text" class="input col-md-4"> -->
                        <q-input clearable v-model="form.from_designation" type="text" label="Designation" class=" col-md-8 col-lg-3"
                            outlined />


                            <q-input class=" col-md-8 col-lg-3" filled v-model="form.date" mask="date" :rules="['date']">
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
                       
                        <!-- <select v-model="selectedSignatory" class="input dropdown col-md-8 col-lg-3">
                            <option disabled value="">Select a signatory</option>
                            <option v-for="(item, i) in signatory" :key="item.id" :value="item.id">
                                {{ i + 1 }}. {{ item.name }} {{ item.designation }}
                            </option>
                        </select> -->

                        <q-select v-model="form.signatory_id" :options="formattedSignatory" label="Select Signatory"
                                emit-value map-options use-input input-debounce="0" clearable filled
                                class=" col-md-8 col-lg-3" />
                        <!-- <p class="my-1">Signatory</p> -->
                       
                    </div>


                </div>
                <br><br>

            </div>
        <!-- Individual info -->
        <q-card flat bordered class="my-card">
                    <q-card-section>
                        <div class="text-center text-h6">Individual Informations</div>
                    </q-card-section>
                    
                    <q-card-section class="q-pt-none">
                        <div v-if="form.individual_infos">
                            <!-- Loop through individualInfoLines and render each line -->
                            <div v-for="(individualInfo, index) in form.individual_infos" :key="index" 
                                class="flex flex-wrap justify-center row q-gutter-md">
                                <!-- Individual info input fields -->
                                <q-input filled v-model="individualInfo.name" label="Name" class=" col-md-8 col-lg-3" />
                            
                                <q-input filled v-model="individualInfo.designation" label="Designation" class=" col-md-8 col-lg-3"/>
                                
                                <q-input filled v-model="individualInfo.account" label="GPF Account" class=" col-md-8 col-lg-3"/>
                                
                                <q-input filled v-model="individualInfo.amount" label="Amount" class=" col-md-8 col-lg-3"/>
                                
                                <q-input filled v-model="individualInfo.phone" label="Mobile" class=" col-md-8 col-lg-3"/>
                                
                                <q-select v-model="individualInfo.status" :options="statusOptions" label="Select Status"
                                emit-value map-options use-input input-debounce="0" clearable filled
                                class=" col-md-8 col-lg-3" />
                                    <!-- Delete Individual Info -->
                                    <q-btn class="remove-button col-md-4 col-lg-3 mb-7"
                                @click="deleteIndividual(individualInfo.id, index)">Remove</q-btn>
                                <!-- <button class="remove-button" @click="deleteIndividual(individualInfo.id, index)">Remove</button> -->
                            </div>
                        </div>
                        
                    </q-card-section>
                    <q-separator inset />

                    <div class="text-center" style="margin-top: 10px;">
                        <q-card-section>
                        <q-btn class="text-center" @click="addNewIndividualInfoLine" label="Add New Individual"/>
                    </q-card-section>
                    </div>
                   
        </q-card>
         <!-- Buttons -->
        <div class="text-center" style="margin-top: 40px; margin: 20px;">
           
                <q-btn color="primary" text-color="black" label="Print Aprroval" @click="OnPrint(form.id)"/>
                <q-btn color="primary" class="ml-4" text-color="black" label="Save"  @click="onEdit(form.id)"/>
        </div>
   

    
</QuasarLayout>
</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
let signatory = ref([]);
let department = ref([]);
let selectedDepartment = ref('');

let form = ref({
    id: ''
})

const props = defineProps({
    id:{
        type:String,
        default:''
    }
})
const formattedSignatory = ref();
const statusOptions = ['Approved', 'Rejected', 'Pending']
const departmentChanged = ref(false);


onMounted(async () =>{
    getGpf()
    getsignatory()
    getDepartment()
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
        formattedSignatory.value = response.data.signatory.map(item => ({label: item.name + ' / ' + item.designation, value: item.id}));
        // console.log(formattedSignatory.value);
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
    //   department: form.value.from_deparment,
    // department:  selectedDepartmentId.value,
    department: departmentChanged.value ? selectedDepartmentId.value : form.value.department_id,  
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
const getDepartment = async () => {
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

        const response = await axios.get('/api/department', config);
        department.value = response.data.department;
        // console.log(department.value);
        // formattedSignatory.value = response.data.signatory.map(item => ({label: item.name + ' / ' + item.designation, value: item.id}));
        // console.log(formattedSignatory.value);
    } catch (error) {
        console.error('Error fetching signatory:', error);
    }
};

const options = ref(department.value.map(department => department.name));
const selectedDepartmentId = ref(null);

const filterFn = (val, update) => {
  if (val === '') {
    update(() => {
      options.value = department.value.map(department => department.name);
    });
    return;
  }

  update(() => {
    const needle = val.toLowerCase();
    options.value = department.value.filter(department => department.name.toLowerCase().includes(needle)).map(department => department.name);
  });
};

const updateDepartment  = (name) => {
  const selectedDepartment = department.value.find(department => department.name === name);
  if (selectedDepartment) {
    selectedDepartmentId.value = selectedDepartment.id;
    departmentChanged.value = true;
  } else {
    selectedDepartmentId.value = null;
    departmentChanged.value = false;
  }
};
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