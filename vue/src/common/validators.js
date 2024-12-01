import { z } from "zod";
import { user as cUser, client as cClient, budget as cBudget, delivery as cDelivery  } from "./consts";
import store from "@/store";

const {
    NAME,
    EMAIL,
    PASSWORD,
    CONFIRM_PASSWORD,
    ADDRESS,
    CELLPHONE, 
    CEP,
    CNPJ,
    COST,
    CLIENT,
    PAYMENT_DATE,
    PAYMENT_METHOD,
    PROVIDER_CITY,
    PROVIDER_NAME,
    STOCKS,
    REVENUE,
    UNLOADED,
    PAYMENT_STATUS,
    DELIVERY_ADDRESS,
    DELIVERY_DATE,
    DRIVER,
    TRAVEL_COST,
    UNLOADING_COST,
    RECEIPT_DATE,
} = {...cUser.keys, ...cClient.keys, ...cBudget.keys, ...cDelivery.keys  };

const budgetLimits = Object.freeze({
    [DELIVERY_ADDRESS]: Object.freeze({
        min: 3,
        max: 256
    }),
    [PROVIDER_NAME]: Object.freeze({
        min: 3,
        max: 128
    }),
    [PROVIDER_CITY]: Object.freeze({
        min: 2,
        max: 128
    }),
    [STOCKS]: Object.freeze({
        max: 512
    })
});

export const base = Object.freeze({
    user: Object.freeze({
        [NAME]: Object.freeze({
            min: 3,
            max: 64
        }),
        [EMAIL]: Object.freeze({
            min: 8,
            max: 255
        }),
        [PASSWORD]: Object.freeze({
            min: 8,
            max: 64
        }),
    }),

    client: Object.freeze({
        [NAME]: Object.freeze({
            min: 8,
            max: 128,
        }),
        [ADDRESS]: Object.freeze({
            min: 20,
            max: 256
        }),
        [CEP]: Object.freeze({
            size: 8,
        }),
        [CELLPHONE]: Object.freeze({
            size: 11,
        }),
        [CNPJ]: Object.freeze({
            size: 14
        })
    }),

    budget: budgetLimits,
    delivery: {
        ...budgetLimits,
        [DRIVER]: Object.freeze({
            min: 3,
            max: 128,
        }),        
    }
});

const msgs = Object.freeze({
    diff: (field) => `Os campos de ${field} não coincidem`,
    valid: (field) => `O campo ${field} deve ser válido`,
    required: (field) => `O campo ${field} é obrigatório`,
    min: (field, val) => `O campo ${field} não contém o mínimo de ${val} caracteres`,
    max: (field, val) => `O campo ${field} contém mais caracteres do que o permitido de ${val}`,
    size: (field, val) => `O campo ${field} deve ter o exato tamanho de dígitos de ${val}`,
    twoCases: (field) => `O campo ${field} deve ter exatamente duas casas decimais`,
});

const nonempty = /^[^$]/;
const twoCases = /^\d{1,20}$|(?=^.{1,20}$)^\d+\.\d{0,2}$/i;
const includes = (arr) => ((el) => arr.includes(el));

function validate(validator, data) {
    const schema = validator.safeParse(data);
    console.log(schema.error);
    if (!schema.success) return schema.error.flatten().fieldErrors;
    return {};
}

function verify(schema, name, field, fields) {
    const errors = validate(schema, fields)[name];
    field.errors = errors || [];
    return errors;
}

export function verifyUser(name, obj) {
    const sel = obj || this;
    const curUser = store.state.userMod.user;
    const users = store.state.userMod.users
        .filter(({email}) => email !== curUser.email)
        .map(({email}) => email);

    const handleFields = {};
    [NAME, EMAIL, PASSWORD, CONFIRM_PASSWORD, cUser.keys.TYPE.THIS]
        .forEach(key => handleFields[key] = sel[key]?.value);
    const user = z.object({
        [EMAIL]: z
            .string()
            .trim()
            .toLowerCase()
            .regex(nonempty, msgs.required(cUser.trans.EMAIL))
            .email(msgs.valid(cUser.trans.EMAIL))
            .min(base.user[EMAIL].min, msgs.min(cUser.trans.EMAIL, base.user[EMAIL].min))
            .max(base.user[EMAIL].max, msgs.max(cUser.trans.EMAIL, base.user[EMAIL].max))
            .refine((el) => curUser.email === el || !users.includes(el), { message: "Email já em uso" }),
    
        [PASSWORD]: z
            .string()
            .trim()
            .regex(nonempty, msgs.required(cUser.trans.PASSWORD))
            .min(base.user[PASSWORD].min, msgs.min(cUser.trans.PASSWORD, base.user[PASSWORD].min))
            .max(base.user[PASSWORD].max, msgs.max(cUser.trans.PASSWORD, base.user[PASSWORD].max)),
        
        [NAME]: z
            .string()
            .trim()
            .regex(nonempty, msgs.required(cUser.trans.NAME))
            .min(base.user[NAME].min, msgs.min(cUser.trans.NAME, base.user[NAME].min))
            .max(base.user[NAME].max, msgs.max(cUser.trans.NAME, base.user[NAME].max)),
    
        [CONFIRM_PASSWORD]: z.literal(handleFields[PASSWORD], {
            errorMap: () => ({ message: msgs.diff(cUser.trans.PASSWORD) }),
        }),
    
        [cUser.keys.TYPE.THIS]: z.enum([cUser.keys.TYPE.ADMIN, cUser.keys.TYPE.COMMON]) 
    });

    return verify(user, name, sel[name], handleFields);
}

