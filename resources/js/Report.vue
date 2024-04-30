<template>
    <QuasarLayout>
    <!-- <p>
        Reports
    </p> -->
    <div class="flex-wrap q-pa-md column q-gutter-md"  >
    <h4 class="text-weight-bold">Generate Reports</h4>


    <!-- Date range filter -->
    <div class="date-inputs row">

      <div class="q-pa-md" style="max-width: 300px">
        <q-input filled v-model="start_date" mask="date" label="Start Date">
        <template v-slot:append>
            <q-icon name="event" class="cursor-pointer">
            <q-popup-proxy cover transition-show="scale" transition-hide="scale">
                <q-date v-model="start_date">
                <div class="items-center justify-end row">
                    <q-btn v-close-popup label="Close" color="primary" flat />
                </div>
                </q-date>
            </q-popup-proxy>
            </q-icon>
        </template>
        </q-input>
  </div>
  <div class="q-pa-md" style="max-width: 300px">
    <q-input filled v-model="end_date" mask="date" label="End Date" >
      <template v-slot:append>
        <q-icon name="event" class="cursor-pointer">
          <q-popup-proxy cover transition-show="scale" transition-hide="scale">
            <q-date v-model="end_date">
              <div class="items-center justify-end row">
                <q-btn v-close-popup label="Close" color="primary" flat />
              </div>
            </q-date>
          </q-popup-proxy>
        </q-icon>
      </template>
    </q-input>
  </div>
    </div>

  
    <div class="flex-wrap row q-gutter-md ">
      
      <q-select  v-model="selectedDepartment" :options="options" label="Department"
                                emit-value map-options use-input input-debounce="0" clearable filled
                                class=" col-md-8 col-lg-3"  @filter="filterFn"    @update:model-value="updateDepartment"/>
      <q-select v-model="status" label="Status" :options="statuses" multiple outlined class="col-4 col-lg-4 col-md-9" use-chips/>
    </div>

   



    <div class="flex-wrap items-center content-center justify-center row q-gutter-md fit " style="max-width: 50vw;">
      <q-btn label="Reset" color="grey" @click="resetFilter" />

    <!-- Generate Report button -->
    <q-btn @click="getFilteredReports()" label="Generate Report"  color="primary" class="col-lg-2 col-md-8"/>
    <q-btn @click="generateExcel()" label="Download Report"  color="secondary" class="col-lg-2 col-md-4"/>


    </div>

    <!-- Show reports -->
    <q-card v-if="data.length>0" class="q-mt-md">
      <p class="text-weight-bold q-pl-md q-pt-md text-h5"> Reports </p>
      <q-card-section>
        <!-- <q-table :rows="reports" :columns="columns" row-key="id" /> -->
        <q-table
          :rows="data"
          :columns="columns"
        />
      </q-card-section>




    </q-card>
    <!-- <div >
        <h4 class="text-center text-weight-bold">Statistical Reports</h4>
        <div class="items-center column q-gutter-md">
        <div class="flex-wrap row q-gutter-md">


        <q-card class="container q-pa-md " style="width: 200px;">
        <p class="text-body">Total Proposed AA</p>
        <p class="text-weight-bold text-h4">asd</p>
        </q-card>
        <q-card class="container q-pa-md " style="width: 200px;">
        <p class="text-body">Total Proposed ES</p>
        <p class="text-weight-bold text-h4">sd</p>
        </q-card>

        <q-card class="container q-pa-md " style="width: 200px;">
        <p class="text-body">Compiled Total Values</p>
        <p class="text-weight-bold text-h4">123</p>
        </q-card>
        </div>

       
        <div class="flex-wrap row q-gutter-md">

        <q-card class="container q-pa-md " style="width: 200px;">
            <p class="text-body">Number of Proposals</p>
        <p class="text-weight-bold text-h4">132</p>
        </q-card>
        <q-card class="container q-pa-md " style="width: 200px;">
            <p class="text-body">Number of Scheme</p>
        <p class="text-weight-bold text-h4">{{ numSchemes }}</p>
        </q-card>
       
        </div>

        </div>


    </div> -->



  </div>
</QuasarLayout>
</template>
<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { ref,computed ,onMounted} from 'vue';
import exportFromJSON from 'export-from-json'

let gpf = ref([]);
const  start_date= ref(null);
const  end_date=  ref(null);
const departmentChanged = ref(false);
const  status= ref([]);
let selectedDepartment = ref('');
const departmentOptions = ref([]);
let department = ref([]);
const reports = ref([]);
const statuses = ['Pending', 'Approved', 'Rejected'];
const jsonData = ref([]);

const resetFilter = () => {
      start_date.value = null;
      end_date.value = null;
      
      department.value = null;
      
      status.value = [];
      data.value = [];
      selectedDepartment.value = '' ;
      getDepartment()
    };

   
