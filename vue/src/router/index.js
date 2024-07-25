import { consts, endpoints, methods } from "@/common/consts";
import { api, refreshToken } from "@/common/utils";
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
        component: () => import("../views/Login.vue")
    },
    {
        path: endpoints.routes.HOME,
        name: endpoints.names.HOME,
        component: () => import("../views/Home.vue")
    },
    {
        path: endpoints.routes.USERS,
        name: endpoints.names.USERS,
        component: () => import("../views/Users.vue")
    },
    {
        path: endpoints.routes.NOT_FOUND,
        name: endpoints.names.NOT_FOUND,
        component: () => import("../views/NotFound.vue")
    },
];

const router = createRouter({
    history: createWebHistory(),
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

    const protectedRoutes = [endpoints.routes.USERS];
    if (!protectedRoutes.includes(to.path)) return next();
    const userId = localStorage.getItem(consts.USER_ID);
    await store.dispatch("storeUser", userId);
    if (store.state.user.type !== "admin") return next(endpoints.routes.HOME);
    return next();
});

export default router;