<template>
    <div class="invoices">
        
        <div class="card__header">
            <div>
                <h2 class="invoice__title">GPF</h2>
            </div>
            <div>
                <a class="btn btn-secondary">
                    New GPF
                </a>
            </div>
            <div>
                <a class="btn btn-secondary">
                    New Signatory
                </a>
            </div>
        </div>

        <div class="table card__content">
            <div class="table--filter">
                <span class="table--filter--collapseBtn ">
                    <i class="fas fa-ellipsis-h"></i>
                </span>
                <div class="table--filter--listWrapper">
                    <ul class="table--filter--list">
                        <li>
                            <p class="table--filter--link table--filter--link--active">
                                All
                            </p>
                        </li>
                        <li>
                            <p class="table--filter--link ">
                                Paid/?
                            </p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table--search">
                <div class="table--search--wrapper">
                    <select class="table--search--select" name="" id="">
                        <option value="">Filter</option>
                    </select>
                </div>
                <div class="relative">
                    <i class="table--search--input--icon fas fa-search "></i>
                    <input class="table--search--input" type="text" placeholder="Search GPF">
                </div>
            </div>

            <div class="table--heading">
                <p>ID</p>
                <p>File Number</p>
                <p>Status</p>
                <p>Signatory</p>
                <p>Amount</p>
                <p>Date</p>
            </div>

            <!-- item 1 -->
            <div class="table--items">
                <a href="#" class="table--items--transactionId">#1</a>
                <p>#093654</p>
                <p>#Approved</p>
                <p>Jonathan Yu</p>
                <p> $ 16,943</p>
                <p>Jan 18, 9:31am</p>
            </div>
        </div>
        
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';

let gpf = ref();

onMounted(async () => {
    getGpf()
})

const getGpf = async () => {
    let response = await axios.get("/api/get_all_gpf")
    console.log('response', response);
}
</script>