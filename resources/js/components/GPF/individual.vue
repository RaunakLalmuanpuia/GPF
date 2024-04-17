<template>
    <QuasarLayout>
        <!-- <p>Individuals</p> -->
        <div class=" q-ma-lg q-pa-md">
            <!-- <input v-model="searchGpf" @keyup="search()" class="table--search--input" type="text" placeholder="Search GPF"> -->
            <q-input clearable outlined v-model="searchIndividuals" @keyup="search()" type="text" placeholder="Search Individual" />
        </div>  
        <q-table
            :rows="individuals"
            :columns="columns"
            row-key="id"
            class="q-ma-md"
            >
            

            <template v-slot:body="props">
                <q-tr :props="props" @click="onShow(props.row.entry_info.id)" class="cursor-pointer">
                <q-td key="id">
                    <a href="#" @click="onShow(props.row.entry_info.id)"> {{ props.rowIndex+1 }}</a></q-td>
                <q-td key="name">{{ props.row.name }}</q-td>
                <q-td key="account">{{ props.row.account }}</q-td>
                <q-td key="amount">{{ props.row.amount }}</q-td>
                <q-td key="designation">{{ props.row.designation }}</q-td>
                
                <q-td key="from_deparment">{{ props.row.entry_info.from_deparment }}</q-td>
                <q-td key="file_number">{{ props.row.entry_info.file_number }}</q-td>
                <q-td key="phone">{{ props.row.phone }}</q-td>
                <q-td :class="getStatusColor(props.row.status)" key="status">{{ props.row.status }}</q-td>
                <q-td key="date">{{ props.row.entry_info.date }}</q-td>
                <!-- <q-td key="status">{{ props.row.status }}</q-td> -->
                <!-- <q-td key="signatory">{{ props.row.signatory.name }} / {{ props.row.signatory.designation }}</q-td> -->
                <!-- <q-td key="amount">$ {{ props.row.amount }}</q-td> -->
                <!-- <q-td key="status">{{ props.row.date }}</q-td> -->
                </q-tr>
            </template>

            <template v-slot:no-data>
                <q-td colspan="9" class="text-center">No Indivuduals Found</q-td>
            </template>
        </q-table>
    </QuasarLayout>
   
</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

const individuals = ref([]);
let searchIndividuals = ref([]);

const columns = [
  { name: 'id', label: 'ID', align: 'left', field: 'id' },
  { name: 'name', label: 'Name', align: 'left', field: 'name' },
  { name: 'account', label: 'Account', align: 'left', field: 'account' },
  { name: 'amount', label: 'Amount', align: 'left', field: 'amount' },
  { name: 'designation', label: 'Designation', align: 'left', field: 'designation' },
  { name: 'from_deparment', label: 'Department', align: 'left', field: 'from_deparment' },
  { name: 'file_number', label: 'File_number', align: 'left', field: 'file_number' },
  { name: 'phone', label: 'Phone', align: 'left', field: 'phone' },
  { name: 'status', label: 'Status', align: 'left', field: 'status' },
  { name: 'date', label: 'Date', align: 'left', field: 'date' },
  
  // Custom Actions column
];

onMounted(async () => {
    getIndividuals()
    document.title = 'GPF - Indivuduals';
})


const getIndividuals = async () => {
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

        const response = await axios.get("/api/individuals", config);
        individuals.value = response.data.individual_info;
        // console.log(individuals)
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

        const response = await axios.get('/api/search_individuals?s=' + searchIndividuals.value, config);
        individuals.value = response.data.individual_info;
    } catch (error) {
        console.error('Error searching:', error);
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
const getStatusColor = (status) => {
  switch (status) {
    case 'Approved':
      return 'bg-green-500';
    case 'Rejected':
      return 'bg-red-500';
    case 'Pending':
      return 'bg-yellow-500';
    default:
      return ''; // Or any default color if needed
  }
}
</script>