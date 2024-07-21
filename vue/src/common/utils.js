import { methods, consts } from "./consts";
// import { verify } from "jsonwebtoken";


export function getUUID() {
    return "i" + crypto.randomUUID().substring(1);
}



export async function api(url, method = methods.GET, body = null) {
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