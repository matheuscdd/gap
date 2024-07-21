import { consts, endpoints, methods } from "@/common/consts";
import { api } from "@/common/utils";
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

    const res = await api("/auth/refresh", methods.PUT, {token});
    if (!res.token) {
        localStorage.removeItem(consts.TOKEN);
        return next(path);
    }

    localStorage.setItem(consts.TOKEN, res.token);
    if (isLogin) return next(endpoints.routes.HOME);
    return next();
});

export default router;