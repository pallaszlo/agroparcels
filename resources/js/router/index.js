import Vue from "vue";
import Router from "vue-router";
import store from "../store";

Vue.use(Router);

import Home from "../components/Home.vue";
import Regisztracio from "../components/auth/regisztracio/Regisztracio.vue";
import Login from "../components/auth/login/Login.vue";
import Dashboard from "../components/dashboard/Dashboard.vue";
import Index from "../components/Index.vue";
import NotFound from "../components/NotFound.vue";
import Unauthorized from "../components/Unauthorized.vue";
import PasswordWrapper from "../components/auth/password/PasswordWrapper.vue";
import PasswordForgot from "../components/auth/password/PasswordForgot.vue";
import PasswordReset from "../components/auth/password/PasswordReset.vue";


// Admin
import Profil from "../components/dashboard/admin/Profil.vue";
import AdminDashboard from "../components/dashboard/admin/AdminDashboard.vue";
import Users from "../components/dashboard/admin/Users.vue";
import Companies from "../components/dashboard/admin/Companies.vue";
import Groups from "../components/dashboard/admin/Groups.vue";
import Layers from "../components/dashboard/admin/Layers.vue";
import Parcels from "../components/dashboard/admin/Parcels.vue";
import Cultures from "../components/dashboard/admin/Cultures.vue";
import Diseases from "../components/dashboard/admin/Diseases.vue";
import FitoProducts from "../components/dashboard/admin/FitoProducts.vue";
import WorkMachines from "../components/dashboard/admin/WorkMachines.vue";
import Seasons from "../components/dashboard/admin/Seasons.vue";
import Persons from "../components/dashboard/admin/Persons.vue";
import Works from "../components/dashboard/admin/Works.vue";
import Treatments from "../components/dashboard/admin/Treatments.vue";
import Layer_details from "../components/dashboard/admin/Layer_details.vue";
import Parcel_details from "../components/dashboard/admin/Parcel_details.vue";
import Person_details from "../components/dashboard/admin/Person_details.vue";
import Operations from "../components/dashboard/admin/Operations.vue";
import Operation_details from "../components/dashboard/admin/Operation_details.vue";
import WorkerOperations from "../components/dashboard/user/WorkerOperations.vue";
import WorkerOperationDetails from "../components/dashboard/user/WorkerOperationDetails.vue";
import PersonalDetails from "../components/dashboard/user/PersonalDetails.vue";

const router = new Router({
    //mode: 'history',
    //base: 'localhost/laravel/sulidesi',
    //base: process.env.BASE_URL,
    //base: '/${window._locale}/',
    routes: [
        {
            name: "index",
            path: "/",
            redirect: "/home",
            component: Index,
            children: [
                {
                    name: "home",
                    path: "/home",
                    component: Home
                },
                {
                    name: "regisztracio",
                    path: "/regisztracio",
                    component: Regisztracio
                },
                {
                    name: "login",
                    path: "/login",
                    component: Login
                },
                {
                    path: "/password",
                    component: PasswordWrapper,
                    children: [
                        {
                            name: "forgot",
                            path: "",
                            component: PasswordForgot
                        },
                        {
                            name: "reset",
                            path: "reset/:token",
                            component: PasswordReset
                        }
                    ]
                },
            ]
        },
        // Dashboard
        {
            name: "dashboard",
            path: "/dashboard",
            component: Dashboard,
            meta: {
                auth: true
            },
            children: [
                // Admin
                {
                    name: "profil",
                    path: "/dashboard/profil",
                    component: Profil,
                    meta: {
                        adminAuth: false
                    },
                },
                {
                    name: "admin_dashboard",
                    path: "/dashboard/admin",
                    component: AdminDashboard,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "users",
                    path: "/dashboard/users",
                    component: Users,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "companies",
                    path: "/dashboard/companies",
                    component: Companies,
                    meta: {
                        superAdminAuth: true
                    },
                },
                {
                    name: "groups",
                    path: "/dashboard/groups",
                    component: Groups,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "layers",
                    path: "/dashboard/layers",
                    component: Layers,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: 'layer_details',
                    path: '/dashboard/layers/:uuid',
                    component: Layer_details,
                    meta: {
                        adminAuth: true
                    }
                },
                {
                    name: "parcels",
                    path: "/dashboard/parcels",
                    component: Parcels,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: 'parcel_details',
                    path: '/dashboard/parcels/:uuid',
                    component: Parcel_details,
                    meta: {
                        adminAuth: true
                    }
                },
                {
                    name: "cultures",
                    path: "/dashboard/cultures",
                    component: Cultures,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "diseases",
                    path: "/dashboard/diseases",
                    component: Diseases,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "fitoproducts",
                    path: "/dashboard/fitoproducts",
                    component: FitoProducts,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "workmachines",
                    path: "/dashboard/workmachines",
                    component: WorkMachines,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "seasons",
                    path: "/dashboard/seasons",
                    component: Seasons,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "persons",
                    path: "/dashboard/persons",
                    component: Persons,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "person_details",
                    path: '/dashboard/persons/:uuid',
                    component: Person_details,
                    meta: {
                        adminAuth: true
                    }
                },
                {
                    name: "works",
                    path: "/dashboard/works",
                    component: Works,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "treatments",
                    path: "/dashboard/treatments",
                    component: Treatments,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "operations",
                    path: "/dashboard/operations",
                    component: Operations,
                    meta: {
                        adminAuth: true
                    },
                },
                {
                    name: "operation_details",
                    path: '/dashboard/operations/:uuid',
                    component: Operation_details,
                    meta: {
                        adminAuth: true
                    }
                },
                // User
                {
                    name: "user_dashboard",
                    path: "/dashboard/user_dashboard",
                    component: WorkerOperations,
                },
                {
                    name: "user_operation_details",
                    path: "/dashboard/user_dashboard/:uuid",
                    component: WorkerOperationDetails,
                },
                {
                    name: "user_personal_details",
                    path: "/dashboard/personal_details",
                    component: PersonalDetails,
                }

            ],
            //beforeEnter: requireAuth
        },
        {
            name: "notfound",
            path: "*",
            component: NotFound
        },
        {
            name: "unauthorized",
            path: "*",
            component: Unauthorized
        }
    ]
});

router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem('user');
    const role = localStorage.getItem('role');
    //console.log(role);
    //console.log(role=='super-admin');
    if (to.matched.some(record => record.meta.auth) && !loggedIn) {
        next('/login')
        return
    } else if (to.meta.adminAuth && role !== 'super-admin' && role !== 'admin') {
        //next('/login');
        next({name: "unauthorized"});
        return
    }else if (to.meta.superAdminAuth && role !== 'super-admin') {
        //next('/login');
        next({name: "unauthorized"});
        return
    }
     else {
        next();
    }
})

export default router;