const data = ref([]);
const columns = ref([
  {
    name: 'file_number',
    required: true,
    label: 'File Number',
    align: 'left',
    field: 'file_number'
  },
  {
    name: 'date',
    required: true,
    label: 'Date',
    align: 'left',
    field: 'date'
  },
  {
    name: 'department',
    required: true,
    label: 'Department',
    align: 'left',
    field: 'department'
  },
  {
    name: 'signatory',
    required: true,
    label: 'Signatory',
    align: 'left',
    field: 'signatory'
  },
  {
    name: 'name',
    required: true,
    label: 'Name',
    align: 'left',
    field: 'name'
  },
  {
    name: 'phone',
    required: true,
    label: 'Phone',
    align: 'left',
    field: 'phone'
  },
  {
    name: 'designation',
    required: true,
    label: 'Designation',
    align: 'left',
    field: 'designation'
  },
  {
    name: 'account',
    required: true,
    label: 'Account',
    align: 'left',
    field: 'account'
  },
  {
    name: 'amount',
    required: true,
    label: 'Amount',
    align: 'left',
    field: 'amount'
  },
  {
    name: 'status',
    required: true,
    label: 'Status',
    align: 'left',
    field: 'status'
  }
]);

onMounted(async () =>{
    getDepartment()
   
    document.title = 'GPF - Report'
})

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
        department.value = response.data.department;
        // console.log(department.value);
        // formattedSignatory.value = response.data.signatory.map(item => ({label: item.name + ' / ' + item.designation, value: item.id}));
        // console.log(formattedSignatory.value);
    } catch (error) {
        console.error('Error fetching signatory:', error);
    }
};

const options = ref(department.value.map(department => department.name));
const selectedDepartmentId = ref(null);

const filterFn = (val, update) => {
  if (val === '') {
    update(() => {
      options.value = department.value.map(department => department.name);
    });
    return;
  }

  update(() => {
    const needle = val.toLowerCase();
    options.value = department.value.filter(department => department.name.toLowerCase().includes(needle)).map(department => department.name);
  });
};

const updateDepartment  = (name) => {
  const selectedDepartment = department.value.find(department => department.name === name);
  if (selectedDepartment) {
    selectedDepartmentId.value = selectedDepartment.id;
    departmentChanged.value = true;
  } else {
    selectedDepartmentId.value = null;
    departmentChanged.value = false;
  }
};

const getFilteredReports = async () => {
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
    
    // Extract values from filter parameters
    const payload = {};

    if (start_date.value) payload.start_date = start_date.value;
    if (end_date.value) payload.end_date = end_date.value;
    if (selectedDepartmentId.value) payload.selectedDepartmentId = selectedDepartmentId.value;
    if (status.value && status.value.length > 0) payload.status = status.value;
    
    const response = await axios.post('/api/filter-reports', payload, config); // Adjust the endpoint URL as per your backend setup
    jsonData.value  = response.data;
    data.value =prepareData(jsonData.value)

  } catch (error) {
    console.error('Error fetching reports:', error);
  }
};
//final
// const generateExcel = () => {
//   // Prepare data
//   const data = [];
//   jsonData.value.forEach(item => {
//     // Extract file number, date, department, and signatory
//     const { file_number, date, departments, signatory } = item;
    
//     // Iterate over individual infos
//     item.individual_infos.forEach(individual => {
//       // Push individual info as a new row
//       data.push({
//         file_number,
//         date,
//         department: departments.name,
//         signatory: signatory.name,
//         name: individual.name,
//         phone: individual.phone,
//         designation: individual.designation,
//         // entry_info_id: individual.entry_info_id,
//         account: individual.account,
//         amount: individual.amount,
//         status: individual.status
//       });
//     });
//   });

//   // Define the filename for the Excel file
//   const fileName = 'report_final';

//   // Call exportFromJSON function
//   exportFromJSON({ data, fileName, exportType: 'xls' });
// };

//with total row
const generateExcel = () => {
  // Prepare data
  const data = [];
  let totalAmount = 0; // Variable to store the total amount

  jsonData.value.forEach(item => {
    // Extract file number, date, department, and signatory
    const { file_number, date, departments, signatory } = item;
    
    // Iterate over individual infos
    item.individual_infos.forEach(individual => {
       // Convert individual.amount from string to integer
       const amount = parseInt(individual.amount, 10); // Assuming base 10

      // Push individual info as a new row
      data.push({
        file_number,
        date,
        department: departments.name,
        signatory: signatory.name,
        name: individual.name,
        phone: individual.phone,
        designation: individual.designation,
        // entry_info_id: individual.entry_info_id,
        account: individual.account,
        amount: individual.amount,
        status: individual.status
      });

      // Increment total amount
      // Increment total amount
      totalAmount += amount;
    });
  });

  // Add a row for the total amount at the bottom
  const totalRow = {
    file_number: '', // You can leave file_number empty for the total row
    date: '', // Similarly for other columns
    department: '',
    signatory: '',
    name: '',
    phone: '',
    designation: '',
    account: 'Total Amount:',
    amount: totalAmount, // Label for the total amount
    status: '' // The calculated total amount
  };
  data.push(totalRow);

  // Define the filename for the Excel file
  const fileName = 'report_final';

  // Call exportFromJSON function
  exportFromJSON({ data, fileName, exportType: 'xls' });
};

const prepareData = (jsonData) => {
  // Prepare data
  const preparedData = [];
  jsonData.forEach(item => {
    const { file_number, date, departments, signatory } = item;
    item.individual_infos.forEach(individual => {
      const amount = parseInt(individual.amount, 10);
      preparedData.push({
        file_number,
        date,
        department: departments.name,
        signatory: signatory.name,
        name: individual.name,
        phone: individual.phone,
        designation: individual.designation,
        account: individual.account,
        amount,
        status: individual.status
      });
    });
  });
  return preparedData;
};

</script>