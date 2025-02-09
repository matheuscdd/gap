import { endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

const getDefaultState = () => ({
    clients: [],
    client: {}
});

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        storeClient(state, payload) {
            state.client = payload;
        },

        storeClients(state, payload) {
            state.clients = payload;
        },
        reset(state) {
            Object.assign(state, getDefaultState());
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
                created_at: new Date(user.created_at),
                updated_at: new Date(user.updated_at),
            }));
            ctx.commit("storeClients", data);
        },

        async createClient(ctx, data) {
            const response = await api("/clients", methods.POST, data);
            if (response.error) return ctx.iChoice.open(response.error, true);
            router.push(endpoints.routes.CLIENT_LIST);
        },

        async editClient(ctx, data) {
            const response = await api("/clients/" + data.id, methods.PATCH, data);
            if (response.error) return ctx.iChoice.open(response.error, true);
            ctx.commit("storeClient", response);
            router.push(endpoints.routes.CLIENT_LIST);
        },

        async delClient(ctx, id) {
            const response = await api("/clients/" + id, methods.DELETE);
            if (response.error) return ctx.iChoice.open(response.error, true);
            ctx.dispatch("storeClients");
        },
    }
};