<template>
   <QuasarLayout>
    <q-btn label="back" icon="arrow_back" unelevated class="q-py-md" @click="router.go(-1)"></q-btn>
    <div class="items-center justify-center q-pa-md q-gutter-sm column q-my-md">
     
      <h4>GPF Approval  Template</h4>

      <q-editor v-model="editorContent" :style="{ width: editorWidth, height: editorHeight }" min-height="5rem"
      
      :toolbar="[
          [
            {
              label: $q.lang.editor.align,
              icon: $q.iconSet.editor.align,
              fixedLabel: true,
              list: 'only-icons',
              options: ['left', 'center', 'right', 'justify']
            },
          ],
          ['bold', 'italic', 'strike', 'underline', 'subscript', 'superscript'],
          ['print'],
          [
            {
              label: $q.lang.editor.formatting,
              icon: $q.iconSet.editor.formatting,
              list: 'no-icons',
              options: [
                'p',
                'h1',
                'h2',
                'h3',
                'h4',
                'h5',
                'h6',
                'code'
              ]
            },
            {
              label: $q.lang.editor.fontSize,
              icon: $q.iconSet.editor.fontSize,
              fixedLabel: true,
              fixedIcon: true,
              list: 'no-icons',
              options: [
                'size-1',
                'size-2',
                'size-3',
                'size-4',
                'size-5',
                'size-6',
                'size-7'
              ]
            },
            {
              label: $q.lang.editor.defaultFont,
              icon: $q.iconSet.editor.font,
              fixedIcon: true,
              list: 'no-icons',
              options: [
                'default_font',
                'arial',
                'arial_black',
                'comic_sans',
                'courier_new',
                'impact',
                'lucida_grande',
                'times_new_roman',
                'verdana'
              ]
            },
            'removeFormat'
          ],
          ['quote', 'unordered', 'ordered', 'outdent', 'indent'],

          ['undo', 'redo'],
          ['viewsource']
        ]"
        :fonts="{
          arial: 'Arial',
          arial_black: 'Arial Black',
          comic_sans: 'Comic Sans MS',
          courier_new: 'Courier New',
          impact: 'Impact',
          lucida_grande: 'Lucida Grande',
          times_new_roman: 'Times New Roman',
          verdana: 'Verdana'
        }" />
      <div class="row q-gutter-md q-my-md" >
        <!-- get the new GPF account details if any changes were made to the account -->
        <q-btn
            @click="getGpftemplate()"
            label="Reset Template"
            color="primary"
            class="q-mt-md"
            v-model="new_template"
          />
          <q-btn
          
            label="Save Data"
            color="primary"
            class="q-mt-md"
            @click="saveTemplate()"
          />
      </div>
      <!-- <div class="row q-gutter-md">
        <q-select
        v-model="selectedFormat"
        :options="formatOptions"
        label="Select Paper Size"
        outlined
        class=" q-mt-md" style="width: 200px;"
      /> -->
      <div class="row q-gutter-md">
      <q-select
      v-model="selectedFormat"
      :options="formatOptions"
      label="Select Paper Size"
      outlined
      class=" q-mt-md" style="width: 200px;"
    />


      <!-- <q-btn
      @click="printData()"
      label="Print"
      color="grey"
      class="q-mt-md"
    /> -->
      </div>
      
  </div>
