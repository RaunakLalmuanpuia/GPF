<template>
    <QuasarLayout>

        <!-- Error message section -->
        <div v-if="showError" class="error-message">
            {{ errorMessage }}
        </div>

        <div>

            <div>
                <div>
                    <h4 class="text-center invoice__title q-ma-md">New GPF</h4>
                </div>

            </div>

            <div class="card__content q-pa-md">
                <div class="card__content--header">
                    <div class="flex flex-wrap justify-center row q-gutter-md">
                        <!-- <p class="my-1">Name of Fund</p> -->
                        <q-input clearable v-model="name" type="text" label="Name of Fund" class=" col-md-8 col-lg-3" outlined />
                        <!-- <p class="my-1">File Number</p> -->
                        <!-- <input v-model="fileNumber" type="text" class="input col-md-4"> -->
                        <q-input clearable v-model="fileNumber" type="text" label="File Number" class=" col-md-8 col-lg-3"
                            outlined />
                        <!-- <p class="my-1">Department</p>
                        <input v-model="department" type="text" class="input col-md-4"> -->
                        <q-input clearable v-model="department" type="text" label="Department" class=" col-md-8 col-lg-3"
                            outlined />
                        <!-- <p class="my-1">Designation</p>
                        <input v-model="designation" type="text" class="input col-md-4"> -->
                        <q-input clearable v-model="designation" type="text" label="Designation" class=" col-md-8 col-lg-3"
                            outlined />


                        <q-input clearable class=" col-md-8 col-lg-3" filled label="Date" v-model="date" mask="date"
                            :rules="['date']">
                            <template v-slot:append>
                                <q-icon name="event" class="cursor-pointer">
                                    <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                                        <q-date v-model="date">
                                            <div class="items-center justify-end row">
                                                <q-btn v-close-popup label="Close" color="primary" flat />
                                            </div>
                                        </q-date>
                                    </q-popup-proxy>
                                </q-icon>
                            </template>
                        </q-input>
                       
                        <select v-model="selectedSignatory" class="input dropdown col-md-8 col-lg-3">
                            <option disabled value="">Select a signatory</option>
                            <option v-for="(item, i) in signatory" :key="item.id" :value="item.id">
                                {{ i + 1 }}. {{ item.name }} {{ item.designation }}
                            </option>
                        </select>
                        <!-- <p class="my-1">Signatory</p> -->

                    </div>


                </div>
                <br><br>

            </div>

            <div>
                <q-card flat bordered class="my-card">
                    <q-card-section>
                        <div class="text-center text-h6">Individual Informations</div>
                    </q-card-section>
                    
                    <q-card-section class="q-pt-none">
                        <div v-for="(individualInfo, index) in individualInfoLines" :key="index"
                            class="flex flex-wrap justify-center row q-gutter-md">
                            <!-- Loop through individualInfoLines and render each line -->

                            <!-- Individual info input fields -->
                            <q-input clearable filled v-model="individualInfo.name" label="Name"
                                class=" col-md-8 col-lg-3" />

                            <q-input clearable filled v-model="individualInfo.designation" label="Designation"
                                class=" col-md-8 col-lg-3" />

                            <q-input clearable filled v-model="individualInfo.account" label="GPF Account"
                                class=" col-md-8 col-lg-3" />

                            <q-input clearable filled v-model="individualInfo.amount" type="number" label="Amount"
                                class=" col-md-8 col-lg-3" />

                            <q-input clearable filled v-model="individualInfo.mobile" type="number" label="Mobile"
                                class=" col-md-8 col-lg-3" />

                            <q-select v-model="individualInfo.status" :options="statusOptions" label="Select Status"
                                emit-value map-options use-input input-debounce="0" clearable filled
                                class=" col-md-8 col-lg-3" />
                            <!-- Delete Individual Info -->
                            <q-btn class="remove-button col-md-4 col-lg-3 mb-7"
                                @click="removeIndividualInfoLine(index)">Remove</q-btn>
                        
                        </div>
                        
                    </q-card-section>

                    <q-separator inset />

                    <q-card-section>
                        <button class="text-center add-button float-center" @click="addNewIndividualInfoLine">Add New Individual</button>
                    </q-card-section>
                </q-card>
            </div>

            <div class="text-center" style="margin-top: 40px;">
                <div>
                    <q-btn label="Save" color="primary" @click="confirm = true" class="" style="width:200px" />
                </div>
            </div>

        </div>



        <br><br><br>
        <q-dialog v-model="confirm" persistent>
      <q-card>
        <q-card-section class="items-center row">
          <!-- <q-avatar icon="signal_wifi_off" color="primary" text-color="white" /> -->
          <span class="q-ml-sm">Are you sure you want to save?</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="Cancel" color="primary" v-close-popup />
          <q-btn flat label="Confirm" color="primary" v-close-popup @click="saveEntry()" />
        </q-card-actions>
      </q-card>
    </q-dialog>
    </QuasarLayout>

</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();
const confirm = ref(false);
// let form = ref([]);
let fileNumber = ref('');
let date = ref('');
let signatory = ref([]);
let amount = ref('');
let status = ref('');
let department = ref('');
let designation = ref('');
let name = ref('');
let individualInfoLines = ref([]);

let entryInfoId = ref('');

let selectedSignatory = ref('');
const statusOptions = ['Approved', 'Rejected', 'Pending']
// Define reactive variables
const showError = ref(false); // Controls the visibility of the error message
const errorMessage = ref(''); // Stores the error message text

onMounted(async () => {
    getsignatory()
    document.title = 'GPF - New'
})



// Function to add a new individual info line
const addNewIndividualInfoLine = () => {
    // Add a new line to the temporary array
    individualInfoLines.value.push({});
};

// Function to remove an individual info line
const removeIndividualInfoLine = (index) => {
    // Remove the line from the temporary array
    individualInfoLines.value.splice(index, 1);
};


const saveEntry = async () => {
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
            file_number: fileNumber.value,
            date: date.value,
            amount: amount.value,
            status: status.value,
            individual_infos: individualInfoLines.value,
            selectedSignatory: selectedSignatory.value,
            department: department.value,
            designation: designation.value,
            name: name.value
        };

        // Make API request to save the entry
        const response = await axios.post('/api/save_gpf', entryInfoData, config);
        // console.log(response.data.id);
        // Check if the response contains any errors
        if (response.data.error) {
            // Handle validation errors
            showError.value = true; // Show the error message
            errorMessage.value = response.data.error.message;
            console.log(response.data.error.message); // Set the error message text
            return;
        }
        // Redirect to the desired route after successful save
        router.push(`/gpf/show/${response.data.id}`);

        // Clear the form fields after successful save
        fileNumber.value = '';
        date.value = '';
        selectedSignatory.value = '';
        amount.value = '';
        status.value = '';
        department.value = '';
        name.value = '';
        individualInfoLines.value = [];
    } catch (error) {
        console.error('Error:', error);
        // Handle error here, such as displaying an error message to the user
    }
};

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


</script>

<style scoped>
.container {
    max-width: 1200px;
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
    /* padding: 8px 12px; */
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

.error-message {
    background-color: #ffcccc;
    color: #ff0000;
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
}

.error-text {
    margin: 0;
    font-weight: bold;
}
</style>