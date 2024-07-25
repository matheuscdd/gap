import { consts, endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";
import { jwtDecode } from "jwt-decode";
import { createStore } from "vuex";

export default createStore({
    state: {
        user: {},
        users: [],
    },
    mutations: {
        storeUser(state, payload) {
            state.user = payload;
        },

        storeUsers(state, payload) {
            state.users = payload;
        }
    },
    getters: {
        
    },
    actions: {
        async storeUser(ctx, id = localStorage.getItem(consts.USER_ID)) {
            const response = await api("/users/" + id);
            ctx.commit("storeUser", response);
        },

        async storeUsers(ctx) {
            const response = await api("/users/");
            const data = response.map(user => ({
                ...user,
                created_at: handleDate(user.created_at),
                updated_at: handleDate(user.updated_at),
            }));
            ctx.commit("storeUsers", data);
        },

        async storeLogin(ctx, data) {
            const response = await api("/auth/login", methods.POST, data);
            if (response.error) return alert(response.error);
            const userId = jwtDecode(response.token).sub;
            localStorage.setItem(consts.TOKEN, response.token);
            localStorage.setItem(consts.USER_ID, userId);
            ctx.dispatch("storeUser", userId);
            router.push(endpoints.routes.HOME);
        }
    }
});