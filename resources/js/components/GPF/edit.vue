<template>
    <div class="container">
             <!--==================== EDIT INVOICE ====================-->
             <div class="invoices">
        
        <div class="card__header">
            <div>
                <h2 class="invoice__title">Edit GPF</h2>
            </div>
            <div>
                
            </div>
        </div>
        <p>{{ form }}</p>
        <div class="card__content">
            <div class="card__content--header">
                <div>
                    <p class="my-1">File Number</p>
                    <input v-model="form.file_number" type="text" class="input"> 
                    <p class="my-1">Department</p>
                    <input v-model="form.from_deparment" type="text" class="input"> 
                    <p class="my-1">Designation</p>
                    <input v-model="form.from_designation" type="text" class="input"> 
                </div>
                <div>
                    <p class="my-1">Date</p> 
                    <input v-model="form.date" type="text" class="input">  <!---->
                    <p class="my-1">Signatory</p> 
                    <div>
                      <select v-model="form.signatory_id" class="input dropdown">
                        <option disabled value="">Select a Signatory</option>
                        <option v-for="(item, i) in signatory" :key="item.id" :value="item.id">
                          {{ i+1 }}. {{ item.name }} {{ item.designation }}
                        </option>
                      </select>
                      <p class="my-1">Name of Fund</p>
                      <input v-model="form.gpf_name" type="text" class="input"> 
                      <!-- <button @click="addSignatory(selectedSignatory)" class="add-button">
                        +
                      </button> -->
                    </div>
                      
                </div>
                <div>
                    <p class="my-1">Amount</p> 
                    <input v-model="form.amount" type="text" class="input"> 
                    <p class="my-1">Status</p> 
                    <input v-model="form.status" type="text" class="input"> 
                   
                </div>
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

              <div v-if="form.individual_infos" class="individual-info-container">
                  <!-- Loop through individualInfoLines and render each line -->
                  <div v-for="(individualInfo, index) in form.individual_infos" :key="index" class="individual-info-line">
                      <!-- Individual info input fields -->
                      <!-- Use v-model to bind inputs to individualInfo properties -->
                      <input v-model="individualInfo.name" type="text" class="input" placeholder="Enter Name">
                      <input v-model="individualInfo.designation" type="text" class="input" placeholder="Enter Designation">
                      <input v-model="individualInfo.account" type="text" class="input" placeholder="Enter GPF Account">
                      <input v-model="individualInfo.amount" type="text" class="input" placeholder="Enter Amount">
                      <input v-model="individualInfo.phone" type="text" class="input" placeholder="Enter Mobile">
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
                <a @click="saveEntry()" class="btn btn-secondary">
                    Save
                </a>
            </div>
        </div>
        
    </div>

        <!-- <iframe width="500" height="250" src="https://www.youtube.com/embed/_EQuJYqK2oc" title="Laravel 10 and Vuejs 3 - Simple Invoice Application - Edit and Delete Invoice." frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe> -->
    </div>
</template>

<script setup>

import { onMounted, ref } from 'vue';

let signatory = ref([]);
let individualInfoLines = ref([]);
let selectedSignatory = ref('');

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
})

const getsignatory = async  () => {
  let response = await axios.get('/api/signatory')
  // console.log('signatory', response)
  signatory.value = response.data.signatory
}
const addNewIndividualInfoLine = () => {
    form.value.individual_infos.push({
        name: '',
        designation: '',
        account: '',
        amount: '',
        mobile: '',
        status: ''
    });
};
const removeIndividualInfoLine = (index) => {
    form.value.individual_infos.splice(index, 1);
};

const getGpf = async () => {
    let response = await axios.get(`/api/edit_gpf/${props.id}`)
    console.log('form', response.data.entry_info);
    form.value = response.data.entry_info
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