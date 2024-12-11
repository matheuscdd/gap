import { consts, endpoints, user as cUser } from "@/common/consts";
import { refreshToken } from "@/common/utils";
import store from "@/store";
import { createRouter, createWebHistory } from "vue-router";

const routes = [
    {
        path: endpoints.routes.INITIAL,
        name: endpoints.names.INITIAL,
        redirect: endpoints.routes.HOME
    },
    {
        path: endpoints.routes.LOGIN,
        name: endpoints.names.LOGIN,
        component: () => import("../views/auth/Login.vue")
    },
    {
        path: endpoints.routes.HOME,
        name: endpoints.names.HOME,
        component: () => import("../views/common/Home.vue")
    },
    {
        path: endpoints.routes.USER_LIST,
        name: endpoints.names.USER_LIST,
        component: () => import("../views/users/UsersList.vue")
    },
    {
        path: endpoints.routes.USER_EDIT,
        name: endpoints.names.USER_EDIT,
        component: () => import("@/views/users/UsersEdit.vue")
    },
    {
        path: endpoints.routes.USER_CREATE,
        name: endpoints.names.USER_CREATE,
        component: () => import("@/views/users/UsersCreate.vue")
    },
    {
        path: endpoints.routes.CLIENT_CREATE,
        name: endpoints.names.CLIENT_CREATE,
        component: () => import("@/views/clients/ClientsCreate.vue")
    },
    {
        path: endpoints.routes.CLIENT_LIST,
        name: endpoints.names.CLIENT_LIST,
        component: () => import("@/views/clients/ClientsList.vue")
    },
    {
        path: endpoints.routes.CLIENT_EDIT,
        name: endpoints.names.CLIENT_EDIT,
        component: () => import("@/views/clients/ClientsEdit.vue")
    },
    {
        path: endpoints.routes.BUDGET_CREATE,
        name: endpoints.names.BUDGET_CREATE,
        component: () => import("@/views/budgets/BudgetsCreate.vue")
    },
    {
        path: endpoints.routes.BUDGET_EDIT,
        name: endpoints.names.BUDGET_EDIT,
        component: () => import("@/views/budgets/BudgetsEdit.vue")
    },
    {
        path: endpoints.routes.BUDGET_LIST,
        name: endpoints.names.BUDGET_LIST,
        component: () => import("@/views/budgets/BudgetsList.vue")
    },
    {
        path: endpoints.routes.DELIVERY_LIST,
        name: endpoints.names.DELIVERY_LIST,
        component: () => import("@/views/deliveries/DeliveriesList.vue")
    },
    {
        path: endpoints.routes.DELIVERY_CREATE_FULL,
        name: endpoints.names.DELIVERY_CREATE_FULL,
        component: () => import("@/views/deliveries/DeliveriesFullCreate.vue")
    },
    {
        path: endpoints.routes.DELIVERY_EDIT_FULL,
        name: endpoints.names.DELIVERY_EDIT,
        component: () => import("@/views/deliveries/DeliveriesFullEdit.vue")
    },
    {
        path: endpoints.routes.DELIVERY_DASH,
        name: endpoints.names.DELIVERY_DASH,
        component: () => import("@/views/deliveries/DeliveriesDash.vue")
    },
    {
        path: endpoints.routes.NOT_FOUND,
        name: endpoints.names.NOT_FOUND,
        component: () => import("../views/common/NotFound.vue")
    },
];

const router = createRouter({
    history: createWebHistory("/app/"),
    routes
});

router.beforeEach(async (to, from, next) => {
    const token = localStorage.getItem(consts.TOKEN);
    const isLogin = to.path === endpoints.routes.LOGIN;
    const path = !isLogin ? endpoints.routes.LOGIN : undefined;
    if (!token) return next(path);

    await refreshToken();
    if (!localStorage.getItem(consts.TOKEN)) return next(path);
    
    if (isLogin) return next(endpoints.routes.HOME);

    const protectedRoutes = [endpoints.routes.USER_LIST, endpoints.routes.USER_EDIT, endpoints.routes.USER_CREATE];
    if (!protectedRoutes.includes(to.path)) return next();
    const userId = localStorage.getItem(consts.USER_ID);
    await store.dispatch("userMod/storeLogged", userId);
    if (store.state.userMod.logged.type !== cUser.keys.TYPE.ADMIN) return next(endpoints.routes.HOME);
    return next();
});

export default router;