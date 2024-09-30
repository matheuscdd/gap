import { client, consts, endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

const getDefaultState = () => ({
    delivery: {},
    deliveries: [],
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
        reset(state) {
            Object.assign(state, getDefaultState());
        }
    },
    actions: {
        async createDelivery(ctx, data) {
            const response = await api("/deliveries", methods.POST, data);
            if (response.error) return alert(response.error);
            router.push(endpoints.routes.DELIVERY_LIST);
        },

        async editDelivery(ctx, data) {
            const response = await api("/deliveries/" + data.id, methods.PATCH, data);
            if (response.error) return alert(response.error);
            ctx.commit("storeDelivery", response);
            router.push(endpoints.routes.DELIVERY_LIST);
        },

        async storeDeliveries(ctx) {
            const response = await api("/deliveries");
            const data = response.map(delivery => ({
                ...delivery,
                created_at: handleDate(delivery.created_at),
                updated_at: handleDate(delivery.updated_at),
            }));
            ctx.commit("storeDeliveries", data);
        },

        async storeDelivery(ctx, id) {
            const response = await api("/deliveries/" + id);
            if (response.error) return;
            ctx.commit("storeDelivery", response);
        },
    }
};