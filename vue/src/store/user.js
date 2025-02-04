import { consts, endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";
import { jwtDecode } from "jwt-decode";

const getDefaultState = () => ({
    logged: {},
    user: {
        email: "",
        name: ""
    },
    users: [],
});

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        storeLogged(state, payload) {
            state.logged = payload;
        },

        storeUser(state, payload) {
            state.user = payload;
        },

        storeUsers(state, payload) {
            state.users = payload;
        },
        reset(state) {
            Object.assign(state, getDefaultState());
        }
    },
    getters: {
        
    },
    actions: {
        async delUser(ctx, id) {
            if (ctx.state.user.id === id) return alert("O usuário não pode excluir a si mesmo");
            const response = await api("/users/" + id, methods.DELETE);
            if (response.error) return alert(response.error);
            ctx.commit("storeUser", response);
            await ctx.dispatch("storeUsers");
        },

        async editUser(ctx, data) {
            const response = await api("/users/" + data.id, methods.PATCH, data);
            if (response.error) return alert(response.error);
            ctx.commit("storeUser", response);
            router.push(endpoints.routes.USER_LIST);
        },

        async createUser(ctx, data) {
            const response = await api("/users", methods.POST, data);
            if (response.error) return alert(response.error);
            router.push(endpoints.routes.USER_LIST);
        },

        async storeUser(ctx, id) {
            const response = await api("/users/" + id);
            if (response.error) return;
            ctx.commit("storeUser", response);
        },

        async storeLogged(ctx, id = localStorage.getItem(consts.USER_ID)) {
            const response = await api("/users/" + id);
            ctx.commit("storeLogged", response);
        },

        async storeUsers(ctx) {
            const response = await api("/users");
            const data = response.map(user => ({
                ...user,
                created_at: new Date(user.created_at),
                updated_at: new Date(user.updated_at),
            }));
            ctx.commit("storeUsers", data);
        },

        async storeLogin(ctx, data) {
            const response = await api("/auth/login", methods.POST, data);
            if (response.error) return alert(response.error);
            const userId = jwtDecode(response.token).sub;
            localStorage.setItem(consts.TOKEN, response.token);
            localStorage.setItem(consts.USER_ID, userId);
            ctx.dispatch("storeLogged", userId);
            router.push(endpoints.routes.HOME);
        }
    }
};