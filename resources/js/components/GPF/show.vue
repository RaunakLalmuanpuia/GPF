<template>
    <QuasarLayout>
    <div class="container">
             <!--==================== SHOW GPF ====================-->
             
        <div>
            <h3>GPF Account</h3>
            <!-- {{ form.signatory}} -->
                    <div>
                        <p class="px-2 pt-2">File Number: {{ form.file_number }}</p>
                        <p style="padding-left: 10px;">Date: {{ form.date }} </p>
                        <p style="padding-left: 10px;">Name: {{ form.gpf_name }} </p>
                        <p style="padding-left: 10px;">Department: {{ form.from_deparment }} </p>
                        <p style="padding-left: 10px;">Designation: {{ form.from_designation }} </p>
                        <p style="padding-left: 10px;" v-if="form.signatory">Signatory: {{ form.signatory.name }} / {{ form.signatory.designation }}</p>
                    </div>
                
            <!-- Change table design -->
            <div class="table invoice">
                <table style="padding-top: 20px; margin-right: 20px;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Phone</th>
                            <th>Account</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in form.individual_infos" :key="item.id">
                            <td>{{ i+1 }}</td>
                            <td>{{ item.name }}</td>
                            <td>{{ item.designation }}</td>
                            <td>{{ item.phone }}</td>
                            <td>{{ item.account }}</td>
                            <td>₹{{ item.amount }}</td>
                            <td>{{ item.status }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex">
                <q-btn
                        label="Edit"
                        color="primary"
                        class="ml-2 mr-2 q-mt-md"
                        @click="onEdit(form.id)"
                    />
                    <q-btn
                        label="Delete"
                        color="primary"
                        class="q-mt-md"
                        @click="deleteGpf(form.id)"
                    />
                
            </div>
            
        </div>

    </div>
</QuasarLayout>
</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { onMounted,ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

let form = ref({id:''})

const props = defineProps({
    id:{
        type:String,
        default:''
    }
})

onMounted(async () =>{
    getGpf()
    document.title = 'GPF - Show'
})

// const getGpf = async () => {
//     let response = await axios.get(`/api/show_gpf/${props.id}`)
//     // console.log('form', response.data.entry_info)
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
        let response = await axios.get(`/api/show_gpf/${props.id}`, config);

        // Set the form data after successful fetch
        form.value = response.data.entry_info;
    } catch (error) {
        console.error('Error:', error);
        // Handle error here, such as redirecting the user to the login page
    }
};

// const onEdit = (id) => {
//     router.push('/gpf/edit/' + id)
// }
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
        // If token is found, navigate to the edit page with the ID
        router.push(`/gpf/edit/${id}`,config);
    } catch (error) {
        console.error('Authentication error:', error);
        // Handle authentication error here, such as redirecting to the login page
    }
};
// const deleteGpf = (id) => {
//     axios.get('/api/delete_gpf/'+id)
//     router.push('/')
// }
const deleteGpf = (id) => {
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
        // If token is found, navigate to the edit page with the ID
        axios.get('/api/delete_gpf/'+id , config)
        router.push('/gpf')
    } catch (error) {
        console.error('Authentication error:', error);
        // Handle authentication error here, such as redirecting to the login page
    }
}
</script>

<style>
.container {
    display: flex;
    justify-content: center; /* Horizontal centering */
    align-items: center; /* Vertical centering */
    /* height: 100vh; Full viewport height */
}
    /* Table styles */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    th, td {
        border: 1px solid #dddddd;
        text-align: left;
        padding: 8px;
    }

    th {
        background-color: #f2f2f2;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* Responsive table */
    @media screen and (max-width: 600px) {
        table {
            border: 0;
        }

        table thead th {
            display: none;
        }

        table tr {
            border-bottom: 3px solid #ddd;
            display: block;
            margin-bottom: .625em;
        }

        table td {
            display: block;
            text-align: right;
            border-bottom: 1px dotted #ccc;
        }

        table td::before {
            content: attr(data-label);
            float: left;
            font-weight: bold;
            text-transform: uppercase;
        }
    }
</style>
