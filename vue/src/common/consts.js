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
        USER_LIST: "/users/list",
        USER_EDIT: "/users/edit/:id",
        USER_CREATE: "/users/create",
        CLIENT_CREATE: "/clients/create",
        CLIENT_LIST: "/clients/list",
        CLIENT_EDIT: "/clients/edit/:id",
        BUDGET_CREATE: "/budgets/create",
        BUDGET_EDIT: "/budgets/edit/:id",
        BUDGET_LIST: "/budgets/list",
        DELIVERY_CREATE_FULL: "/deliveries/full/create",
        DELIVERY_EDIT_FULL_FULL: "/deliveries/full/edit/:id",
        DELIVERY_DASH: "/deliveries/dash",
        DELIVERY_LIST: "/deliveries/list",
        DELIVERY_CALENDAR: "/deliveries/calendar",
        DELIVERY_CREATE_PARTIAL: "/deliveries/partial/create/:id",
        DELIVERY_VIEW_FULL: "/deliveries/full/view/:id",
        TRUCK_CREATE: "/trucks/create",
        TRUCK_EDIT: "/trucks/edit/:id",
        MAINTENANCE_CREATE: "/maintenances/create",
        MAINTENANCE_EDIT: "/maintenances/edit/:id",
        DRIVER_CREATE: "/drivers/create",
        DRIVER_EDIT: "/drivers/edit/:id",
        DRIVER_LIST: "/drivers/list",
        GARAGE_LIST: "/garage/list",
        NOT_FOUND: "/:pathMatch(.*)*",
    }),
    names: Object.freeze({
        INITIAL: "initial",
        LOGIN: "login",
        HOME: "home",
        USER_LIST: "users-list",
        USER_EDIT: "users-edit",
        USER_CREATE: "users-create",
        CLIENT_CREATE: "clients-create",
        CLIENT_LIST: "clients-list",
        CLIENT_EDIT: "clients-edit",
        BUDGET_CREATE: "budge-create",
        BUDGET_EDIT: "budge-edit",
        BUDGET_LIST: "budgets-list",
        DELIVERY_CREATE_FULL: "deliveries-full-create",
        DELIVERY_EDIT_FULL: "deliveries-full-edit",
        DELIVERY_LIST: "deliveries-list",
        DELIVERY_DASH: "deliveries-dash",
        DELIVERY_CALENDAR: "deliveries-calendar",
        DELIVERY_CREATE_PARTIAL: "deliveries-partial-create",
        DELIVERY_VIEW_FULL: "deliveries-full-view",
        TRUCK_CREATE: "trucks-create",
        TRUCK_EDIT: "trucks-edit",
        MAINTENANCE_CREATE: "maintenances-create",
        MAINTENANCE_EDIT: "maintenances-edit",
        DRIVER_CREATE: "drivers-create",
        DRIVER_EDIT: "drivers-edit",
        DRIVER_LIST: "drivers-list",
        GARAGE_LIST: "garage-list",
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

export const client = Object.freeze({
    keys: Object.freeze({
        NAME: "name",
        CNPJ: "CNPJ",
        CEP: "CEP",
        ADDRESS: "address",
        CELLPHONE: "cellphone",
    }),
    trans: Object.freeze({
        NAME: "nome",
        CNPJ: "CNPJ",
        CEP: "CEP",
        ADDRESS: "endereço",
        CELLPHONE: "celular",
    })
});

export const clientForm = Object.freeze({
    NAME: Object.freeze({
        PLACEHOLDER: "Digite o nome",
        ICON: "building-solid",
        TYPE: "text",
        NAME: client.keys.NAME,
        LABEL: client.trans.NAME
    }),
    CNPJ: Object.freeze({
        PLACEHOLDER: "Digite o CNPJ",
        ICON: "id-card-solid",
        TYPE: "number",
        NAME: client.keys.CNPJ,
        LABEL: client.trans.CNPJ
    }),
    ADDRESS: Object.freeze({
        PLACEHOLDER: "Digite o endereço",
        ICON: "address-book-solid",
        TYPE: "text",
        NAME: client.keys.ADDRESS,
        LABEL: client.trans.ADDRESS
    }),
    CEP: Object.freeze({
        PLACEHOLDER: "Digite o CEP",
        ICON: "map-solid",
        TYPE: "number",
        NAME: client.keys.CEP,
        LABEL: client.trans.CEP
    }),
    CELLPHONE: Object.freeze({
        PLACEHOLDER: "Digite o telefone",
        ICON: "phone-solid",
        TYPE: "number",
        NAME: client.keys.CELLPHONE,
        LABEL: client.trans.CELLPHONE
    }),
});

export const budget = Object.freeze({
    keys: Object.freeze({
        CLIENT: "client",
        DELIVERY_DATE: "delivery_date",
        DELIVERY_ADDRESS: "delivery_address",
        PROVIDER_NAME: "provider_name",
        PROVIDER_CITY: "provider_city",
        PAYMENT_DATE: "payment_date",
        REVENUE: "revenue",
        COST: "cost",
        STOCKS: "stocks",
        UNLOADED: Object.freeze({
            THIS: "unloaded",
            CLIENT: "client",
            CARRIER: "carrier"
        }),
        PAYMENT_STATUS: Object.freeze({
            THIS: "payment_status",
            PAID: "paid",
            PENDING: "pending"
        }),
        PAYMENT_METHOD: Object.freeze({
            THIS: "payment_method",
            PIX: "pix",
            TICKET: "ticket"
        }),
    }),
    trans: Object.freeze({
        CLIENT: "cliente",
        DELIVERY_DATE: "data de entrega",
        DELIVERY_ADDRESS: "endereço de entrega",
        PROVIDER_NAME: "nome do fornecedor",
        PROVIDER_CITY: "cidade do fornecedor",
        PAYMENT_DATE: "data de pagamento",
        REVENUE: "faturamento",
        COST: "custo",
        STOCKS: "produtos",
        UNLOADED: "descarga",
        PAYMENT_STATUS: "status de pagamento",
        PAYMENT_METHOD: "método de pagamento",
    }),
});

export const budgetForm = Object.freeze({
    CLIENT: Object.freeze({
        PLACEHOLDER: "Escolha seu cliente",
        ICON: "building-solid",
        NAME: budget.keys.CLIENT,
        LABEL: budget.trans.CLIENT
    }),
    DELIVERY_DATE: Object.freeze({
        PLACEHOLDER: "Data de entrega",
        ICON: "truck-solid",
        TYPE: "date",
        NAME: budget.keys.DELIVERY_DATE,
        LABEL: budget.trans.DELIVERY_DATE
    }),
    DELIVERY_ADDRESS: Object.freeze({
        PLACEHOLDER: "Endereço de entrega",
        ICON: "map-solid",
        TYPE: "text",
        NAME: budget.keys.DELIVERY_ADDRESS,
        LABEL: budget.trans.DELIVERY_ADDRESS
    }),
    PROVIDER_NAME: Object.freeze({
        PLACEHOLDER: "Nome do fornecedor",
        ICON: "industry-solid",
        TYPE: "text",
        NAME: budget.keys.PROVIDER_NAME,
        LABEL: budget.trans.PROVIDER_NAME
    }),
    PROVIDER_CITY: Object.freeze({
        PLACEHOLDER: "Cidade do fornecedor",
        ICON: "city-solid",
        TYPE: "text",
        NAME: budget.keys.PROVIDER_CITY,
        LABEL: budget.trans.PROVIDER_CITY
    }),
    PAYMENT_DATE: Object.freeze({
        PLACEHOLDER: "Data de pagamento",
        ICON: "cash-register-solid",
        TYPE: "date",
        NAME: budget.keys.PAYMENT_DATE,
        LABEL: budget.trans.PAYMENT_DATE
    }),
    REVENUE: Object.freeze({
        PLACEHOLDER: "Defina o faturamento",
        ICON: "arrow-up-solid",
        TYPE: "number",
        NAME: budget.keys.REVENUE,
        LABEL: budget.trans.REVENUE
    }),
    COST: Object.freeze({
        PLACEHOLDER: "Defina o custo",
        ICON: "arrow-down-solid",
        TYPE: "number",
        NAME: budget.keys.COST,
        LABEL: budget.trans.COST
    }),
    UNLOADED: Object.freeze({
        PLACEHOLDER: "Modo de descarga",
        ICON: "dolly-solid",
        NAME: budget.keys.UNLOADED,
        LABEL: budget.trans.UNLOADED
    }),
    PAYMENT_STATUS: Object.freeze({
        PLACEHOLDER: "Status do pagamento",
        ICON: "money-bill-transfer-solid",
        NAME: budget.keys.PAYMENT_STATUS,
        LABEL: budget.trans.PAYMENT_STATUS
    }),
    PAYMENT_METHOD: Object.freeze({
        PLACEHOLDER: "Método de pagamento",
        ICON: "barcode-solid",
        NAME: budget.keys.PAYMENT_METHOD,
        LABEL: budget.trans.PAYMENT_METHOD
    }),
});

export const delivery = Object.freeze({
    keys: Object.freeze({
        ...budget.keys,
        TRAVEL_COST: "travel_cost",
        UNLOADING_COST: "unloading_cost",
        DRIVER: "driver",
        RECEIPT_DATE: "receipt_date",
        INVOICE: "invoice",
        COST: null,
    }),

    trans: Object.freeze({
        ...budget.trans,
        TRAVEL_COST: "custo da viagem",
        UNLOADING_COST: "custo da descarga",
        DRIVER: "motorista",
        RECEIPT_DATE: "data de recebimento",
        INVOICE: "nota fiscal",
        COST: null,
    })
});

export const deliveryForm = Object.freeze({
    ...budgetForm,
    COST: null,
    TRAVEL_COST: Object.freeze({
        PLACEHOLDER: "Indique o valor",
        ICON: "gas-pump-solid",
        TYPE: "number",
        NAME: delivery.keys.TRAVEL_COST,
        LABEL: delivery.trans.TRAVEL_COST
    }),
    UNLOADING_COST: Object.freeze({
        PLACEHOLDER: "Indique o valor",
        ICON: "people-carry-box-solid",
        TYPE: "number",
        NAME: delivery.keys.UNLOADING_COST,
        LABEL: delivery.trans.UNLOADING_COST
    }),
    DRIVER: Object.freeze({
        PLACEHOLDER: "Indique o motorista",
        ICON: "user-solid",
        TYPE: "text",
        NAME: delivery.keys.DRIVER,
        LABEL: delivery.trans.DRIVER
    }),
    INVOICE: Object.freeze({
        PLACEHOLDER: "Indique se existir",
        ICON: "qrcode-solid",
        TYPE: "text",
        NAME: delivery.keys.INVOICE,
        LABEL: delivery.trans.INVOICE
    }),
    RECEIPT_DATE: Object.freeze({
        PLACEHOLDER: "Data de chegada da mercadoria",
        ICON: "truck-ramp-box-solid",
        TYPE: "date",
        NAME: delivery.keys.RECEIPT_DATE,
        LABEL: delivery.trans.RECEIPT_DATE
    }),
});

export const truck = Object.freeze({
    keys: Object.freeze({
        PLATE: "plate",
        AXIS: "axis",
        PHOTO: "photo"
    }),
    trans: Object.freeze({
        PLATE: "placa",
        AXIS: "eixos",
        PHOTO: "foto"
    }),
});

export const truckForm = Object.freeze({
    PLATE: Object.freeze({
        PLACEHOLDER: "Placa do veículo",
        ICON: "truck-front-solid",
        TYPE: "text",
        NAME: truck.keys.PLATE,
        LABEL: truck.trans.PLATE
    }),
    AXIS: Object.freeze({
        PLACEHOLDER: "Quantidade de eixos",
        ICON: "truck-moving-solid",
        TYPE: "number",
        NAME: truck.keys.AXIS,
        LABEL: truck.trans.AXIS
    }),
    PHOTO: Object.freeze({
        PLACEHOLDER: "Foto do veículo",
        ICON: "camera-solid",
        TYPE: "file",
        NAME: truck.keys.PHOTO,
        LABEL: truck.trans.PHOTO,
        ACCEPT: "image/png, image/jpeg",
    }),
});

export const maintenance = Object.freeze({
    keys: Object.freeze({
        SERVICE: "service",
        KM: "km",
        COST: "cost",
        DATE: "date",
        TRUCK: "truck",
    }),
    trans: Object.freeze({
        SERVICE: "serviço",
        KM: "quilometragem",
        COST: "custo",
        DATE: "data",
        TRUCK: "caminhão",
    }),
});

export const maintenanceForm = Object.freeze({
    SERVICE: Object.freeze({
        PLACEHOLDER: "Reparo realizado",
        ICON: "screwdriver-wrench-solid",
        TYPE: "text",
        NAME: maintenance.keys.SERVICE,
        LABEL: maintenance.trans.SERVICE
    }),
    KM: Object.freeze({
        PLACEHOLDER: "Insira a quilometragem",
        ICON: "gauge-high-solid",
        TYPE: "number",
        NAME: maintenance.keys.KM,
        LABEL: maintenance.trans.KM
    }),
    COST: Object.freeze({
        PLACEHOLDER: "Insira o custo",
        ICON: "arrow-down-solid",
        TYPE: "number",
        NAME: maintenance.keys.COST,
        LABEL: maintenance.trans.COST,
    }),
    DATE: Object.freeze({
        PLACEHOLDER: "Insira a data",
        ICON: "calendar-solid",
        TYPE: "date",
        NAME: maintenance.keys.DATE,
        LABEL: maintenance.trans.DATE,
    }),
    TRUCK: Object.freeze({
        PLACEHOLDER: "Insira o caminhão",
        ICON: "truck-solid",
        TYPE: "number",
        NAME: maintenance.keys.TRUCK,
        LABEL: maintenance.trans.TRUCK,
    }),
});

export const driver = Object.freeze({
    keys: Object.freeze({
        NAME: "name",
        CPF: "CPF",
    }),
    trans: Object.freeze({
        NAME: "nome",
        CPF: "CPF",
    }),
});

export const driverForm = Object.freeze({
    NAME: Object.freeze({
        PLACEHOLDER: "Insira o nome",
        ICON: "user-solid",
        TYPE: "text",
        NAME: driver.keys.NAME,
        LABEL: driver.trans.NAME
    }),
    CPF: Object.freeze({
        PLACEHOLDER: "Insira o CPF",
        ICON: "id-card-solid",
        TYPE: "text",
        NAME: driver.keys.CPF,
        LABEL: driver.trans.CPF
    }),
});