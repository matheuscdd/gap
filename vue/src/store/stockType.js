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
            const icons = {
                1: "sack-xmark-solid",
                2: "ruler-combined-solid",
                3: "pallet-solid"
            };
            const data = response.map((el, i) => ({value: el.id, text: el.name, icon: icons[i + 1] || "box-solid"}));
            ctx.commit("storeStockTypes", data);
        },
    }
};