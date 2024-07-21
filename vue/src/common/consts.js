export const consts = Object.freeze({
    TOKEN: "token"
});

export const methods = Object.freeze({
    GET: "GET",
    POST: "POST",
    PUT: "PUT",
    PATCH: "PATCH",
    DELETE: "DELETE",
});

export const endpoints = Object.freeze({
    routes: Object.freeze({
        INITIAL: "/",
        LOGIN: "/login",
        HOME: "/home",
        NOT_FOUND: "/:pathMatch(.*)*",
    }),
    names: Object.freeze({
        INITIAL: "initial",
        LOGIN: "login",
        HOME: "home",
        NOT_FOUND: "not found",
    })
});