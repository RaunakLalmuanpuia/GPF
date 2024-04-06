<template>
<p>print Gpf</p>
{{ form }}

<div class="q-pa-md">
    <q-carousel
      animated
      v-model="slide"
      arrows
      navigation
      infinite
    >
      <q-carousel-slide :name="1" img-src="https://cdn.quasar.dev/img/mountains.jpg" />
      <q-carousel-slide :name="2" img-src="https://cdn.quasar.dev/img/parallax1.jpg" />
      <q-carousel-slide :name="3" img-src="https://cdn.quasar.dev/img/parallax2.jpg" />
      <q-carousel-slide :name="4" img-src="https://cdn.quasar.dev/img/quasar.jpg" />
    </q-carousel>
  </div>
</template>

<script setup>
import { onMounted,ref } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();

let form = ref({id:''})

let slide = ref(1);

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