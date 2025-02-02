import { endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

const getDefaultState = () => ({
    maintenances: [],
    maintenance: {}
});

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        storeMaintenance(state, payload) {
            state.maintenance = payload;
        },

        storeMaintenances(state, payload) {
            state.maintenances = payload;
        },
        reset(state) {
            Object.assign(state, getDefaultState());
        },
    },
    actions: {
        async storeMaintenance(ctx, id) {
            const response = await api("/maintenances/" + id);
            if (response.error) return;
            ctx.commit("storeMaintenance", response);
        },

        async storeMaintenances(ctx) {
            const response = await api("/maintenances");
            const data = response.map(maintenance => ({
                ...maintenance,
                date: handleDate(maintenance.date),
                created_at: handleDate(maintenance.created_at),
                updated_at: handleDate(maintenance.updated_at),
            }));
            ctx.commit("storeMaintenances", data);
        },

        async createMaintenance(ctx, data) {
            const response = await api("/maintenances", methods.POST, data);
            if (response.error) return alert(response.error);
            router.push(endpoints.routes.GARAGE_LIST);
        },

        async editMaintenance(ctx, data) {
            const response = await api("/maintenances/" + data.id, methods.PATCH, data);
            if (response.error) return alert(response.error);
            ctx.commit("storeMaintenance", response);
            router.push(endpoints.routes.GARAGE_LIST);
        },

        async delMaintenance(ctx, id) {
            const response = await api("/maintenances/" + id, methods.DELETE);
            if (response.error) return alert(response.error);
            ctx.dispatch("storeMaintenances");
        },
    }
};