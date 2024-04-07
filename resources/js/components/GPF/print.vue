<template>

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
  <q-btn
     
      label="Reset Template"
      color="primary"
      class="q-mt-md"
    />
    <q-btn
     
      label="Save Data"
      color="primary"
      class="q-mt-md"
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
    @click="printData"
    label="Print"
    color="grey"
    class="q-mt-md"
  />
    </div>
    <q-card flat bordered> </q-card>
  </div>

</template>

<script setup>
import { onMounted,ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import html2canvas from 'html2canvas';
import jspdf from 'jspdf';

const router = useRouter();
const format = ref('A4');
// const selectedFormat = ref('A4');

// const formatOptions = [
//   { label: 'A4', value: 'A4', width: '210mm', height: '297mm' }, // A4 size is 210mm x 297mm
//   { label: 'Legal', value: 'Legal', width: '216mm', height: '356mm' } // Legal size is 216mm x 356mm
// ];
// const editorWidth = ref('210mm'); // Default to A4 width
// const editorHeight = ref('297mm'); // Default to A4 height

// watch(selectedFormat, (newValue) => {
//   console.log("Selected format changed to: ", newValue);
//   const selectedOption = formatOptions.find(option => option.value === newValue.value);
//   console.log("Selected option: ", selectedOption);
//   if (!selectedOption) {
//     console.error("No matching option found for value: ", newValue);
//     return;
//   }
//   editorWidth.value = selectedOption.width;
//   editorHeight.value = selectedOption.height;
//   console.log("Updated editorWidth: ", editorWidth.value);
//   console.log("Updated editorHeight: ", editorHeight.value);
// });
const formatOptions = [
  { label: 'A4', value: 'A4', width: 210, height: 297 }, // A4 size is 210mm x 297mm
  { label: 'Legal', value: 'Legal', width: 216, height: 356 }, // Legal size is 216mm x 356mm
];
const selectedFormat = ref('A4');
const editorWidth = ref('210mm'); // Default to A4 width
const editorHeight = ref('297mm'); // Default to A4 height
// const editorContent = ref('');

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

// const fetchTemplateData = async () => {
//   try {
//     const response = await api.get('text_templates/' + templateID.value);
//     const data = response.data;
//     // Adjust data and set it to editor
//     // For example, concatenate some values from the response
//     editor.value = `${data.template.template}\n`;

//     // You can continue adding more data as per your requirement
//   } catch (error) {
//     console.error('Error fetching data:', error);
//   }
// };

onMounted(async () =>{
    await getGpf();
})

let entryInfoData;
let editorContent = ref('');


const getGpf = () => {
  return new Promise(async (resolve, reject) => {
    try {
      let response = await axios.get(`/api/show_gpf/${props.id}`);
      form.value = response.data.entry_info;

      entryInfoData = {
        file_number: form.value.file_number,
        date: form.value.date,
        amount: form.value.amount,
        status: form.value.status,
        individual_infos: form.value.individual_infos,
        selectedSignatory: form.value.signatory_id,
        department: form.value.from_deparment,
        designation: form.value.from_designation,
        name: form.value.gpf_name
      };

      console.log(entryInfoData);

      let individualInfoHTML = '';
      entryInfoData.individual_infos.forEach((info, index) => {
        individualInfoHTML += `
          ${index + 1}.&nbsp ${info.name}, ${info.designation}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -&nbsp;&nbsp;&nbsp;
          ₹ ${info.amount}/-<br>
          <div style="padding-left:162px;">
            (Holder of GPA Account No. ${info.account})<br>
            </div> 
        `;
      });

      resolve(response);

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
              The Under Secretary to the Govt. of Mizoram,<br>
              Public Health Engineering Department,
          </div>
          <br><br>

          Subject: 
          <div style="padding-left: 100px; font-weight: bold;">
              Sanction & Approval of ${entryInfoData.name} of GPF amounting to ₹ ${entryInfoData.amount}/- only.
          </div>
          <br><br>    
          Sir,<br>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;I am directed to convey sanction and approval of ${entryInfoData.name} of GPF amounting to ₹ ${entryInfoData.amount}/-  only in respect of the following person for various purposes.<br><br>
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



</script>