<template>
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
                </div>
                <div>
                    <p class="my-1">Date</p> 
                    <input v-model="date" type="text" class="input">  <!---->
                    <p class="my-1">Signatory</p> 
                   
                    <div>
                      <select v-model="selectedSignatory" class="input dropdown">
                        <option disabled value="">Select a signatory</option>
                        <option v-for="(item, i) in signatory" :key="item.id" :value="item.id">
                          {{ i+1 }}. {{ item.name }} {{ item.designation }}
                        </option>
                      </select>
                      <!-- <button @click="addSignatory(selectedSignatory)" class="add-button">
                        +
                      </button> -->
                    </div>
                      
                </div>
                <div>
                    <p class="my-1">Amount</p> 
                    <input v-model="amount" type="text" class="input"> 
                    <p class="my-1">Status</p> 
                    <input v-model="status" type="text" class="input"> 
                   
                </div>
            </div>
            <br><br>
            <p>Individual Info</p>
            <div class="table">

                <div class="table--heading2">
                    
                    
                    <p>Name</p>
                    <p>Designation</p>
                    <p>GPF Account</p>
                    <p>Amount</p>
                    <p>Mobile</p>
                
                </div>
    
                <div class="table">
                  <div class="table--heading2">
                    <!-- Heading columns -->
                  </div>
      <!-- Loop through individualInfoLines and render each line -->
                  <div v-for="(individualInfo, index) in individualInfoLines" :key="index" class="table--items2">
                    <!-- Individual info input fields -->
                    <!-- Use v-model to bind inputs to individualInfo properties -->
                    <!-- Provide a button to remove the line -->
                    <input v-model="individualInfo.name" type="text" class="input">
                    <p>
                      <input v-model="individualInfo.designation" type="text" class="input">
                    </p>
                    <p>
                      <input v-model="individualInfo.account" type="text" class="input">
                    </p>
                    <!-- <p>
                
                      $ {{ individualInfo.amount }}
                    </p> -->
                    <p>
                      <input v-model="individualInfo.amount" type="text" class="input">
                    </p>
                    <p>
                      <input v-model="individualInfo.mobile" type="text" class="input">
                    </p>
                    <p style="color: red; font-size: 24px;cursor: pointer;" @click="removeIndividualInfoLine(index)">
                      &times;
                    </p>
                  </div>
               </div>
                <div style="padding: 10px 30px !important;">
                    <button class="btn btn-sm btn__open--modal" @click="addNewIndividualInfoLine">Add New Individual</button>
                </div>
            </div>
        </div>
       
        <div class="card__header" style="margin-top: 40px;">
            <div>
                
            </div>
            <div>
                <a @click="saveEntry()" class="btn btn-secondary">
                    Save
                </a>
            </div>
        </div>
        
    </div>

    
    
    <br><br><br>
    
   

</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
const router = useRouter();

// let form = ref([]);
let fileNumber = ref('');
let date = ref('');
let signatory = ref([]);
let amount = ref('');
let status = ref('');
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

const saveEntry = async () => {
  console.log(selectedSignatory.value)
  try {
    // Prepare data for entry_info
    const entryInfoData = {
      file_number: fileNumber.value,
      date: date.value,
      amount: amount.value,
      status: status.value,
      individual_infos: individualInfoLines.value,
      selectedSignatory: selectedSignatory.value,
    };
    axios.post('/api/save_gpf', entryInfoData);
    router.push('/')
    // console.log('Response:', response.data);

    fileNumber.value = ''; // Clear the fields after successful save
    date.value = '';
    selectedSignatory.value = '';
    amount.value = '';
    status.value = '';
    individualInfoLines.value = [];
  } catch (error) {
    console.error('Error:', error);
  }
};

//get signatory
const getsignatory = async  () => {
  let response = await axios.get('/api/signatory')
  // console.log('signatory', response)
  signatory.value = response.data.signatory
}


</script>