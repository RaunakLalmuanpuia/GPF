
<template>
 <QuasarLayout>
      <p class="text-weight-bold text-h4"> Departments</p>
      
      <q-btn class=" q-ma-md"  icon="add"  label="Add" color="primary" @click="addDepartment" />

      <div class=" q-ma-lg q-pa-md">
            <!-- <input v-model="searchGpf" @keyup="search()" class="table--search--input" type="text" placeholder="Search GPF"> -->
            <q-input clearable  @clear="clearSearch" outlined v-model="searchDepartment" @keyup="search()" type="text" placeholder="Search Department" />
        </div>  
    
      <q-table
        :rows="departments"
        :columns="columns"
        title = "Departments"
        row-key="id"
        flat bordered
            separator="cell"
            virtual-scroll
      >
      <template v-slot:body-cell-actions="{row}">
        <div class="justify-center row">
          <q-td key="edit">
      <q-btn icon="edit" @click="editDepartment(row)"  unelevated  size="12px"/>
    </q-td>
    <q-td key="delete">
      <q-btn icon="delete" @click="confirmDelete(row)"  unelevated size="12px"/>
    </q-td>
        </div>
  
  </template>
  
      </q-table>
  
  
      <q-dialog v-model="createDialogVisible" >
        <q-card>
          <p class="text-center text-weight-bold text-h6 q-pt-md"> Add Department</p>
          <q-card-section>
            <div class="flex-wrap column q-gutter-md">
              <q-input v-model="newDepartment.name" label="Department Name"  outlined/>
              <q-input v-model="newDepartment.address" label="Address"  outlined type="textarea"/>
              <q-checkbox v-model="newDepartment.is_active" label="Is Active" />
              <q-btn label="Cancel" color="negative"  @click="closedialog()"/>
              <q-btn type="submit" label="Save" color="primary" @click="submitNewDepartment"  />
  
            </div>
          </q-card-section>
        </q-card>
      </q-dialog>
  
      <q-dialog v-model="dialogVisible" >
        <q-card>
          <p class="text-center text-weight-bold text-h6 q-pt-md"> Edit Department</p>
          <q-card-section>
            <div class="flex-wrap column q-gutter-md">
              <q-input v-model="editedDepartment.name" label="Department Name"  outlined/>
              <q-input v-model="editedDepartment.address" label="Address"  outlined type="textarea"/>
              <q-input v-model="editedDepartment.number" label="Phone"  outlined/>
              <q-btn label="Cancel" color="negative" @click="dialogVisible = false" />
              <q-btn type="submit" label="Save" color="primary"  @click="saveChanges"/>
  
            </div>
          </q-card-section>
        </q-card>
      </q-dialog>
  
      <q-dialog v-model="confirmDeleteVisible">
  <q-card>
              <q-card-section>
                <span class="text-h6">Are you sure you want to delete this department?</span>
              </q-card-section>
              <q-card-actions align="right">
                <q-btn label="Cancel" color="primary" @click="confirmDeleteVisible = false" />
                <q-btn label="Delete" color="red" @click="deleteDepartment" />
              </q-card-actions>
            </q-card>
  
      </q-dialog>
  
    </QuasarLayout>
  </template>
  
  <script setup>
  import { ref, reactive, onMounted } from 'vue';
  import QuasarLayout from "@/Layout/Layout.vue";

  let searchDepartment = ref([]);

//   import { api } from 'src/boot/axios';
  const columns = [
    { name: 'name', required: true, label: 'Name', align: 'left', field: 'name', sortable: true },
    { name: 'address', required: true, label: 'Address', align: 'left', field: 'address', sortable: true },
    { name: 'number', required: true, label: 'Number', align: 'left', field: 'number', sortable: true },
    // {
    //   name: 'is_active',
    //   required: false,
    //   label: 'Active',
    //   align: 'center',
    //   field: 'is_active',
    //   sortable: true,
    //   format: (value) => (value ? 'Yes' : 'No') // Custom format function to display 'Yes' or 'No'
    // },
    {
      name: 'actions',
      label: 'Actions',
      align: 'center',
      sortable: false,
      format: (value, row) => row // Custom format function to return the row object
    },
  ];
  
  
  
  const departments = ref([]);
  const editedDepartment = reactive({});
  
  let dialogVisible = ref(false);
  let createDialogVisible = ref(false);
  
  
  let confirmDeleteVisible = ref(false);
  let selectedDepartment = null;
;
  

const getDepartment = async () => {
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

        const response = await axios.get('/api/department', config);
        departments.value = response.data.department;
        // console.log(department.value);
        // formattedSignatory.value = response.data.signatory.map(item => ({label: item.name + ' / ' + item.designation, value: item.id}));
        // console.log(formattedSignatory.value);
    } catch (error) {
        console.error('Error fetching signatory:', error);
    }
};
  
  const editDepartment = (row) => {
    editedDepartment.name = row.name;
    editedDepartment.address = row.address;
    editedDepartment.number = row.number;
    selectedDepartment = row;
    dialogVisible.value = true;
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
    await axios.post(`/api/departments/${selectedDepartment.id}`, editedDepartment, config);
    dialogVisible.value = false;
    getDepartment();
  };

  const submitNewDepartment = async () => {
    const token = localStorage.getItem('token'); // Get the token from local storage
        if (!token) {
            throw new Error('No token found');
        }

        const config = {
            headers: {
                Authorization: `Bearer ${token}` // Include the token in the request headers
            }
        };
    await axios.post('/api/departments', newDepartment.value, config);
    createDialogVisible.value = false;
    getDepartment();
  };
  
  const addDepartment = async () => {
    createDialogVisible.value = true;
  }
  const closedialog = async () => {
    createDialogVisible.value = false;
  }
  const clearSearch = async () => {
    searchDepartment.value = '';
    getDepartment() 
  }
  const newDepartment = ref({
    name :'',
    address : '',
    number : '',
  });
  
  const deleteDepartment = async () => {
    const token = localStorage.getItem('token'); // Get the token from local storage
        if (!token) {
            throw new Error('No token found');
        }

        const config = {
            headers: {
                Authorization: `Bearer ${token}` // Include the token in the request headers
            }
        };
    await axios.delete(`/api/departments/${selectedDepartment.id}`, config);
    confirmDeleteVisible.value = false;
    getDepartment();
  };
  
  const confirmDelete = (row) => {
    selectedDepartment = row;
    confirmDeleteVisible.value = true;
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
        console.log(searchDepartment.value);
        const response = await axios.get('/api/search_department?s=' + searchDepartment.value, config);
        departments.value = response.data.department;

    } catch (error) {
        console.error('Error searching:', error);
    }
};
  
  onMounted(async () =>{
    getDepartment()
   
    document.title = 'GPF - Departments'
})

  </script>
  