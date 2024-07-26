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
        USERS: "/users/list",
        USER_EDIT: "/users/edit/:id",
        USER_CREATE: "/users/create",
        NOT_FOUND: "/:pathMatch(.*)*",
    }),
    names: Object.freeze({
        INITIAL: "initial",
        LOGIN: "login",
        HOME: "home",
        USERS: "users-list",
        USER_EDIT: "users-edit",
        USER_CREATE: "users-create",
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

export const userForm = Object.freeze({
    NAME: Object.freeze({
        PLACEHOLDER: "Digite o nome",
        ICON: "user-solid",
        TYPE: "text",
        NAME: user.keys.NAME,
        LABEL: user.trans.NAME
    }),
    EMAIL: Object.freeze({
        PLACEHOLDER: "Digite o email",
        ICON: "envelope-solid",
        TYPE: "text",
        NAME: user.keys.EMAIL,
        LABEL: user.trans.EMAIL
    }),
    PASSWORD: Object.freeze({
        PLACEHOLDER: "Digite a senha",
        ICON: "key-solid",
        TYPE: "password",
        NAME: user.keys.PASSWORD,
        LABEL: user.trans.PASSWORD
    }),
    CONFIRM_PASSWORD: Object.freeze({
        PLACEHOLDER: "Digite a senha novamente",
        ICON: "key-solid",
        TYPE: "password",
        NAME: user.keys.CONFIRM_PASSWORD,
        LABEL: "Confirme a senha"
    }),
    TYPE: Object.freeze({
        PLACEHOLDER: "Selecione o tipo de usuário",
        NAME: user.keys.TYPE.THIS,
        LABEL: "Tipo de usuário",
    })
});