import { endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

const getDefaultState = () => ({
    trucks: [],
    truck: {}
});

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        storeTruck(state, payload) {
            state.truck = payload;
        },

        storeTrucks(state, payload) {
            state.trucks = payload;
        },
        reset(state) {
            Object.assign(state, getDefaultState());
        },
    },
    actions: {
        async storeTruck(ctx, id) {
            const response = await api("/trucks/" + id);
            if (response.error) return;
            ctx.commit("storeTruck", response);
        },

        async storeTrucks(ctx) {
            const response = await api("/trucks");
            const data = response.map(truck => ({
                ...truck,
                created_at: new Date(truck.created_at),
                updated_at: new Date(truck.updated_at),
            }));
            ctx.commit("storeTrucks", data);
        },

        async createTruck(ctx, data) {
            const response = await api("/trucks", methods.POST, data);
            if (response.error) return alert(response.error);
            router.push(endpoints.routes.GARAGE_LIST);
        },

        async editTruck(ctx, data) {
            const response = await api("/trucks/" + data.id, methods.PATCH, data);
            if (response.error) return alert(response.error);
            ctx.commit("storeTruck", response);
            router.push(endpoints.routes.GARAGE_LIST);
        },

        async delTruck(ctx, id) {
            const response = await api("/trucks/" + id, methods.DELETE);
            if (response.error) return alert(response.error);
            ctx.dispatch("storeTrucks");
        },
    }
};