import { client, consts, endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

export default {
    namespaced: true,
    state: {
        stockTypes: [],
    },
    mutations: {
        storeStockTypes(state, payload) {
            state.stockTypes = payload;
        },
    },
    actions: {
        async storeStockTypes(ctx) {
            const response = await api("/stock_type");
            ctx.commit("storeStockTypes", response);
        },
    }
};