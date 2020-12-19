import Vue from 'vue';
import Router from 'vue-router';
import Login from './components/Login.vue';
import Dashboard from './components/Dashboard.vue';
import Register from  './components/Register.vue';
import Articles from './components/Articles.vue';
import Topics from './components/Topics.vue';
import Subjects from './components/Subjects.vue';
import store from './store';

Vue.use(Router);

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: { auth: true }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { auth: false }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { auth: false }
    },
    {
        path: '/logout',
        name: 'logout',
        meta: { auth: true }
    },
    {
        path: '/articles',
        name: 'articles',
        component: Articles,
        meta: { auth: true }
    },
    {
        path: '/topics',
        name: 'topics',
        component: Topics,
        meta: { auth: true }
    },
    {
        path: '/subjects',
        name: 'subjects',
        component: Subjects,
        meta: { auth: true }
    }
];

const router = new Router({
    routes: routes,
	scrollBehavior() {
		return {x: 0, y: 0};
	}
});

router.beforeEach((to, from, next) => {
    to.matched.some((record) => {
        if (record.meta.auth)
        {
            if (to.name == 'logout') {
                store.dispatch('logout');
                next({name: 'login'});
                return;
            }
            
            if (store.getters.isAuthenticated) {
                next();
            }
            else {
                next({name: 'login'});
            }
        }
        else 
        {
            if (store.getters.isAuthenticated) {
                next({name: 'dashboard'});
            }
            else {
                next();
            }
        }
    });
});


export default router