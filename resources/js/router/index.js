import { createRouter, createWebHistory } from "vue-router";
import GpfIndex from '../components/GPF/index.vue'; 
import gpfNew from '../components/GPF/new.vue';
import GpfShow from '../components/GPF/show.vue';
import NotFound from "../components/NotFound.vue";
import GpfEdit from "../components/GPF/edit.vue";
import GpfPrint from "../components/GPF/print.vue";
import Dashboard from "../Dashboard.vue";
import Login from "../Login.vue";


const routes = [
    {
        path:'/',
        component:Dashboard
    },
    {
        path:'/login',
        component:Login
    },
    {
        path:'/:pathMatch(.*)*',
        component: NotFound
    },
    {
        path:'/gpf',
        component:GpfIndex
    },
    {
        path:'/gpf/new',
        component:gpfNew
    },
    {
        path:'/gpf/show/:id',
        component:GpfShow,
        props:true
    },
    {
        path:'/gpf/edit/:id',
        component:GpfEdit,
        props:true
    },
    {
        path:'/gpf/print/:id',
        component:GpfPrint,
        props:true
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router

