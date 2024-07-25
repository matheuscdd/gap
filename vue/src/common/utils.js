import router from "@/router";
import { methods, consts, endpoints } from "./consts";

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
    const data = await response.json();
    if ([200, 201, 204].includes(response.status)) {
        return data;
    }
    return {error: data.msg};
}

export async function refreshToken() {
    const token = localStorage.getItem(consts.TOKEN);
    if (!token) return;
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

export function handleDate(rawer) {
    const raw = new Date(rawer);
    const diff = raw.getTime() + raw.getTimezoneOffset() * 60 * 1000;
    const handle = new Date(diff).toLocaleString("pt-BR");
    return handle;
}