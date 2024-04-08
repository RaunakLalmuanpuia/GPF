<template>
    <div>
        
        <div>
            <div>
                <h2>GPF</h2>
            </div>
            <!-- <div>
                <q-btn @click="newGpf()" color="white" text-color="black" label="New GPF"/>
            </div> -->
            <div>
                <q-btn @click="openModal()" color="white" text-color="black" label="New Signatory"/>
            </div>
        </div>

        <div class="relative">
                    <!-- <input v-model="searchGpf" @keyup="search()" class="table--search--input" type="text" placeholder="Search GPF"> -->
                    <q-input outlined v-model="searchGpf" @keyup="search()" type="text" placeholder="Search GPF" />
         </div>
<!-- {{ gpf }} -->
        

            
        

        <q-table
            :rows="gpf"
            :columns="columns"
            row-key="id"
            class="q-mt-md"
            >
            <template v-slot:top>
                <q-toolbar>
                <q-space />
                <q-btn @click="newGpf()" label="Create New" color="primary" />
                </q-toolbar>
            </template>

            <template v-slot:body="props">
                <q-tr :props="props">
                <q-td key="id">
                    <a href="#" @click="onShow(props.row.id)">{{ props.row.id }}</a></q-td>
                <q-td key="file_number">{{ props.row.file_number }}</q-td>
                <q-td key="gpf_name">{{ props.row.gpf_name }}</q-td>
                <q-td key="from_deparment">{{ props.row.from_deparment }}</q-td>
                <q-td key="from_designation">{{ props.row.from_designation }}</q-td>
                <!-- <q-td key="status">{{ props.row.status }}</q-td> -->
                <q-td key="signatory">{{ props.row.signatory.name }} / {{ props.row.signatory.designation }}</q-td>
                <!-- <q-td key="amount">$ {{ props.row.amount }}</q-td> -->
                <q-td key="date">{{ props.row.date }}</q-td>
                </q-tr>
            </template>

            <template v-slot:no-data>
                <q-td colspan="9" class="text-center">No GPF Found</q-td>
            </template>
        </q-table>

        
    </div>
     <!--==================== add modal items ====================-->

     <q-dialog v-model="showModal">
      <q-card class="my-card">
        <q-card-section>
            <div>
            <q-btn @click="closeModal()" v-close-popup flat color="primary" round icon="close" />
            <!-- <span class="modal__close btn__close--modal" @click="closeModal()">×</span> -->
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
        </q-card-section>
<!-- 
        <q-card-section class="q-pt-none">
          <div class="text-subtitle1">
            $・Italian, Cafe
          </div>
          <div class="text-caption text-grey">
            Small plates, salads & sandwiches in an intimate setting.
          </div>
        </q-card-section> -->

        <q-separator />

        <!-- <q-card-actions align="right">
          <q-btn v-close-popup flat color="primary" label="Reserve" />
          <q-btn v-close-popup flat color="primary" round icon="event" />
        </q-card-actions> -->
      </q-card>
    </q-dialog>

 <!--==================== add modal items ====================-->
     <!-- <div class="modal main__modal" :class="{show: showModal}">
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
    </div> -->
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

const columns = [
  { name: 'id', label: 'ID', align: 'left', field: 'id' },
  { name: 'file_number', label: 'File Number', align: 'left', field: 'file_number' },
  { name: 'gpf_name', label: 'Name', align: 'left', field: 'gpf_name' },
  { name: 'from_deparment', label: 'Department', align: 'left', field: 'from_deparment' },
  { name: 'from_designation', label: 'Designation', align: 'left', field: 'from_designation' },
  { name: 'signatory', label: 'Signatory', align: 'left', field: 'signatory' },
  { name: 'date', label: 'Date', align: 'left', field: 'date' },
  { name: 'action', label: 'Actions', align: 'left', field: 'action' } // Custom Actions column
];

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
