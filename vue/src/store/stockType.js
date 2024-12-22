import { api } from "@/common/utils";


const getDefaultState = () => ({
    stockTypes: [],
});

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        storeStockTypes(state, payload) {
            state.stockTypes = payload;
        },
        reset(state) {
            Object.assign(state, getDefaultState());
        }
    },
    actions: {
        async storeStockTypes(ctx) {
            if (ctx.state.stockTypes.length) return;
            const response = await api("/stock_type");
            ctx.commit("storeStockTypes", response);
        },
    }
};