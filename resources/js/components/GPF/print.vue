<template>
<p>print Gpf</p>
{{ form }}
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
    getGpf()
})

const getGpf = async () => {
    let response = await axios.get(`/api/show_gpf/${props.id}`)
    form.value = response.data.entry_info
}

</script>