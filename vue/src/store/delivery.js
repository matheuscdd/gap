import { endpoints, methods } from "@/common/consts";
import { api, handleDate } from "@/common/utils";
import router from "@/router";

const getDefaultState = () => ({
    delivery: {},
    deliveries: [],
    partials: [],
    treemap: [],
    calendar: {}
});

function rola(el) {
    const [ year, month, day ] = el.date.split("-");
    const date = new Date();
    date.setFullYear(year);
    date.setHours(0);
    date.setMinutes(0);
    date.setSeconds(0);
    date.setMonth(Number(month) - 1);
    date.setDate(day);
    let color = "--green-1";
    if (el.type === "delivery") {
        color = el.isPartial ? "--orange-1" : "--red-2";
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
        storeDeliveries(state, payload) {
            state.deliveries = payload.toReversed();
        },
        storeDelivery(state, payload) {
            state.delivery = payload;
        },
        storePartials(state, payload) {
            state.partials = payload;
        },
        storeTreemap(state, payload) {
            state.treemap = payload;
        },
        storeCalendar(state, { holidays, deliveries }) {
            state.calendar = {
                events: [...holidays.map(rola), ...deliveries.map(rola)]
            };
        },
        reset(state) {
            Object.assign(state, getDefaultState());
        }
    },
    actions: {
        async createFullDelivery(ctx, data) {
            const response = await api("/deliveries/full", methods.POST, data);
            if (response.error) return alert(response.error);
            router.push(endpoints.routes.DELIVERY_LIST);
        },

        async createPartialDelivery(ctx, data) {
            const response = await api("/deliveries/partial/" + data.id , methods.PUT, data);
            if (response.error) return alert(response.error);
            router.push(endpoints.routes.DELIVERY_VIEW_FULL.replace(":id", data.id));
        },

        async editFullDelivery(ctx, data) {
            const response = await api("/deliveries/full/" + data.id, methods.PATCH, data);
            if (response.error) return alert(response.error);
            ctx.commit("storeDelivery", response);
            router.push(endpoints.routes.DELIVERY_LIST);
        },

        async finishFull(ctx, id) {
            const response = await api("/deliveries/full/finish/" + id, methods.PATCH);
            if (response.error) return alert(response.error);
        },

        async finishPartial(ctx, id) {
            const response = await api("/deliveries/partial/finish/" + id, methods.PATCH);
            if (response.error) return alert(response.error);
        },

        async storeDeliveries(ctx) {
            const response = await api("/deliveries/full");
            const data = response.map(delivery => ({
                ...delivery,
                created_at: handleDate(delivery.created_at),
                updated_at: handleDate(delivery.updated_at),
            }));
            ctx.commit("storeDeliveries", data);
        },

        async storeDelivery(ctx, id) {
            const response = await api("/deliveries/full/" + id);
            if (response.error) return;
            ctx.commit("storeDelivery", response);
        },

        async storePartials(ctx, id) {
            const response = await api("/deliveries/partial/" + id);
            if (response.error) return;
            ctx.commit("storePartials", response);
        },

        async storeTreemap(ctx) {
            const response = await api("/deliveries/treemap/");
            if (response.error) return;
            ctx.commit("storeTreemap", response);
        },

        async storeCalendar(ctx) {
            const data = await api("/deliveries/calendar/");
            ctx.commit("storeCalendar", data);
        }
    }
};