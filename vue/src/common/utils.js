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