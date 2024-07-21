import { consts, endpoints, methods } from "@/common/consts";
import { api, getUserId } from "@/common/utils";
import router from "@/router";
import { jwtDecode } from "jwt-decode";
import { createStore } from "vuex";

export default createStore({
    state: {
        user: {}
    },
    mutations: {
       storeUser(state, payload) {

       }
    },
    getters: {
        
    },
    actions: {
        async storeUser(ctx, {id = localStorage.getItem("userId")} = {}) {
            const res = await api("/users/" + id);
            console.log(res);
            ctx.state.user = res;
        },
        async storeLogin(_, data) {
            localStorage.removeItem(consts.TOKEN);
            const res = await api("/auth/login", methods.POST, data);
            if (res.error) return alert(res.error);
            const userId = jwtDecode(res.token).sub;
            localStorage.setItem(consts.TOKEN, res.token);
            localStorage.setItem("userId", userId);
            router.push(endpoints.routes.HOME);
        }
    }
});