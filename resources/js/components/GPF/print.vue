<template>
   <QuasarLayout>
  <div class="q-pa-md q-gutter-sm column justify-center items-center q-my-md">
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
<div class="row  q-gutter-md">
      <q-select
      v-model="selectedFormat"
      :options="formatOptions"
      label="Select Paper Size"
      outlined
      class=" q-mt-md " style="width: 200px;"
    />


    <q-btn
    @click="printData()"
    label="Print"
    color="grey"
    class="q-mt-md"
  />
    </div>
    <q-card flat bordered> </q-card>
  </div>
</QuasarLayout>
</template>

<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { onMounted,ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import html2canvas from 'html2canvas';
import jspdf from 'jspdf';

const router = useRouter();
const format = ref('A4');

const exist = ref(false);
const temp_id = ref('');
const new_template = ref(false);

console.log('New Template'+new_template.value);

const formatOptions = [
  { label: 'A4', value: 'A4', width: 210, height: 297 }, // A4 size is 210mm x 297mm
  { label: 'Legal', value: 'Legal', width: 216, height: 356 }, // Legal size is 216mm x 356mm
];
const selectedFormat = ref('A4');
const editorWidth = ref('210mm'); // Default to A4 width
const editorHeight = ref('297mm'); // Default to A4 height


watch(selectedFormat, (newValue) => {
  const selectedOption = formatOptions.find((option) => option.value === newValue);
  if (selectedOption) {
    editorWidth.value = `${selectedOption.width}mm`;
    editorHeight.value = `${selectedOption.height}mm`;
  }
});




let form = ref({id:''})

let slide = ref(1);

const props = defineProps({
    id:{
        type:String,
        default:''
    }
})

const printData = async () => {
  try {
    // Get the dimensions of the editor content
    const editorElement = document.querySelector('.q-editor__content');
    const width = editorElement.offsetWidth;
    const height = editorElement.offsetHeight;

    // Convert the editor content to a canvas element
    const canvas = await html2canvas(editorElement, {
      width: width,
      height: height,
      useCORS: true,
    });

    // Create a new PDF document with A4 size
    const doc = new jspdf('p', 'mm', 'a4');

    // Add the canvas content to the PDF document
    doc.addImage(canvas.toDataURL('image/jpeg'), 'JPEG', 0, 0, 210, 297);
    doc.save(`routine_sheet.pdf`);
  } catch (error) {
    console.error('Error printing data:', error);
  }
};

onMounted(async () =>{
    await getGpf();
    await checkExist();
})

let entryInfoData;
let editorContent = ref('');



//get the template if it exists
const fetchTemplateData = async () => {
  try {

    let response = await axios.get(`/api/approval_templates/${props.id}`);
    // const response = await axios.get('/api/text_templates/' + form.id);


    // console.log(response.data.template.contents)
    // Adjust data and set it to editor
    // For example, concatenate some values from the response
     editorContent.value = response.data.template.contents;

    // You can continue adding more data as per your requirement
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};
// check if template exist or not
const checkExist = async () => {
  try {
    
    const entryInfoData = {
      entry_info: form.value.id,
      purpose: "Approval",
    };
    // let response = await axios.get("/api/get_entry_info")
    
    let response = await axios.post('/api/check_existence',entryInfoData);
    exist.value = response.data.exists == true ? true :false;
    temp_id.value =response.data.id;
    console.log('Template id ' + temp_id.value);
    if(exist.value == true){
      console.log("EXIST")
      fetchTemplateData();
    }
    else{
      console.log("NOT EXIST")
      getGpftemplate();
    }
    // You can continue adding more data as per your requirement
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

// save the current template
// const saveData = async () => {
//   try {
//     // Send editor value to API for saving
//     if (exist.value) {
//       await api.put('text_templates/' + templateID.value, {
//         'routine_sheet_id':id,
//         'purpose':purpose.value,
//         'template':editor.value,
//         'format':selectedFormat.value
//       });
//       Notify.create({
//           type: 'secondary',

//           message: 'Template Updated!',
//         });
//     } else

//     {
//       var form = new FormData();
//       form.append('routine_sheet_id', id);
//       form.append('purpose', purpose.value);
//       form.append('template', editor.value);
//       form.append('format', format.value);
//       await api.post('text_templates', form);
//       Notify.create({
//           type: 'secondary',

//           message: 'Data saved successfully!',
//         });
//     }

//     console.log('Data saved successfully!');
//   } catch (error) {
//     console.error('Error saving data:', error);
//   }
// };

const saveTemplate = async() => {
  try {
    // Prepare data for entry_info
    const entryInfoData = {
      template_id: temp_id.value,
      purpose: "approval",
      content: editorContent.value,
      new: new_template.value,
    };
    axios.post(`/api/save_approval_template/${form.value.id}`, entryInfoData);

    alert('success');
  } catch (error) {
    console.error('Error:', error);
  }
}
const getGpf = async () => {
    let response = await axios.get(`/api/edit_gpf/${props.id}`)
    // console.log('form', response.data.entry_info);
    form.value = response.data.entry_info
}

const getGpftemplate = () => {
  new_template.value = true;
  console.log('New Template'+new_template.value);
  return new Promise(async (resolve, reject) => {
    try {
      let response = await axios.get(`/api/show_gpf/${props.id}`);
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

      console.log('Total amount '+totalAmountInWords);
      entryInfoData = {
        file_number: form.value.file_number,
        date: formatDate(form.value.date),
        amount: formattedTotalAmount, // Set total amount
        amount_word: totalAmountInWords,
        status: form.value.status,
        individual_infos: approvedIndividualInfos,
        selectedSignatory: form.value.signatory_id,
        department: form.value.from_deparment,
        designation: form.value.from_designation,
        name: form.value.gpf_name
      };

      console.log(entryInfoData);
      resolve(response);

      let individualInfoHTML = '';
      // Loop for individual info
      entryInfoData.individual_infos.forEach((info, index) => {
      const amountInWords = numberToWords(parseInt(info.amount.replace(/,/g, ''))); // Convert amount to words
      individualInfoHTML += `
          ${index + 1}.&nbsp ${info.name}, ${info.designation}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -&nbsp;&nbsp;&nbsp;
          ₹ ${info.amount}/-<br>
          <div style="padding-left:162px;">
              (Holder of GPA Account No. ${info.account})<br>
          </div>
      `;
      });
      // entryInfoData.individual_infos.forEach((info, index) => {
      //   individualInfoHTML += `
      //     ${index + 1}.&nbsp ${info.name}, ${info.designation}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -&nbsp;&nbsp;&nbsp;
      //     ₹ ${info.amount}/-<br>
      //     <div style="padding-left:162px;">
      //       (Holder of GPA Account No. ${info.account})<br>
      //       </div>
      //   `;
      // });



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
              ${entryInfoData.designation},<br>
              ${entryInfoData.department},
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