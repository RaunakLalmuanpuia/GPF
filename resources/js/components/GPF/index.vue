<template>
    <div class="invoices">
        
        <div class="card__header">
            <div>
                <h2 class="invoice__title">GPF</h2>
            </div>
            <div>
                <a class="btn btn-secondary" @click="newGpf()">
                    New GPF
                </a>
            </div>
            <div>
                <a class="btn btn-secondary" @click="openModal()">
                    New Signatory
                </a>
            </div>
        </div>
<!-- {{ gpf }} -->
        <div class="table card__content">
            <div class="table--search">
                <div class="table--search--wrapper">
                    <select class="table--search--select" name="" id="">
                        <option value="">Filter</option>
                    </select>
                </div>
                <div class="relative">
                    <i class="table--search--input--icon fas fa-search "></i>
                    <input v-model="searchGpf" @keyup="search()" class="table--search--input" type="text" placeholder="Search GPF">
                </div>
            </div>

            <div class="table--heading">
                <p>ID</p>
                <p>File Number</p>
                <p>Name</p>
                <p>Department</p>
                <p>Designation</p>
                <p>Status</p>
                <p>Signatory</p>
                <p>Amount</p>
                <p>Date</p>
            </div>

            
            <div class="table--items" v-for="gpf in gpf" :key="gpf.id" v-if="gpf.length > 0">
                <a href="#" @click="onShow(gpf.id)" class="table--items--transactionId">#{{ gpf.id }}</a>
                <p>{{ gpf.file_number }}</p>
                <p>{{ gpf.gpf_name }}</p>
                <p>{{ gpf.from_deparment }}</p>
                <p>{{ gpf.from_designation }}</p>
                <p>{{ gpf.status }}</p>
                <p>{{ gpf.signatory.name }}/{{  gpf.signatory.designation  }}</p>
                <p> $ {{gpf.amount  }}</p>
                <p>{{ gpf.date }}</p>
            </div>
            <div class="table--items" v-else>
                <p>No Gpf Found</p>
            </div>
        </div>
        
    </div>
     <!--==================== add modal items ====================-->
     <div class="modal main__modal" :class="{show: showModal}">
        <div class="modal__content">
            <span class="modal__close btn__close--modal" @click="closeModal()">×</span>
            <h3 class="modal__title">Add New Signatory</h3>
            <hr><br>
            <div class="modal__items">
                <p class="my-1">Name</p> 
                <input v-model="name" type="text" class="input"> 
                <p class="my-1">Designation</p> 
                <input v-model="designation" type="text" class="input"> 
            </div>
            <br><hr>
            <ul>
            <li v-for="(item, i) in signatory" :key="item.id" :value="item.id">
                {{ i+1 }}. {{ item.name }} /{{ item.designation }}
                <button style="margin-left: 100px;" @click="deletesignatory(item.id)">Delete</button>
            </li>
            </ul>
            <div class="model__footer">
                <button @click="closeModal()" class="btn btn-light mr-2 btn__close--modal">
                    Cancel
                </button>
                <button class="btn btn-light btn__close--modal" @click="addSignatory()">Save</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

let gpf = ref([]);
let searchGpf = ref([]);

let name = ref('');
let designation = ref('');

let signatory = ref([]);
const showModal = ref(false)

const openModal = () => {
  showModal.value = true
}
const closeModal = () => {
    showModal.value = false
}


onMounted(async () => {
    getGpf()
    getsignatory()
})

const getGpf = async () => {
    let response = await axios.get("/api/get_entry_info")
    // console.log('response', response);
    gpf.value = response.data.entry_info
}

const search = async() => {
    let response = await axios.get('/api/search_gpf?s='+searchGpf.value)
    // console.log('response', response.data.entry_info);
    gpf.value = response.data.entry_info;
}

const newGpf =async() => {
    router.push('/gpf/new')
}

const onShow = (id) => {
    router.push('/gpf/show/'+id);
}
//get signatory
const getsignatory = async  () => {
  let response = await axios.get('/api/signatory')
  // console.log('signatory', response)
  signatory.value = response.data.signatory
}

const deletesignatory = (id) => {
    axios.get('/api/delete_signatory/'+id)
    signatory.value = signatory.value.filter(item => item.id !== id);
}
// console.log('Search parameter:', searchGpf.value);
// const addSignatory = () => {
//      axios.post('/api/save_signatory', {
//         name: name.value,
//         designation: designation.value
//     })
//     //closeModal()
// }
const addSignatory = async () => {
    try {
        await axios.post('/api/save_signatory', {
            name: name.value,
            designation: designation.value
        });
        name.value = ''; // Clear the fields after successful save
        designation.value = '';
        getsignatory(); // Fetch signatories after saving
        // closeModal(); // Close modal after successful save
    } catch (error) {
        console.error('Error saving signatory:', error);
        // Handle error here
    }
}
</script>