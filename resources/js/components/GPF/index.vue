<template>
    <QuasarLayout>
    <div class="q-pa-md">
        
        <div>
            <div>
                <h4 class="ml-4 text-gray-800">Welcome!</h4>
            </div>
            
            <div style="display: flex; justify-content: flex-end; margin-right: 20px; margin-bottom:5px;">
                 <q-btn @click="openModal()" color="white" text-color="black" label="New Signatory"/>
            </div>
            
        </div>

        <div class="mt-2 ml-10 mr-10">
            <!-- <input v-model="searchGpf" @keyup="search()" class="table--search--input" type="text" placeholder="Search GPF"> -->
            <q-input outlined v-model="searchGpf" @keyup="search()" type="text" placeholder="Search GPF" />
        </div>

        <div class="flex mt-10 ml-4 justify-content:flex-start">
                     <q-btn @click="newGpf()" label="Create New" color="primary" />
        </div>
        <q-table
            :rows="gpf"
            :columns="columns"
            row-key="id"
            class="q-mt-md"
            >
            

            <template v-slot:body="props">
                <q-tr :props="props" @click="onShow(props.row.id)" class="cursor-pointer">
                <q-td key="id">
                    <a href="#" @click="onShow(props.row.id)"> {{ props.rowIndex+1 }}</a></q-td>
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
            <q-btn  @click="closeModal()" v-close-popup flat color="primary" round icon="close" />
            
            <h3 class="modal__title">Add New Signatory</h3>
            <hr><br>
            <div class="modal__items">
                <q-input outlined  v-model="name" label="Name" class="q-py-md" />
                <q-input outlined  v-model="designation" label="Designation" class="q-py-md" />
            </div>
            <br><hr>
            <ul>
            <li  class="py-2" v-for="(item, i) in signatory" :key="item.id" :value="item.id">
                {{ i+1 }}. {{ item.name }} /{{ item.designation }}
                <q-btn style="margin-left: 10px;" color="primary" @click="deletesignatory(item.id)">Delete</q-btn>
            </li>
            </ul>
            <div class="mt-5">
                <q-btn @click="closeModal()" class="mr-2 btn btn-light btn__close--modal">
                    Cancel
                </q-btn>
                <q-btn class="btn btn-light btn__close--modal" @click="addSignatory()">Save</q-btn>
            </div>
        </div>
        </q-card-section>


        <q-separator />

      </q-card>
    </q-dialog>

 
</QuasarLayout>
</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
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
  // Custom Actions column
];

onMounted(async () => {
    getGpf()
    getsignatory()
    document.title = 'GPF - Index';
})

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

        const response = await axios.get("/api/get_entry_info", config);
        gpf.value = response.data.entry_info;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
};


const search = async () => {
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

        const response = await axios.get('/api/search_gpf?s=' + searchGpf.value, config);
        gpf.value = response.data.entry_info;
    } catch (error) {
        console.error('Error searching:', error);
    }
};


const newGpf = async () => {
    try {
        const token = localStorage.getItem('token'); // Get the token from local storage
        if (!token) {
            throw new Error('No token found');
        }

        // If token is found, redirect the user to the new route
        router.push('/gpf/new');
    } catch (error) {
        console.error('Authentication error:', error);
        // Handle authentication error here, such as redirecting to the login page
    }
};


const onShow = async (id) => {
    try {
        const token = localStorage.getItem('token'); // Get the token from local storage
        if (!token) {
            throw new Error('No token found');
        }

        // If token is found, redirect the user to the show route with the ID
        router.push(`/gpf/show/${id}`);
    } catch (error) {
        console.error('Authentication error:', error);
        // Handle authentication error here, such as redirecting to the login page
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



const deletesignatory = async (id) => {
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

        await axios.get(`/api/delete_signatory/${id}`, config); // Include the config object in the request

        // After successful deletion, update the signatory array in the Vue component
        signatory.value = signatory.value.filter(item => item.id !== id);
    } catch (error) {
        console.error('Error deleting signatory:', error);
    }
};


const addSignatory = async () => {
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

        await axios.post('/api/save_signatory', {
            name: name.value,
            designation: designation.value
        }, config); // Include the config object in the request

        name.value = ''; // Clear the fields after successful save
        designation.value = '';
        getsignatory(); // Fetch signatories after saving
        // closeModal(); // Close modal after successful save
    } catch (error) {
        console.error('Error saving signatory:', error);
        // Handle error here
    }
};



</script>
