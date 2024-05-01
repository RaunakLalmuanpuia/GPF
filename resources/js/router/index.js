import { createRouter, createWebHistory } from "vue-router";
import GpfIndex from '../components/GPF/index.vue'; 
import gpfNew from '../components/GPF/new.vue';
import GpfShow from '../components/GPF/show.vue';
import NotFound from "../components/NotFound.vue";
import GpfEdit from "../components/GPF/edit.vue";
import GpfPrint from "../components/GPF/print.vue";
import Individual from "../components/GPF/individual.vue";
import Dashboard from "../Dashboard.vue";
import Login from "../Login.vue";
import Profile from "../Profile.vue";
import Departments from "../Departments.vue";
import Reports from "../Report.vue";
import Sign from "../Signatory.vue";
import { isAuthenticated } from '../auth/auth';

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
        component:GpfIndex,
        meta: { requiresAuth: true }
    },
    {
        path:'/gpf/new',
        component:gpfNew,
        meta: { requiresAuth: true }
    },
    {
        path:'/gpf/show/:id',
        component:GpfShow,
        props:true,
        meta: { requiresAuth: true }
    },
    {
        path:'/gpf/edit/:id',
        component:GpfEdit,
        props:true,
        meta: { requiresAuth: true }
    },
    {
        path:'/gpf/print/:id',
        component:GpfPrint,
        props:true,
        meta: { requiresAuth: true }
    },
    {
        path:'/profile',
        component:Profile,
        props:true,
        meta: { requiresAuth: true }
    },
    {
        path:'/departments',
        component: Departments,
        // props:true,
        meta: { requiresAuth: true }
    },
    {
        path:'/individual',
        component:Individual,
        // props:true,
        meta: { requiresAuth: true }
    },
    {
        path:'/report',
        component:Reports,
        // props:true,
        meta: { requiresAuth: true }
    },
    {
        path:'/sign',
        component:Sign,
        // props:true,
        meta: { requiresAuth: true }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // Check if the user is authenticated
        if (!isAuthenticated()) {
            // If not authenticated, redirect to login page
            next('/login');
        } else {
            // If authenticated, proceed to the requested route
            next();
        }
    } else {
        // If the route does not require authentication, proceed as usual
        next();
    }
});

export default router

