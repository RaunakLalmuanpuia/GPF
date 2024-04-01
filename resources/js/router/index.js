import { createRouter, createWebHistory } from "vue-router";
import GpfIndex from '../components/GPF/index.vue'; 
import NotFound from "../components/NotFound.vue";

const routes = [
    {
        path:'/',
        component:GpfIndex
    },
    {
        path:'/:pathMatch(.*)*',
        component: NotFound
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router