import { client, consts, endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

export default {
    namespaced: true,
    state: {
        clients: [],
        client: {}
    },
    mutations: {
        storeClient(state, payload) {
            state.client = payload;
        },

        storeClients(state, payload) {
            state.clients = payload;
        },
    },
    actions: {
        async storeClient(ctx, id) {
            const response = await api("/clients/" + id);
            if (response.error) return;
            ctx.commit("storeClient", response);
        },

        async storeClients(ctx) {
            const response = await api("/clients");
            const data = response.map(user => ({
                ...user,
                created_at: handleDate(user.created_at),
                updated_at: handleDate(user.updated_at),
            }));
            ctx.commit("storeClients", data);
        },

        async createClient(ctx, data) {
            const response = await api("/clients", methods.POST, data);
            if (response.msg) return alert(response.msg);
            router.push(endpoints.routes.CLIENT_LIST);
        },

        async editClient(ctx, data) {
            const response = await api("/clients/" + data.id, methods.PATCH, data);
            if (response.msg) return alert(response.msg);
            ctx.commit("storeClient", response);
            router.push(endpoints.routes.CLIENT_LIST);
        },
    }
};