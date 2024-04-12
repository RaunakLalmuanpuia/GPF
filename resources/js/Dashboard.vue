<template>
    <QuasarLayout>
        <p>Dashboard</p>
        <!-- <q-btn
                        label="GPF"
                        color="primary"
                        class="q-mt-md"
                        @click="Gpf()"
                    /> -->

    </QuasarLayout>

</template>
<script setup>
import QuasarLayout from "@/Layout/Layout.vue";
import { useRouter } from 'vue-router';

const router = useRouter();

// const Gpf =async() => {
//     router.push('/gpf')
// }
</script>