export function verifyClient(name, obj) {
    const sel = obj || this;
    const handleFields = {};
    [NAME, ADDRESS, CEP, CELLPHONE, CNPJ]
        .forEach(key => handleFields[key] = sel[key]?.value);
    const client = z.object({
        [NAME]: z
            .string()
            .trim()
            .regex(nonempty, msgs.required(cClient.trans.NAME))
            .min(base.client[NAME].min, msgs.min(cClient.trans.NAME, base.client[NAME].min))
            .max(base.client[NAME].max, msgs.max(cClient.trans.NAME, base.client[NAME].max)),
      
        [ADDRESS]: z
            .string()
            .trim()
            .regex(nonempty, msgs.required(cClient.trans.ADDRESS))
            .min(base.client[ADDRESS].min, msgs.min(cClient.trans.ADDRESS, base.client[ADDRESS].min))
            .max(base.client[ADDRESS].max, msgs.max(cClient.trans.ADDRESS, base.client[ADDRESS].max)),
        
        [CEP]: z
            .number({message: msgs.size(cClient.trans.CEP, base.client[CEP].size)})
            .int(msgs.size(cClient.trans.CEP, base.client[CEP].size))
            .gte(Math.pow(10, base.client[CEP].size - 1), msgs.size(cClient.trans.CEP, base.client[CEP].size))
            .lte(Math.pow(10, base.client[CEP].size) - 1, msgs.size(cClient.trans.CEP, base.client[CEP].size))
            .optional(),

        [CNPJ]: z
            .number({message: msgs.size(cClient.trans.CNPJ, base.client[CNPJ].size)})
            .int(msgs.size(cClient.trans.CNPJ, base.client[CNPJ].size))
            .gte(Math.pow(10, base.client[CNPJ].size - 1), msgs.size(cClient.trans.CNPJ, base.client[CNPJ].size))
            .lte(Math.pow(10, base.client[CNPJ].size) - 1, msgs.size(cClient.trans.CNPJ, base.client[CNPJ].size))
            .optional(),

        [CELLPHONE]: z
            .number({message: msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size)})
            .int(msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size))
            .gte(Math.pow(10, base.client[CELLPHONE].size - 1), msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size))
            .lte(Math.pow(10, base.client[CELLPHONE].size) - 1, msgs.size(cClient.trans.CELLPHONE, base.client[CELLPHONE].size))
    });
    return verify(client, name, sel[name], handleFields);
}

const stockVal = z.object({
    type: z.enum(store.state.stockTypeMod.stockTypes.map(({id}) => id)),
    name: z
        .string()
        .regex(nonempty, msgs.required("nome"))
        .max(base.budget[STOCKS].max),
    weight: z.number({ message: msgs.required("peso") }).int("Precisa ser inteiro").positive("Precisa ser maior que zero"),
    quantity: z.number({ message: msgs.required("quantidade")}).int("Precisa ser inteiro").positive("Precisa ser maior que zero")
});

export function verifyBudget(name, obj) {
    const sel = obj || this;
    const handleFields = {};
    [ 
        COST,
        CLIENT,
        PAYMENT_DATE,
        PROVIDER_CITY,
        PROVIDER_NAME,
        STOCKS,
        REVENUE,
        DELIVERY_ADDRESS,
        DELIVERY_DATE,
        UNLOADED.THIS,
        PAYMENT_STATUS.THIS,
        PAYMENT_METHOD.THIS,
    ].forEach(key => handleFields[key] = sel[key]?.value);

    const clients = store.state.clientMod.clients.map(({id}) => id);
    const budget = z.object({
        [DELIVERY_DATE]: z
            .string()
            .date(msgs.required("data de entrega")),
        [DELIVERY_ADDRESS]: z
            .string()
            .regex(nonempty, msgs.required(cBudget.trans.DELIVERY_ADDRESS))      
            .min(base.budget[DELIVERY_ADDRESS].min, msgs.min(cBudget.trans.DELIVERY_ADDRESS, base.budget[DELIVERY_ADDRESS].min))
            .max(base.budget[DELIVERY_ADDRESS].max, msgs.max(cBudget.trans.DELIVERY_ADDRESS, base.budget[DELIVERY_ADDRESS].max)),
        [PROVIDER_NAME]: z
            .string()
            .regex(nonempty, msgs.required(cBudget.trans.PROVIDER_NAME))      
            .min(base.budget[PROVIDER_NAME].min, msgs.min(cBudget.trans.PROVIDER_NAME, base.budget[PROVIDER_NAME].min))
            .max(base.budget[PROVIDER_NAME].max, msgs.max(cBudget.trans.PROVIDER_NAME, base.budget[PROVIDER_NAME].max)),
        [PROVIDER_CITY]: z
            .string()
            .regex(nonempty, msgs.required(cBudget.trans.PROVIDER_CITY))      
            .min(base.budget[PROVIDER_CITY].min, msgs.min(cBudget.trans.PROVIDER_CITY, base.budget[PROVIDER_CITY].min))
            .max(base.budget[PROVIDER_CITY].max, msgs.max(cBudget.trans.PROVIDER_CITY, base.budget[PROVIDER_CITY].max)),
        [COST]: z
            .number({ message: msgs.required("custo") })
            .positive()
            // .regex(twoCases, msgs.twoCases(cBudget.trans.COST))
            .lte(handleFields[REVENUE], "O custo não pode ser maior que o faturamento"),
        [REVENUE]: z
            .number({ message: msgs.required("faturamento") })
            .positive()
            // .regex(twoCases, msgs.twoCases(cBudget.trans.REVENUE))
            .gte(handleFields[COST], "O faturamento não pode ser menor que o custo"),
        [CLIENT]: z
            .number({ message: msgs.valid("cliente") })
            .refine(includes(clients), { message: msgs.valid("cliente") })

    });
    return verify(budget, name, sel[name], handleFields);
}

