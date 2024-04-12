<template>
   <QuasarLayout>
     <div class="invoices">
        
        <div class="card__header">
            <div>
                <h2 class="invoice__title">New GPF</h2>
            </div>
            <div>
                
            </div>
        </div>

        <div class="card__content">
            <div class="card__content--header">
                <div>
                    <p class="my-1">File Number</p>
                    <input v-model="fileNumber" type="text" class="input"> 
                    <p class="my-1">Department</p>
                    <input v-model="department" type="text" class="input"> 
                    <p class="my-1">Designation</p>
                    <input v-model="designation" type="text" class="input"> 
                </div>
                <div>
                    <p class="my-1">Date</p> 
                    <!-- <input v-model="date" type="text" class="input">   -->
                    <!-- <q-date v-model="date" /> -->
                    <q-input filled v-model="date" mask="date" :rules="['date']">
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
                    <p class="my-1">Signatory</p> 
                    <div>
                      <select v-model="selectedSignatory" class="input dropdown">
                        <option disabled value="">Select a signatory</option>
                        <option v-for="(item, i) in signatory" :key="item.id" :value="item.id">
                          {{ i+1 }}. {{ item.name }} {{ item.designation }}
                        </option>
                      </select>
                      <p class="my-1">Name of Fund</p>
                      <input v-model="name" type="text" class="input"> 
                      <!-- <button @click="addSignatory(selectedSignatory)" class="add-button">
                        +
                      </button> -->
                    </div>
                      
                </div>
                <!-- <div>
                    <p class="my-1">Amount</p> 
                    <input v-model="amount" type="text" class="input"> 
                    <p class="my-1">Status</p> 
                    <input v-model="status" type="text" class="input"> 
                   
                </div> -->
            </div>
            <br><br>
            <h2>Individual Info</h2>
            <div class="container">
              <div class="header">
                  <p>Name</p>
                  <p>Designation</p>
                  <p>GPF Account</p>
                  <p>Amount</p>
                  <p>Mobile</p>
                  <p>Status</p>
              </div>

              <div class="individual-info-container">
                  <!-- Loop through individualInfoLines and render each line -->
                  <div v-for="(individualInfo, index) in individualInfoLines" :key="index" class="individual-info-line">
                      <!-- Individual info input fields -->
                      <!-- Use v-model to bind inputs to individualInfo properties -->
                      <input v-model="individualInfo.name" type="text" class="input" placeholder="Enter Name">
                      <input v-model="individualInfo.designation" type="text" class="input" placeholder="Enter Designation">
                      <input v-model="individualInfo.account" type="text" class="input" placeholder="Enter GPF Account">
                      <input v-model="individualInfo.amount" type="text" class="input" placeholder="Enter Amount">
                      <input v-model="individualInfo.mobile" type="text" class="input" placeholder="Enter Mobile">
                      <input v-model="individualInfo.status" type="text" class="input" placeholder="Enter Status">
                      <button class="remove-button" @click="removeIndividualInfoLine(index)">Remove</button>
                  </div>
              </div>

          <div class="button-container">
              <button class="add-button" @click="addNewIndividualInfoLine">Add New Individual</button>
          </div>
        </div>

        </div>
       
        <div class="card__header" style="margin-top: 40px;">
            <div>
                
            </div>
            <div>
              <q-btn color="white" text-color="black" label="Create GPF Account"  @click="saveEntry()"/>  
              
            </div>
        </div>
        
    </div>

    
    
    <br><br><br>
    
  </QuasarLayout>

</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();

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

let selectedSignatory = ref('');




onMounted(async () => {
  getsignatory()
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

// Function to save the entry and related individual info

// const saveEntry = async () => {
//   // console.log(selectedSignatory.value)
//   try {
//     // Prepare data for entry_info
//     const entryInfoData = {
//       file_number: fileNumber.value,
//       date: date.value,
//       amount: amount.value,
//       status: status.value,
//       individual_infos: individualInfoLines.value,
//       selectedSignatory: selectedSignatory.value,
//       department: department.value,
//       designation: designation.value,
//       name: name.value


//     };
//     axios.post('/api/save_gpf', entryInfoData);
//     router.push('/')
//     // console.log('Response:', response.data);

//     fileNumber.value = ''; // Clear the fields after successful save
//     date.value = '';
//     selectedSignatory.value = '';
//     amount.value = '';
//     status.value = '';
//     selectedSignatory.value = '';
//     department.value = '';
//     name.value = '';
//     // alert('saved Succesfully');
//     individualInfoLines.value = [];
//   } catch (error) {
//     console.error('Error:', error);
//   }
// };
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
        await axios.post('/api/save_gpf', entryInfoData, config);

        // Redirect to the desired route after successful save
        router.push('/gpf');

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

//get signatory
// const getsignatory = async  () => {
//   let response = await axios.get('/api/signatory')
//   // console.log('signatory', response)
//   signatory.value = response.data.signatory
// }
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