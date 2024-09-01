import { client, consts, endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";


export default {
    namespaced: true,
    state: {
        budget: {},
        budgets: [],
    },
    mutations: {
        storeBudgets(state, payload) {
            state.budgets = payload;
        },
    },
    actions: {
        async createBudget(ctx, data) {
            const response = await api("/budgets", methods.POST, data);
            if (response.msg) return alert(response.msg);
            router.push(endpoints.routes.BUDGET_LIST);
        },

        async storeBudgets(ctx) {
            const response = await api("/budgets");
            const data = response.map(budget => ({
                ...budget,
                created_at: handleDate(budget.created_at),
                updated_at: handleDate(budget.updated_at),
            }));
            ctx.commit("storeBudgets", data);
        }
    }
};