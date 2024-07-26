export const consts = Object.freeze({
    TOKEN: "token",
    USER_ID: "userId",
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
        USERS: "/users",
        NOT_FOUND: "/:pathMatch(.*)*",
    }),
    names: Object.freeze({
        INITIAL: "initial",
        LOGIN: "login",
        HOME: "home",
        USERS: "users",
        NOT_FOUND: "not found",
    })
});

export const user = Object.freeze({
    keys: Object.freeze({
        NAME: "name",
        EMAIL: "email",
        PASSWORD: "password",
        CONFIRM_PASSWORD: "confirmPassword",
        CREATED_AT: "createdAt",
        UPDATED_AT: "updatedAt",
        TYPE: Object.freeze({
            THIS: "type",
            ADMIN: "admin",
            COMMON: "common"
        }),
    }),
    trans: Object.freeze({
        NAME: "nome",
        EMAIL: "email",
        PASSWORD: "senha",
        TYPE: "tipo"
    }),
});