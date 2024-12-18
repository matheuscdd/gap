import router from "@/router";
import { methods, consts, endpoints } from "./consts";
import { jwtDecode } from "jwt-decode";

export function getUUID() {
    const id =
        typeof crypto.randomUUID === "function"
            ? crypto.randomUUID()
            : String(Math.random()).replace(".", "");
    return "i" + id.substring(1);
}

async function _api(url, method = methods.GET, body = null) {
    const token = localStorage.getItem(consts.TOKEN);
    const request = {
		method,
		headers: {
            "Content-Type": "application/json;",
            "Authorization": `Bearer ${token}`
        }
    };
    if (body) request.body = JSON.stringify(body);

    const response = await fetch(process.env.VUE_APP_API_URL + "/api" + url, request);
    if (response.status === 204) return {};
    const data = await response.json();
    if ([200, 201].includes(response.status)) {
        return data;
    } 
    return {error: `${data.msg} - ${data.errors ? JSON.stringify(data.errors) : ""}`};
}

export async function refreshToken() {
    const token = localStorage.getItem(consts.TOKEN);
    if (!token) return;
    const tenMinutes = 60 * 10; 
    const exp = new Date((jwtDecode(token).exp - tenMinutes) * 1000).getTime();
    const now = new Date().getTime();
    if (exp >= now) return;
    const response = await _api("/auth/refresh", methods.PUT, {token});
    if (response.token) return localStorage.setItem(consts.TOKEN, response.token);
    localStorage.removeItem(consts.TOKEN);
    router.push(endpoints.routes.LOGIN);
}

export async function api(url, method = methods.GET, body = null) {
    await refreshToken();
    const response = await _api(url, method, body);
    return response;
}

function convertUTC(raw) {
    return new Date(raw.getTime() + raw.getTimezoneOffset() * 60 * 1000);
}

export function handleDate(rawer) {
    return convertUTC(new Date(rawer));
}

export function getNow() {
    return convertUTC(new Date());
}

export async function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}
  
export function getValues(data) {
    const result = {};
    Object.keys(data).forEach(key => result[key] = data[key]?.value);
    return result;
}

export async function prepareDataBudget(ctx, action, verifyBudget, extra = {}) {
    ctx.revenue.value = Number(ctx.revenue.value) || "";
    ctx.cost.value = Number(ctx.cost.value) || "";
    const { 
        client, 
        delivery_address, 
        delivery_date, 
        provider_name, 
        provider_city, 
        revenue, 
        cost, 
        unloaded, 
        payment_status, 
        payment_method
    } = ctx;
    const data = {
        stocks: ctx.stocks.map(({type, name, quantity, weight, extra }) => ({type, name, quantity, weight, extra })),
        ...getValues({ 
            client, 
            delivery_address, 
            delivery_date, 
            provider_name, 
            provider_city, 
            revenue, 
            cost, 
            unloaded, 
            payment_status, 
            payment_method
        })
    };
    const paymentDate = ctx.payment_date.value;
    if (paymentDate) data.payment_date = paymentDate;

    const errors = [];
    Object.keys(data).forEach(key => errors.push(verifyBudget(key, ctx)));
    ctx.$refs.stocks.forEach(el => 
        ["name", "quantity", "weight"].forEach(name => 
            errors.push(el.outFocus({target: {name}}))
        )
    );
    await sleep(100);
    if (errors.flat().filter(Boolean).length) return alert("Verifique os campos marcados e tente novamente");
    if (!confirm("Esta operação não poderá ser desfeita. Deseja continuar?")) return;
    ctx.$store.dispatch(`budgetMod/${action}Budget`, {...data, ...extra});
}

export async function prepareDataDelivery(ctx, action, verifyDelivery, extra = {}) {
    ["revenue", "travel_cost", "unloading_cost"]
        .filter(key => ctx[key])
        .forEach(key => {
            console.log(ctx[key]);
            ctx[key].value = Number(ctx[key]?.value) || "";
        }
        );
    if (ctx.unloaded.value === "client") {
        ctx.unloading_cost.value = 0;
    }
    
    const { 
        client, 
        delivery_address, 
        delivery_date, 
        provider_name, 
        provider_city, 
        revenue, 
        unloaded, 
        payment_status, 
        payment_method,
        travel_cost,
        unloading_cost,
        driver,
        receipt_date,
        invoice,
    } = ctx;
    const data = {
        stocks: ctx.stocks.filter(el => !el.ignore).map(({type, name, quantity, weight, extra }) => ({type, name, quantity, weight, extra })),
        ...getValues({ 
            client, 
            delivery_address, 
            delivery_date, 
            provider_name, 
            provider_city, 
            revenue, 
            unloaded, 
            payment_status, 
            payment_method,
            travel_cost,
            unloading_cost,
            driver,
            receipt_date,
            invoice,
        })
    };
    const paymentDate = ctx.payment_date?.value;
    if (paymentDate) data.payment_date = paymentDate;
    data.invoice = ctx.invoice?.value === "" ? null : ctx.invoice?.value;

    const errors = [];
    const isNotPartial = ctx.$route.name !== endpoints.names.DELIVERY_CREATE_PARTIAL;
    let filter = Boolean;
    if (!isNotPartial) {
        filter = (el) => ["delivery_date", "delivery_address", "unloaded", "driver", "unloading_cost", "stocks"].includes(el);
    }
    Object.keys(data).filter(filter).forEach(key => errors.push(verifyDelivery(key, ctx)));
    ctx.$refs.stocks.filter(el => !el.ignore).forEach(el => 
        ["name", "quantity", "weight"].forEach(name => 
            errors.push(el.outFocus({target: {name}}))
        )
    );
    await sleep(100);
    if (errors.flat().filter(Boolean).length) return alert("Verifique os campos marcados e tente novamente");
    if (!confirm("Esta operação não poderá ser desfeita. Deseja continuar?")) return;
    ctx.$store.dispatch(`deliveryMod/${action}Delivery`, {...data, ...extra});
}