</QuasarLayout>
</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { onMounted,ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import html2canvas from 'html2canvas';
import jspdf from 'jspdf';
import { Notify } from 'quasar';

const router = useRouter();
const format = ref('A4');

const exist = ref(false);
const temp_id = ref('');
const new_template = ref(false);

// console.log('New Template'+new_template.value);
const selectedFormat = ref('A4');

const formatOptions = [
  { label: 'A4', value: 'A4', width: '210mm', height: '297mm' }, // A4 size is 210mm x 297mm
  { label: 'Legal', value: 'Legal', width: '216mm', height: '356mm' } // Legal size is 216mm x 356mm
];
const editorWidth = ref('210mm'); // Default to A4 width
const editorHeight = ref('297mm'); // Default to A4 height

// watch(selectedFormat, (newValue) => {
//   const selectedOption = formatOptions.find((option) => option.value === newValue);
//   if (selectedOption) {
//     editorWidth.value = `${selectedOption.width}mm`;
//     editorHeight.value = `${selectedOption.height}mm`;
//   }
// });
watch(selectedFormat, (newValue) => {
  console.log("Selected format changed to: ", newValue);
  const selectedOption = formatOptions.find(option => option.value === newValue.value);
  console.log("Selected option: ", selectedOption);
  if (!selectedOption) {
    console.error("No matching option found for value: ", newValue);
    return;
  }
  editorWidth.value = selectedOption.width;
  editorHeight.value = selectedOption.height;
  console.log("Updated editorWidth: ", editorWidth.value);
  console.log("Updated editorHeight: ", editorHeight.value);
});




let form = ref({id:''})


const props = defineProps({
    id:{
        type:String,
        default:''
    }
})


const printData = async () => {
  try {
    // Convert the editor content to a canvas element
    const canvas = await html2canvas(document.querySelector('.q-editor__content'), {
      width: 800,
      height: 900,
      useCORS: true,
    });

    // Create a new PDF document with A4 size
    const doc = new jspdf('p', 'mm', 'a4');

    // Add the canvas content to the PDF document
    // const width = doc.internal.pageSize.getWidth();
    // const height = doc.internal.pageSize.getHeight();
    const width = editorWidth.value.width;
    const height = editorHeight.value.height;
    doc.addImage(canvas, 'JPEG', 0, 0, width, height);
            doc.save(`gpf_file.pdf`);

            Notify.create({
          type: 'secondary',

          message: 'Routine Sheet Downloaded!',
        });
    // Open a dialog box with the print preview
    // const printDialog = await Notify.request({
    //   type: 'information',
    //   message: 'Print preview',
    //   actions: [
    //     {
    //       label: 'Print',
    //       color: 'positive',
    //       handler: () => {
    //         // Print the PDF document
    //         doc.save('template.pdf');
    //       },
    //     },
    //     {
    //       label: 'Cancel',
    //       color: 'negative',
    //       handler: () => {
    //         /* ... */
    //       },
    //     },
    //   ],
    // });
  } catch (error) {
    console.error('Error printing data:', error);
  }
};

onMounted(async () =>{
    await getGpf();
    await checkExist();
    document.title = 'GPF - Template'
})

let entryInfoData;
let editorContent = ref('');



//get the template if it exists
const fetchTemplateData = async () => {
  try {
    const token = localStorage.getItem('token'); 
    if (!token) {
      throw new Error('No token found');
    }
    const config = {
            headers: {
                Authorization: `Bearer ${token}` 
            }
        };
    let response = await axios.get(`/api/approval_templates/${props.id}`, config);

     editorContent.value = response.data.template.contents;

  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
// check if template exist or not
const checkExist = async () => {
  try {
    const token = localStorage.getItem('token'); 
    if (!token) {
      throw new Error('No token found');
    }
    const config = {
            headers: {
                Authorization: `Bearer ${token}` 
            }
        };
    const entryInfoData = {
      entry_info: form.value.id,
      purpose: "Approval",
    };

    let response = await axios.post('/api/check_existence', entryInfoData, config);
    exist.value = response.data.exists == true ? true : false;
    temp_id.value = response.data.id;
    // console.log('Template id ' + temp_id.value);
    if (exist.value == true) {
      console.log("EXIST");
      fetchTemplateData();
    } else {
      // console.log("NOT EXIST");
      getGpftemplate();
    }
  } catch (error) {
    console.error('Error fetching data:', error);
    // Handle authentication error here, such as redirecting to the login page
  }
};

const saveTemplate = async () => {
  try {
    const token = localStorage.getItem('token'); 
    if (!token) {
      throw new Error('No token found');
    }
    const config = {
            headers: {
                Authorization: `Bearer ${token}` 
            }
        };
    // Prepare data for entry_info
    const entryInfoData = {
      template_id: temp_id.value,
      purpose: "approval",
      content: editorContent.value,
      new: new_template.value,
    };

    await axios.post(`/api/save_approval_template/${form.value.id}`, entryInfoData,config);
    
    alert('Success');
  } catch (error) {
    console.error('Error saving template:', error);
    
  }
};

  const getGpf = async () => {
    try {
      const token = localStorage.getItem('token'); 
      if (!token) {
        throw new Error('No token found');
      }
      const config = {
              headers: {
                  Authorization: `Bearer ${token}` 
              }
          };
      let response = await axios.get(`/api/edit_gpf/${props.id}`, config);
      form.value = response.data.entry_info;
      // console.log('form', form.value);
    } catch (error) {
      console.error('Error fetching data:', error);
      // Handle authentication error here, such as redirecting to the login page
    }
  };


const getGpftemplate = () => {
  new_template.value = true;
  // console.log('New Template'+new_template.value);
  return new Promise(async (resolve, reject) => {
    try {
      const token = localStorage.getItem('token'); 
        if (!token) {
            throw new Error('No token found');
        }
        const config = {
            headers: {
                Authorization: `Bearer ${token}` 
            }
        };
        
      let response = await axios.get(`/api/show_gpf/${props.id}`, config);
      form.value = response.data.entry_info;
      let totalAmount = 0; 
   
      // Filter individual_infos by status 'Approved'
      const approvedIndividualInfos = form.value.individual_infos.filter(info => info.status === 'Approved');
      
      
      approvedIndividualInfos.forEach(info => {
        // Remove commas from the amount string and then convert it to an integer
        const amountWithoutCommas = info.amount.replace(/,/g, '');
        totalAmount += parseInt(amountWithoutCommas); 
      });

      // Format totalAmount to match the same format as info.amount
      const formattedTotalAmount = totalAmount.toLocaleString('en-IN');

      // Convert formatted total amount to words
      const totalAmountInWords = numberToWords(totalAmount);

      // approvedIndividualInfos.forEach(info => {
      //   totalAmount += parseInt(info.amount); 
      // });

      // console.log('Total amount '+totalAmountInWords);
      entryInfoData = {
        file_number: form.value.file_number,
        date: formatDate(form.value.date),
        amount: formattedTotalAmount, // Set total amount
        amount_word: totalAmountInWords,
        status: form.value.status,
        individual_infos: approvedIndividualInfos,
        selectedSignatory: form.value.signatory_id,
        department: form.value.department_id,
        designation: form.value.from_designation,
        name: form.value.gpf_name
      };

      // console.log(entryInfoData);
      resolve(response);

      let individualInfoHTML = '';
      // Loop for individual info
      entryInfoData.individual_infos.forEach((info, index) => {
      const amountInWords = numberToWords(parseInt(info.amount.replace(/,/g, ''))); // Convert amount to words
      // edit this to make individual_infos of same size
      individualInfoHTML += `
          ${index + 1}.&nbsp ${info.name}, ${info.designation}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -&nbsp;&nbsp;&nbsp;
          ₹ ${info.amount}/-<br>
          <div style="padding-left:162px;">
              (Holder of GPA Account No. ${info.account})<br>
          </div>
      `;
      });
  
      editorContent.value = `<div align="left">
          <div style="padding-left: 200px;">
          ${entryInfoData.file_number}
          <br>
          GOVERNMENT OF MIZORAM<br>
          FINANCE DEPARTMENT(EC)<br>
          </div>
          <div style="padding-left: 270px;">
              ***
          </div>
          <br><br>

          <div style="padding-left: 400px;">
             Dated Aizawl ${entryInfoData.date}<br><br>
          </div>
          <br><br>
          To,<br>
          <div style="padding-left: 100px;">
              ${entryInfoData.designation} to the Govt. of Mizoram,<br>
              ${form.value.departments.name},
          </div>
          <br><br>

          Subject: 
          <div style="padding-left: 100px; font-weight: bold;">
              Sanction & Approval of ${entryInfoData.name} of GPF amounting to ₹ ${entryInfoData.amount}/- (Rupees ${entryInfoData.amount_word}) only.
          </div>
          <br><br>    
          Sir,<br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I am directed to convey sanction and approval of ${entryInfoData.name} of GPF amounting to ₹ ${entryInfoData.amount}/-  (Rupees ${entryInfoData.amount_word}) only in respect of the following person for various purposes.<br><br>
          ${individualInfoHTML}
          <div style=" padding-left:400px; padding-top:30px">
          Yours faithfully,<br><br><br><br>
          ${form.value.signatory.name}<br>
          ${form.value.signatory.designation}<br>
          Finance Department(APF)
        </div>

      </div>`;

    } catch (error) {
      console.error('Error fetching data:', error);
      reject(error);
    }
  });
};

// Function to format the date
const formatDate = (dateString) => {
  const date = new Date(dateString);
  const day = date.getDate();
  const monthIndex = date.getMonth();
  const year = date.getFullYear();

  // Array of month names
  const monthNames = [
    'January', 'February', 'March',
    'April', 'May', 'June', 'July',
    'August', 'September', 'October',
    'November', 'December'
  ];

  // Convert day to string with ordinal suffix
  const dayStr = day + (day % 10 === 1 && day !== 11 ? '<sup>st</sup>' : (day % 10 === 2 && day !== 12 ? '<sup>nd</sup>' : (day % 10 === 3 && day !== 13 ? '<sup>rd</sup>' : '<sup>th</sup>')));
  return `${dayStr} ${monthNames[monthIndex]}, ${year}`;
};
function numberToWords(num) {
    const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
    const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
    const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

    if (num === 0) return 'Zero';

    let result = '';

    if (num >= 10000000) {
        result += numberToWords(Math.floor(num / 10000000)) + ' Crore ';
        num %= 10000000;
    }

    if (num >= 100000) {
        result += numberToWords(Math.floor(num / 100000)) + ' Lakh ';
        num %= 100000;
    }

    if (num >= 1000) {
        result += numberToWords(Math.floor(num / 1000)) + ' Thousand ';
        num %= 1000;
    }

    if (num >= 100) {
        result += units[Math.floor(num / 100)] + ' Hundred ';
        num %= 100;
    }

    if (num >= 10 && num <= 19) {
        result += teens[num % 10];
    } else if (num >= 20) {
        result += tens[Math.floor(num / 10)];
        if (num % 10 !== 0) {
            result += '-' + units[num % 10];
        }
    } else {
        result += units[num];
    }

    return result;
}
</script>
<style scoped>
/* Hide the header and footer of the Quasar editor */
.q-editor__header {
  display: none;
}

.q-editor__footer {
  display: none;
}
</style>
