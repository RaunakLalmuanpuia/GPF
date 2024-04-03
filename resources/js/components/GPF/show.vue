<template>
    <div class="container">
             <!--==================== SHOW GPF ====================-->
        <div class="invoices">
            {{ form.signatory}}
            <div class="card__header">
                <div>
                    <h2 class="invoice__title">GPF Account</h2>
                </div>
                <div>
                    
                </div>
            </div>
            <div>
                <div class="card__header--title ">
                    <h1 class="mr-2">File Number: {{ form.file_number }}</h1>
                    <p>Date: {{ form.date }} </p>
                    

                    <p style="padding-left: 10px;">Status:{{ form.status }} </p>

                    <p style="padding-left: 10px;">Amount: Rs{{ form.amount }} </p>
                    <!-- <p style="padding-left: 10px;">Signatory: {{ form.signatory.name }}/{{ form.signatory.designation }} </p> -->
                </div>
                    <div>
                        <p style="padding-left: 10px;">Name: {{ form.gpf_name }} </p>
                        <p style="padding-left: 10px;">Department: {{ form.from_deparment }} </p>
                        <p style="padding-left: 10px;">Designation: {{ form.from_designation }} </p>
                    </div>
                <div>
                    <ul  class="card__header-list">
                        <li>
                            <!-- Select Btn Option -->
                            <button class="selectBtnFlat" @click="print()">
                                <i class="fas fa-print"></i>
                                Print
                            </button>
                            <!-- End Select Btn Option -->
                        </li>
                        <li>
                            <!-- Select Btn Option -->
                            <button class="selectBtnFlat" @click="onEdit(form.id)">
                                <i class=" fas fa-reply"></i>
                                Edit
                            </button>
                            <!-- End Select Btn Option -->
                        </li>
                        <li>
                            <!-- Select Btn Option -->
                            <button class="selectBtnFlat " @click="deleteGpf(form.id)">
                                <i class=" fas fa-pencil-alt"></i>
                                Delete
                            </button>
                            <!-- End Select Btn Option -->
                        </li>
                        
                    </ul>
                </div>
            </div>

            <div class="table invoice">
                <!-- <div class="logo">
                    <img src="assets/img/logo.png" alt="" style="width: 200px;">
                </div> -->
                <div class="invoice__header--title">
                    <p></p>
                    <p class="invoice__header--title-1">GPF</p>
                    <p></p>
                </div>

                

            
                <table style="padding-top: 20px;">
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
                            <td>${{ item.amount }}</td>
                            <td>{{ item.status }}</td>
                        </tr>
                    </tbody>
                </table>
                

                

            </div>
            <div class="card__footer">
               
                <!-- <div>
                    <a class="btn btn-secondary">
                        Save
                    </a>
                </div>
                <div>
                    <a class="btn btn-secondary">
                        Print
                    </a>
                </div> -->
            </div>
            
        </div>

    </div>
</template>

<script setup>
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
    getInvoice()
})

const getInvoice = async () => {
    let response = await axios.get(`/api/show_gpf/${props.id}`)
    console.log('form', response.data.entry_info)
    form.value = response.data.entry_info
}

const print = () => {
    window.print()
    router.push('/').catch(() => {})
}


const onEdit = (id) => {
    router.push('/gpf/edit/' + id)
}

const deleteGpf = (id) => {
    axios.get('/api/delete_gpf/'+id)
    router.push('/')
}

</script>

<style>
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
