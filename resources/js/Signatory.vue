
<template>
    <QuasarLayout>
         <p class="text-weight-bold text-h4">Signatory</p>
         
         <q-btn class=" q-ma-md"  icon="add"  label="Add" color="primary" @click="addSignatory" />
   
         <div class=" q-ma-lg q-pa-md">
               <!-- <input v-model="searchGpf" @keyup="search()" class="table--search--input" type="text" placeholder="Search GPF"> -->
               <q-input clearable  @clear="clearSearch" outlined v-model="searchSignatory" @keyup="search()" type="text" placeholder="Search Signatory" />
           </div>  
       
         <q-table
           :rows="signatory"
           :columns="columns"
           title = "Signatories"
           row-key="id"
           flat bordered
               separator="cell"
               virtual-scroll
         >
         <template v-slot:body-cell-actions="{row}">
           <div class="justify-center row">
             <q-td key="edit">
         <q-btn icon="edit" @click="editSignatory(row)"  unelevated  size="12px"/>
       </q-td>
       <q-td key="delete">
         <q-btn icon="delete" @click="confirmDelete(row)"  unelevated size="12px"/>
       </q-td>
           </div>
     
     </template>
     
         </q-table>
     
     
         <q-dialog v-model="createDialogVisible" >
           <q-card>
             <p class="text-center text-weight-bold text-h6 q-pt-md"> Add Signatory</p>
             <q-card-section>
               <div class="flex-wrap column q-gutter-md">
                 <q-input v-model="name" label="Signatory Name"  outlined/>
                 <q-input v-model="designation" label="Designation"  outlined />
                 <q-input v-model="phone" label="Phone"  outlined />
                 
                 <q-btn label="Cancel" color="negative"  @click="closedialog()"/>
                 <q-btn type="submit" label="Save" color="primary" @click="addnewSignatory"  />
     
               </div>
             </q-card-section>
           </q-card>
         </q-dialog>
     
         <q-dialog v-model="dialogVisible" >
           <q-card>
             <p class="text-center text-weight-bold text-h6 q-pt-md"> Edit Signatory</p>
             <q-card-section>
               <div class="flex-wrap column q-gutter-md">
                 <q-input v-model="editedSignatory.name" label="Name"  outlined/>
                 <q-input v-model="editedSignatory.designation" label="Designation"  outlined />
                 <q-input v-model="editedSignatory.phone" label="Phone"  outlined/>
                 <q-btn label="Cancel" color="negative" @click="dialogVisible = false" />
                 <q-btn type="submit" label="Save" color="primary"  @click="saveChanges"/>
     
               </div>
             </q-card-section>
           </q-card>
         </q-dialog>
     
         <q-dialog v-model="confirmDeleteVisible">
     <q-card>
                 <q-card-section>
                   <span class="text-h6">Are you sure you want to delete this Signatory?</span>
                 </q-card-section>
                 <q-card-actions align="right">
                   <q-btn label="Cancel" color="primary" @click="confirmDeleteVisible = false" />
                   <q-btn label="Delete" color="red" @click="deleteSignatory" />
                 </q-card-actions>
               </q-card>
     
         </q-dialog>
     
       </QuasarLayout>
     </template>
     
<script setup>
     import { ref, reactive, onMounted } from 'vue';
     import QuasarLayout from "@/Layout/Layout.vue";
   
     let searchSignatory = ref([]);
     
     let name = ref('');
     let designation = ref('');
     let phone = ref('');

     let signatory = ref([]);
   //   import { api } from 'src/boot/axios';
     const columns = [
       { name: 'name', required: true, label: 'Name', align: 'left', field: 'name', sortable: true },
       { name: 'designation', required: true, label: 'Designation', align: 'left', field: 'designation', sortable: true },
       { name: 'phone', required: true, label: 'Phone', align: 'left', field: 'phone', sortable: true },
       {
         name: 'actions',
         label: 'Actions',
         align: 'center',
         sortable: false,
         format: (value, row) => row // Custom format function to return the row object
       },
     ];
     
     
  const editedSignatory = reactive({});
  
  let dialogVisible = ref(false);
  let createDialogVisible = ref(false);
  
  
  let confirmDeleteVisible = ref(false);
  let selectedSignatory = null;
  
const addSignatory = async () => {
    createDialogVisible.value = true;
  }
  const closedialog = async () => {
    createDialogVisible.value = false;
  }
  const clearSearch = async () => {
    searchSignatory.value = '';
    getsignatory() 
  }
 
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

const editSignatory = (row) => {
    editedSignatory.name = row.name;
    editedSignatory.designation = row.designation;
    editedSignatory.phone = row.phone;
    selectedSignatory = row;
    dialogVisible.value = true;
  };

  
const confirmDelete = (row) => {
    selectedSignatory = row;
    confirmDeleteVisible.value = true;
  };
  
  const deleteSignatory = async () => {
    const token = localStorage.getItem('token'); // Get the token from local storage
        if (!token) {
            throw new Error('No token found');
        }

        const config = {
            headers: {
                Authorization: `Bearer ${token}` // Include the token in the request headers
            }
        };
    await axios.delete(`/api/delete_signatory/${selectedSignatory.id}`, config);
    confirmDeleteVisible.value = false;
    getsignatory();
  };



const addnewSignatory = async () => {
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
            designation: designation.value,
            phone : phone.value
        }, config); // Include the config object in the request

        name.value = ''; // Clear the fields after successful save
        designation.value = '';
        phone.value = '';
        createDialogVisible.value = false;
        getsignatory(); // Fetch signatories after saving
        // closeModal(); // Close modal after successful save
    } catch (error) {
        console.error('Error saving signatory:', error);
        // Handle error here
    }
};
const saveChanges = async () => {
    const token = localStorage.getItem('token'); // Get the token from local storage
        if (!token) {
            throw new Error('No token found');
        }

        const config = {
            headers: {
                Authorization: `Bearer ${token}` // Include the token in the request headers
            }
        };
    await axios.post(`/api/signatory/${selectedSignatory.id}`, editedSignatory, config);
    dialogVisible.value = false;
    getsignatory();
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
        // console.log(searchSignatory.value);
        const response = await axios.get('/api/search_signatory?s=' + searchSignatory.value, config);
        signatory.value = response.data.signatory;

    } catch (error) {
        console.error('Error searching:', error);
    }
};
onMounted(async () => {
    getsignatory()
    document.title = 'GPF - Signatory';
})
   
     </script>
     