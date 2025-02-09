import { endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

const getDefaultState = () => ({
    drivers: [],
    driver: {}
});

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        storeDriver(state, payload) {
            state.driver = payload;
        },

        storeDrivers(state, payload) {
            state.drivers = payload;
        },
        reset(state) {
            Object.assign(state, getDefaultState());
        },
    },
    actions: {
        async storeDriver(ctx, id) {
            const response = await api("/drivers/" + id);
            if (response.error) return;
            ctx.commit("storeDriver", response);
        },

        async storeDrivers(ctx) {
            const response = await api("/drivers");
            const data = response.map(driver => ({
                ...driver,
                created_at: new Date(driver.created_at),
                updated_at: new Date(driver.updated_at),
            }));
            ctx.commit("storeDrivers", data);
        },

        async createDriver(ctx, data) {
            const response = await api("/drivers", methods.POST, data);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            router.push(endpoints.routes.DRIVER_LIST);
        },

        async editDriver(ctx, data) {
            const response = await api("/drivers/" + data.id, methods.PATCH, data);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.commit("storeDriver", response);
            router.push(endpoints.routes.DRIVER_LIST);
        },

        async delDriver(ctx, id) {
            const response = await api("/drivers/" + id, methods.DELETE);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.dispatch("storeDrivers");
        },
    }
};