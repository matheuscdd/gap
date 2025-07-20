import { endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

const getDefaultState = () => ({
    budget: {},
    delivery: {},
    deliveries: [],
    partials: [],
    treemap: [],
    scatter: [],
    calendar: {},
});

function formatEvent(el) {
    const date = handleDate(el.date);

    let color = "--green-1";
    if (el.type === "delivery") {
        if (el.finished) color = "--blue-3";
        else if (el.isPartial) color = "--orange-1";
        else color =  "--red-2";
    }
    
    return {
        title: el.name,
        start: date,
        end: date,
        allDay: true,
        isHoliday: el.type !== "delivery",
        color: `var(${color})`,
        ...el,
    };
}

export default {
    namespaced: true,
    state: getDefaultState(),
    mutations: {
        storeFull(state, payload) {
            state.deliveries = payload.toReversed();
        },
        storeDelivery(state, payload) {
            state.delivery = payload;
        },
        storeBudget(state, payload) {
            state.budget = payload;
        },
        storePartials(state, payload) {
            state.partials = payload;
        },
        storeTreemap(state, payload) {
            state.treemap = payload;
        },
        storeScatter(state, payload) {
            state.scatter = payload;
        },
        storeCalendar(state, { holidays, deliveries }) {
            state.calendar = {
                events: [...holidays.map(formatEvent), ...deliveries.map(formatEvent)]
            };
        },
        reset(state) {
            Object.assign(state, getDefaultState());
        }
    },
    actions: {
        async createFull(ctx, data) {
            const response = await api("/deliveries/full", methods.POST, data);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            router.push(endpoints.routes.DELIVERY_LIST);
        },

        async createPartial(ctx, data) {
            const response = await api("/deliveries/partial/" + data.id , methods.PUT, data);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            router.push(endpoints.routes.DELIVERY_VIEW_FULL.replace(":id", data.id));
        },

        async editFull(ctx, data) {
            const response = await api("/deliveries/full/" + data.id, methods.PATCH, data);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.commit("storeDelivery", response);
            router.push(endpoints.routes.DELIVERY_LIST);
        },

        async finishFull(ctx, id) {
            const response = await api("/deliveries/full/finish/" + id, methods.PATCH);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.dispatch("storeFull");
        },

        async receiveFull(ctx, id) {
            const response = await api("/deliveries/full/receive/" + id, methods.PATCH);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.dispatch("storeFull");
        },

        async finishPartial(ctx, id) {
            const response = await api("/deliveries/partial/finish/" + id, methods.PATCH);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.dispatch("storePartials", ctx.state.delivery.id);
        },

        async storeFull(ctx) {
            const response = await api("/deliveries/full");
            const data = response.map(delivery => ({
                ...delivery,
                created_at: new Date(delivery.created_at),
                updated_at: new Date(delivery.updated_at),
                delivery_date: handleDate(delivery.delivery_date),
                payment_date: handleDate(delivery.payment_date),
                receipt_date: handleDate(delivery.receipt_date),
            }));
            ctx.commit("storeFull", data);
        },

        async storeDelivery(ctx, id) {
            const response = await api("/deliveries/full/" + id);
            if (response.error) return;
            ctx.commit("storeDelivery", response);
        },

        async storePartials(ctx, id) {
            const response = await api("/deliveries/partial/" + id);
            if (response.error && response.status !== 404) return;
            ctx.commit("storePartials", response);
        },

        async storeTreemap(ctx) {
            const response = await api("/deliveries/charts/treemap/");
            if (response.error) return;
            ctx.commit("storeTreemap", response);
        },

        async storeScatter(ctx, { start_date, end_date }) {
            const response = await api(
                "/deliveries/charts/scatter?" + new URLSearchParams({ start_date, end_date })
            );
            if (response.error) return;
            ctx.commit("storeScatter", response);
        },

        async storeCalendar(ctx) {
            const data = await api("/deliveries/calendar/");
            ctx.commit("storeCalendar", data);
        },

        async delFull(ctx, id) {
            const response = await api("/deliveries/full/" + id, methods.DELETE);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.dispatch("storeFull");
        },

        async delPartial(ctx, id) {
            const response = await api("/deliveries/partial/" + id, methods.DELETE);
            if (response.error) return ctx.rootState.iChoice.open(response.error, true);
            ctx.dispatch("storePartials", ctx.state.delivery.id);
        },
    }
};