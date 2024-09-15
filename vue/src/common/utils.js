import router from "@/router";
import { methods, consts, endpoints } from "./consts";
import { jwtDecode } from "jwt-decode";

export function getUUID() {
    return "i" + crypto.randomUUID().substring(1);
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

    const response = await fetch(process.env.VUE_APP_API_URL + url, request);
    if (response.status === 204) return {};
    const data = await response.json();
    if ([200, 201].includes(response.status)) {
        return data;
    } 
    return {error: data.msg};
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
    Object.keys(data).forEach(key => result[key] = data[key].value);
    return result;
}

export function getErrors(data) {
    return Object.keys(data).find(field => data[field].errors.length || !data[field].value);
}

export async function prepareDataBudget(ctx, action, verifyBudget, extra = {}) {
    ctx.revenue.value = Number(ctx.revenue.value) || "";
    ctx.cost.value = Number(ctx.cost.value) || "";
    const data = {
        stocks: ctx.stocks.map(({type, name, quantity, weight, extra }) => ({type, name, quantity, weight, extra })),
        client: ctx.client.value,
        delivery_address: ctx.delivery_address.value,
        delivery_date: ctx.delivery_date.value,
        provider_name: ctx.provider_name.value,
        provider_city: ctx.provider_city.value,
        revenue: ctx.revenue.value,
        cost: ctx.cost.value,
        unloaded: ctx.unloaded.value,
        payment_status: ctx.payment_status.value,
        payment_method: ctx.payment_method.value,
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
    ctx.$store.dispatch(`budgetMod/${action}Budget`, {...data, ...extra});
}