export function verifyStock(obj, name) {
    return validate(stockVal, obj)[name] || [];
}

export function verifyDelivery(name, obj) {
    const sel = obj || this;
    const handleFields = {};
    [ 
        TRAVEL_COST,
        UNLOADING_COST,
        RECEIPT_DATE,
        CLIENT,
        DRIVER,
        PAYMENT_DATE,
        PROVIDER_CITY,
        PROVIDER_NAME,
        STOCKS,
        REVENUE,
        DELIVERY_ADDRESS,
        DELIVERY_DATE,
        UNLOADED.THIS,
        PAYMENT_STATUS.THIS,
        PAYMENT_METHOD.THIS,
    ].forEach(key => handleFields[key] = sel[key]?.value);

    const clients = store.state.clientMod.clients.map(({id}) => id);
    const delivery = z.object({
        [DELIVERY_DATE]: z
            .string()
            .date(msgs.required("data de entrega")),
        [DELIVERY_ADDRESS]: z
            .string()
            .regex(nonempty, msgs.required(cDelivery.trans.DELIVERY_ADDRESS))      
            .min(base.delivery[DELIVERY_ADDRESS].min, msgs.min(cDelivery.trans.DELIVERY_ADDRESS, base.budget[DELIVERY_ADDRESS].min))
            .max(base.delivery[DELIVERY_ADDRESS].max, msgs.max(cDelivery.trans.DELIVERY_ADDRESS, base.budget[DELIVERY_ADDRESS].max)),
        [PROVIDER_NAME]: z
            .string()
            .regex(nonempty, msgs.required(cDelivery.trans.PROVIDER_NAME))      
            .min(base.delivery[PROVIDER_NAME].min, msgs.min(cDelivery.trans.PROVIDER_NAME, base.budget[PROVIDER_NAME].min))
            .max(base.delivery[PROVIDER_NAME].max, msgs.max(cDelivery.trans.PROVIDER_NAME, base.budget[PROVIDER_NAME].max)),
        [PROVIDER_CITY]: z
            .string()
            .regex(nonempty, msgs.required(cDelivery.trans.PROVIDER_CITY))      
            .min(base.delivery[PROVIDER_CITY].min, msgs.min(cDelivery.trans.PROVIDER_CITY, base.delivery[PROVIDER_CITY].min))
            .max(base.delivery[PROVIDER_CITY].max, msgs.max(cDelivery.trans.PROVIDER_CITY, base.delivery[PROVIDER_CITY].max)),
        [TRAVEL_COST]: z
            .number({ message: msgs.required("custo de viagem") })
            .positive()
            // .regex(twoCases, msgs.twoCases(cDelivery.trans.COST))
            .lte(handleFields[REVENUE], "O custo de viagem não pode ser maior que o faturamento"),
        [UNLOADING_COST]: z
            .number({ message: msgs.required("custo de descarga") })
            .positive()
            // .regex(twoCases, msgs.twoCases(cDelivery.trans.COST))
            .lte(handleFields[REVENUE], "O custo de descarga não pode ser maior que o faturamento"),
        [REVENUE]: z
            .number({ message: msgs.required("faturamento") })
            .positive()
            // .regex(twoCases, msgs.twoCases(cDelivery.trans.REVENUE))
            .gte(handleFields[COST], "O faturamento não pode ser menor que o custo"),
        [CLIENT]: z
            .number({ message: msgs.valid("cliente") })
            .refine(includes(clients), { message: msgs.valid("cliente") }),
        [DRIVER]: z
            .string()
            .regex(nonempty, msgs.required(cDelivery.trans.DRIVER))      
            .min(base.delivery[DRIVER].min, msgs.min(cDelivery.trans.DRIVER, base.delivery[DRIVER].min))
            .max(base.delivery[DRIVER].max, msgs.max(cDelivery.trans.DRIVER, base.delivery[DRIVER].max)),
    });

    return verify(delivery, name, sel[name], handleFields);
}