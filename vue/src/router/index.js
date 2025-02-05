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
        path: endpoints.routes.BUDGET_VIEW,
        name: endpoints.names.BUDGET_VIEW,
        component: () => import("@/views/budgets/BudgetsView.vue")
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
        path: endpoints.routes.DELIVERY_EDIT_FULL_FULL,
        name: endpoints.names.DELIVERY_EDIT_FULL,
        component: () => import("@/views/deliveries/DeliveriesFullEdit.vue")
    },
    {
        path: endpoints.routes.DELIVERY_DASH,
        name: endpoints.names.DELIVERY_DASH,
        component: () => import("@/views/deliveries/DeliveriesDash.vue")
    },
    {
        path: endpoints.routes.DELIVERY_CALENDAR,
        name: endpoints.names.DELIVERY_CALENDAR,
        component: () => import("@/views/deliveries/DeliveriesCalendar.vue")
    },
    {
        path: endpoints.routes.DELIVERY_CREATE_PARTIAL,
        name: endpoints.names.DELIVERY_CREATE_PARTIAL,
        component: () => import("@/views/deliveries/DeliveriesPartialCreate.vue")
    },
    {
        path: endpoints.routes.DELIVERY_VIEW_FULL,
        name: endpoints.names.DELIVERY_VIEW_FULL,
        component: () => import("@/views/deliveries/DeliveriesFullView.vue")
    },
    {
        path: endpoints.routes.TRUCK_CREATE,
        name: endpoints.names.TRUCK_CREATE,
        component: () => import("@/views/trucks/TrucksCreate.vue")
    },
    {
        path: endpoints.routes.TRUCK_EDIT,
        name: endpoints.names.TRUCK_EDIT,
        component: () => import("@/views/trucks/TrucksEdit.vue")
    },
    {
        path: endpoints.routes.MAINTENANCE_CREATE,
        name: endpoints.names.MAINTENANCE_CREATE,
        component: () => import("@/views/maintenances/MaintenancesCreate.vue")
    },
    {
        path: endpoints.routes.MAINTENANCE_EDIT,
        name: endpoints.names.MAINTENANCE_EDIT,
        component: () => import("@/views/maintenances/MaintenancesEdit.vue")
    },
    {
        path: endpoints.routes.DRIVER_CREATE,
        name: endpoints.names.DRIVER_CREATE,
        component: () => import("@/views/drivers/DriversCreate.vue")
    },
    {
        path: endpoints.routes.DRIVER_EDIT,
        name: endpoints.names.DRIVER_EDIT,
        component: () => import("@/views/drivers/DriversEdit.vue")
    },
    {
        path: endpoints.routes.DRIVER_LIST,
        name: endpoints.names.DRIVER_LIST,
        component: () => import("@/views/drivers/DriversList.vue")
    },
    {
        path: endpoints.routes.GARAGE_LIST,
        name: endpoints.names.GARAGE_LIST,
        component: () => import("@/views/garage/GarageList.vue")
    },
    {
        path: endpoints.routes.GARAGE_DASH,
        name: endpoints.names.GARAGE_DASH,
        component: () => import("@/views/garage/GarageDash.vue")
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

    if (from.fullPath === endpoints.routes.DELIVERY_CREATE_FULL) {
        store.commit("deliveryMod/storeBudget", {});
    }

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