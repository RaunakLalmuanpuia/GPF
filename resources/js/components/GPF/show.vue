<template>
    <div class="container">
             <!--==================== SHOW GPF ====================-->
        <div class="invoices">
            
            <div class="card__header">
                <div>
                    <h2 class="invoice__title">GPF Account</h2>
                </div>
                <div>
                    
                </div>
            </div>
            <div>
                <div class="card__header--title ">
                    <h1 class="mr-2">{{ form.file_number }}</h1>
                    <p>{{ form.date }} </p>
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
                            <button class="selectBtnFlat">
                                <i class=" fas fa-reply"></i>
                                Edit
                            </button>
                            <!-- End Select Btn Option -->
                        </li>
                        <li>
                            <!-- Select Btn Option -->
                            <button class="selectBtnFlat ">
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

                
                <!-- <div class="invoice__header--item">
                    <div>
                        <h2>Invoice To:</h2>
                        <p>Customer 1</p>
                    </div>
                    <div>
                            <div class="invoice__header--item1">
                                <p>Invoice#</p>
                                <span>#1200</span>
                            </div>
                            <div class="invoice__header--item2">
                                <p>Date</p>
                                <span>12/12/2022</span>
                            </div>
                            <div class="invoice__header--item2">
                                <p>Due Date</p>
                                <span>12/12/2022</span>
                            </div>
                            <div class="invoice__header--item2">
                                <p>Reference</p>
                                <span>1045</span>
                            </div>
                        
                    </div>
                </div> -->

                <div class="table py1" style="padding-top: 20px;">

                    <div class="table--heading3">
                        <p>#</p>
                        <p>Name</p>
                        <p>Phone</p>
                        <p>Designation</p>
                        <p>account</p>
                        <p>Amount</p>
                    </div>
        
                    <!-- item 1 -->
                    <div class="table--items3" v-for="(item, i) in form.individual_infos" :key="item.id">
                        <p>{{ i+1 }}</p>
                        <p>{{ item.name }}</p>
                        <p>{{ item.phone }}</p>
                        <p>{{ item.designation }}</p>
                        <p>{{ item.account }}</p>
                        <p>${{ item.amount }}</p>

                        
                    </div>
                   
                </div>

                

                

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
import { routerKey } from 'vue-router';



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
</script>