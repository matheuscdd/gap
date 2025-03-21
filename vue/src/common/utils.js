import router from "@/router";
import { methods, consts, endpoints } from "./consts";

export function jwtDecode(token) {
    return JSON.parse(atob(token.split(".")[1]));
}

export function getUUID() {
    const id =
        typeof crypto.randomUUID === "function"
            ? crypto.randomUUID()
            : String(Math.random()).replace(".", "");
    return "i" + id.substring(1);
}

export function formatCNPJ(CNPJ) {
    return CNPJ.replace(/^(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
}

export function formatCEP(CEP) {
    return CEP.replace(/^(\d{5})(\d)/,"$1-$2");
}

export function formatField(value, limit) {
    return value.length > limit ? value.slice(0, limit) + "..." : value;
}

export function renderFileReader(file) {
    return new Promise((resolve, reject) => {
        if (!file) return resolve();
        const fileReader = new FileReader();
        fileReader.readAsDataURL(file);
        fileReader.onload = () => resolve(fileReader.result);
        fileReader.onerror = reject;
    });
}

export function formatCPF(CPF) {
    return CPF.replace(/(\d{3})(\d{3})(\d{3})(\d{0,2})/, "$1.$2.$3-$4");
}

export function formatCellphone(cellphone) {
    return cellphone.replace(/(\d{2})(\d)(\d{4})/,"($1) $2$3-");
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
    if (response.ok) {
        return data;
    }
    return {
        error: `${data.msg} - ${data.errors ? JSON.stringify(data.errors) : ""}`, 
        status: response.status
    };
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

export function getNow() {
    return new Date();
}

export async function sleep(time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}

export function itemgetter(key) {
    return obj => obj[key];
}
  
export function getValues(data) {
    const result = {};
    Object.keys(data).forEach(key => result[key] = data[key]?.value);
    return result;
}

export function randomNumbers(min, max) {
    min = Math.ceil(min);
    max = Math.floor(max + 1);
    let result = Math.floor(Math.random() * (max - min) + min);
    if (result >= max) {
      result = max - 1;
    }
    return result;
}

export function randomColor(alpha) {
    let red = randomNumbers(0, 255);
    let green = randomNumbers(0, 255);
    let blue = randomNumbers(0, 255);
    let color = `rgb(${red},${green},${blue})`;
    let colora = `rgba(${red},${green},${blue},${alpha})`;
    return {color, colora};
}



export function handleDate(rawestDate) {
    try {
        const rawerDate = new Date(rawestDate);
        const rawDate = new Date(rawerDate.getTime() + rawerDate.getTimezoneOffset() * 60000);

        const [ year, month, day ] = rawDate.toLocaleDateString("sv").split("-");
        const date = new Date();
        date.setFullYear(year);
        date.setHours(0);
        date.setMinutes(0);
        date.setSeconds(0);
        date.setMonth(Number(month) - 1);
        date.setDate(day);
        return date;
    } catch(e) {
        return null;
    } 
}

export async function prepareDataBudget(ctx, action, verifyBudget, extra = {}) {
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
    if (errors.flat().filter(Boolean).length) return ctx.$store.state.iChoice.open("Verifique os campos marcados e tente novamente", true);
    const continues = await ctx.$store.state.iChoice.open("Esta operação não poderá ser desfeita. Deseja continuar?");
    if (!continues) return;
    ctx.$store.dispatch(`budgetMod/${action}Budget`, {...data, ...extra});
}

export async function prepareDataDelivery(ctx, action, verifyDelivery, extra = {}) {
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
    if (!ctx.invoice?.value) {
        delete data.invoice;
    } else {
        data.invoice = ctx.invoice.value;
    }

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
    if (errors.flat().filter(Boolean).length) return ctx.$store.state.iChoice.open("Verifique os campos marcados e tente novamente", true);
    const continues = await ctx.$store.state.iChoice.open("Esta operação não poderá ser desfeita. Deseja continuar?");
    if (!continues) return;
    await ctx.$store.dispatch(`deliveryMod/${action}`, {...data, ...extra});
    ctx.$store.commit("deliveryMod/storeBudget", {});
}