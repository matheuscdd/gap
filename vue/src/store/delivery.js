import { client, consts, endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

const getDefaultState = () => ({
    delivery: {},
    deliveries: [],
    treemap: [],
});

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        storeDeliveries(state, payload) {
            state.deliveries = payload.toReversed();
        },
        storeDelivery(state, payload) {
            state.delivery = payload;
        },
        storeTreemap(state, payload) {
            state.treemap = payload;
        },
        reset(state) {
            Object.assign(state, getDefaultState());
        }
    },
    actions: {
        async createFullDelivery(ctx, data) {
            const response = await api("/deliveries/full", methods.POST, data);
            if (response.error) return alert(response.error);
            router.push(endpoints.routes.DELIVERY_LIST);
        },

        async createPartialDelivery(ctx, data) {
            const response = await api("/deliveries/partial/" + data.id , methods.PUT, data);
            if (response.error) return alert(response.error);
            router.push(endpoints.routes.DELIVERY_LIST);
            // TODO - trocar para a tela de visualização quando estiver pronta
        },

        async editFullDelivery(ctx, data) {
            const response = await api("/deliveries/full/" + data.id, methods.PATCH, data);
            if (response.error) return alert(response.error);
            ctx.commit("storeDelivery", response);
            router.push(endpoints.routes.DELIVERY_LIST);
        },

        async finishFull(ctx, id) {
            const response = await api("/deliveries/full/finish/" + id, methods.PATCH);
            if (response.error) return alert(response.error);
        },

        async storeDeliveries(ctx) {
            const response = await api("/deliveries/full");
            const data = response.map(delivery => ({
                ...delivery,
                created_at: handleDate(delivery.created_at),
                updated_at: handleDate(delivery.updated_at),
            }));
            ctx.commit("storeDeliveries", data);
        },

        async storeDelivery(ctx, id) {
            const response = await api("/deliveries/full/" + id);
            if (response.error) return;
            ctx.commit("storeDelivery", response);
        },

        async storeTreemap(ctx) {
            const response = await api("/deliveries/treemap/");
            if (response.error) return;
            ctx.commit("storeTreemap", response);
        },
    }
};