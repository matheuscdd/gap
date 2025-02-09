import { client, consts, endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";


const getDefaultState = () => ({
    budget: {},
    budgets: [],
});

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        storeBudgets(state, payload) {
            state.budgets = payload.toReversed();
        },
        storeBudget(state, payload) {
            state.budget = payload;
        },
        reset(state) {
            Object.assign(state, getDefaultState());
        }
    },
    actions: {
        async createBudget(ctx, data) {
            const response = await api("/budgets", methods.POST, data);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            router.push(endpoints.routes.BUDGET_LIST);
        },

        async editBudget(ctx, data) {
            const response = await api("/budgets/" + data.id, methods.PATCH, data);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.commit("storeBudget", response);
            router.push(endpoints.routes.BUDGET_LIST);
        },

        async storeBudgets(ctx) {
            const response = await api("/budgets");
            const data = response.map(budget => ({
                ...budget,
                created_at: new Date(budget.created_at),
                updated_at: new Date(budget.updated_at),
                delivery_date: handleDate(budget.delivery_date),
                payment_date: handleDate(budget.payment_date),
            }));
            ctx.commit("storeBudgets", data);
        },

        async storeBudget(ctx, id) {
            const response = await api("/budgets/" + id);
            if (response.error) return;
            ctx.commit("storeBudget", response);
        },

        async delBudget(ctx, id) {
            const response = await api("/budgets/" + id, methods.DELETE);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.dispatch("storeBudgets");
        },
    }
};